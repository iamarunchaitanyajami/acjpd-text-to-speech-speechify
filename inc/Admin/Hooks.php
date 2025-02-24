<?php
/**
 * Setup project hooks.
 *
 * @package           acjpd-speechify-text-to-speech
 * @sub-package       WordPress
 */

namespace Acjpd\Speechify\TextToSpeech\Admin;

use Acjpd\Speechify\TextToSpeech\Cron\Speechify;
use Acjpd\Speechify\TextToSpeech\ServiceProvider\Request;
use Acjpd\Speechify\TextToSpeech\ServiceProvider\Response;
use Acjpd\Speechify\TextToSpeech\ServiceProvider\Speechify\Speech;

/**
 * Hooks.
 */
class Hooks {

	/**
	 * Initialise hooks.
	 *
	 * @return void
	 */
	public function init(): void {
		/**
		 * Listen to save/update post meta.
		 */
		add_action( 'add_post_meta', array( $this, 'trigger_speechify_podcast' ), 99, 3 );
		add_action( 'update_post_meta', array( $this, 'update_trigger_speechify_podcast' ), 99, 4 );

		/**
		 * Short-circuits updating metadata for speechify_conversion_id meta key.
		 */
		add_filter( 'update_post_metadata', array( $this, 'update_speechify_conversion_id' ), 99, 4 );
	}

	/**
	 * Short-circuits updating metadata for speechify_conversion_id meta key.
	 *
	 * @param bool|null $check Whether to allow updating metadata for the given type.
	 * @param int       $object_id ID of the object metadata is for.
	 * @param string    $meta_key Metadata key.
	 * @param mixed     $meta_value Metadata value. Must be serializable if non-scalar.
	 *
	 * @return int|bool|null
	 */
	public function update_speechify_conversion_id( ?bool $check, int $object_id, string $meta_key, mixed $meta_value ): int|bool|null {
		if ( 'speechify_conversion_id' !== $meta_key ) {
			return $check;
		}

		if ( ! empty( $meta_value ) ) {
			return $check;
		}

		return $object_id;
	}

	/**
	 * Trigger save/update meta keys for post types.
	 *
	 * @param int    $meta_id Meta id.
	 * @param int    $post_id Post id.
	 * @param string $meta_key Meta key.
	 * @param mixed  $_meta_value Meta Value.
	 *
	 * @return void
	 */
	public function update_trigger_speechify_podcast( int $meta_id, int $post_id, string $meta_key, mixed $_meta_value ): void {
		$this->trigger_speechify_podcast( $post_id, $meta_key, $_meta_value );
	}


	/**
	 * Trigger save/update meta keys for post types.
	 *
	 * @param int    $post_id Post id.
	 * @param string $meta_key Meta key.
	 * @param mixed  $_meta_value Meta Value.
	 *
	 * @return void
	 */
	public function trigger_speechify_podcast( int $post_id, string $meta_key, mixed $_meta_value ): void {
		if ( $meta_key !== 'speechify_enable_conversion' ) {
			return;
		}

		$post_speech = get_post( $post_id );
		if ( ! $post_speech instanceof \WP_Post ) {
			return;
		}

		$post_title           = $post_speech->post_title;
		$is_conversion_exists = get_post_meta( $post_id, 'speechify_conversion_id', true );
		if ( 'true' !== $_meta_value || ! empty( $is_conversion_exists ) ) {
			return;
		}

		/**
		 * Remove all block tags.
		 *
		 * @param string $content
		 *
		 * @return array|string|string[]|null
		 */
		$post_content = fn ( string $content ) => preg_replace( '/<!--\s*\/?\s*wp:[^>]+-->/', '', $content );
		$post_content = $post_content( $post_speech->post_content );
		$post_content = apply_filters( 'acjpd_stts_content_change', $post_content, $post_id, $post_speech );
		$speech       = new Speech( array( 'body' => array( 'input' => _sanitize_text_fields( $post_content, true ) ) ) );

		/**
		 * Speechify Voice conversion.
		 */
		try {
			$speech_request  = new Request( $speech );
			$speech_response = new Response( $speech_request );
			$response        = $speech_response->get();
		} catch ( \HTTP_Request2_LogicException $e ) {
			return;
		}

		if ( is_wp_error( $response ) ) {
			return;
		}

		$post_title   = sprintf( 'Speechify - %d - %s', esc_html( $post_id ), esc_html( $post_title ) );
		$speechify_id = wp_insert_post(
			array(
				'post_title'   => wp_strip_all_tags( $post_title ),
				'post_content' => $speech->get_body()['input'],
				'post_status'  => 'publish',
				'post_type'    => 'speechify-podcast',
			)
		);

		if ( is_wp_error( $speechify_id ) ) {
			return;
		}

		update_post_meta( $post_id, 'speechify_conversion_id', $speechify_id );
		update_post_meta( $speechify_id, 'speechify_conversion_source_id', $post_id );
		update_post_meta( $speechify_id, 'speechify_conversion_response', $response );
		if ( ! empty( $response->audio_data ) ) {
			update_post_meta( $speechify_id, 'speechify_conversion_link', $response->audio_data );
			update_post_meta( $speechify_id, 'speechify_conversion_audio_format', $response->audio_format );
		}
	}
}

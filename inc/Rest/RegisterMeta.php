<?php
/**
 * Register post type meta.
 *
 * @package acjpd-speechify-text-to-speech
 * @sub-package WordPress
 */

namespace Acjpd\Speechify\TextToSpeech\Rest;

/**
 * Class.
 */
class RegisterMeta {

	/**
	 * Allowed post types.
	 *
	 * @var array
	 */
	private array $allowed_posts = array();

	/**
	 * Filter init.
	 *
	 * @return void
	 */
	public function init(): void {
		$wp_settings = alch_get_option( 'wp-settings' );
		if ( ! empty( $wp_settings['allowed_post_types'] ) ) {
			$this->allowed_posts = $wp_settings['allowed_post_types'];
		}

		$this->register_meta();
		foreach ( $this->allowed_posts as $post_type ) {
			$response_filter_name = wp_sprintf( 'rest_prepare_%s', esc_attr( $post_type ) );
			add_filter( $response_filter_name, array( $this, 'modify_speechify_field_in_rest' ), 99, 3 );
		}
	}

	/**
	 * Register meta key for allowed rest end points.
	 *
	 * @return void
	 */
	protected function register_meta(): void {
		$register_meta_keys = array(
			'speechify_enable_conversion' => array(
				'object_subtype' => 'post',
				'type'           => 'string',
				'single'         => true,
				'show_in_rest'   => true,
			),
			'speechify_conversion_id'     => array(
				'object_subtype' => 'post',
				'type'           => 'string',
				'single'         => true,
				'show_in_rest'   => true,
			),
		);

		foreach ( $register_meta_keys as $register_meta_key => $register_callbacks ) {
			foreach ( $this->allowed_posts as $allowed_post ) {
				$register_callbacks['object_subtype'] = $allowed_post;
				register_meta(
					'post',
					$register_meta_key,
					$register_callbacks
				);
			}
		}
	}

	/**
	 * Register speechify fields.
	 *
	 * @param \WP_REST_Response $response Rest response.
	 * @param \WP_Post          $main_post Post object.
	 * @param \WP_REST_Request  $request Request changes.
	 *
	 * @return \WP_REST_Response
	 */
	public static function modify_speechify_field_in_rest( \WP_REST_Response $response, \WP_Post $main_post, \WP_REST_Request $request ): \WP_REST_Response {
		if ( 'edit' === $request->get_param( 'context' ) && isset( $response->data['meta'] ) ) {
			$conversion_id   = ! empty( $response->data['meta']['speechify_conversion_id'] ) ? $response->data['meta']['speechify_conversion_id'] : 0;
			$conversion_link = array();
			if ( $conversion_id ) {
				$conversion_link = get_post_meta( $conversion_id, 'speechify_conversion_response', true );
			}

			$response->data['meta']['speechify_conversion_audio']  = ! empty( $conversion_link->audio_data ) ? $conversion_link->audio_data : '';
			$response->data['meta']['speechify_conversion_format'] = ! empty( $conversion_link->audio_format ) ? $conversion_link->audio_format : '';
			$response->data['meta']['speechify_conversion_done']   = ! empty( $response->data['meta']['speechify_conversion_audio'] );
		}

		return $response;
	}
}

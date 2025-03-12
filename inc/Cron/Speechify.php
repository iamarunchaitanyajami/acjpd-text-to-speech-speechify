<?php
/**
 * Register Speechify Cron.
 *
 * @package acjpd-text-to-speech-speechify
 * @sub-package WordPress
 */

namespace Acjpd\Speechify\TextToSpeech\Cron;

use Acjpd\Speechify\TextToSpeech\Admin\PostQuery;
use Acjpd\Speechify\TextToSpeech\ServiceProvider\Request;
use Acjpd\Speechify\TextToSpeech\ServiceProvider\Response;
use Acjpd\Speechify\TextToSpeech\ServiceProvider\Speechify\Voices;

/**
 * Speechify class.
 */
class Speechify extends Cron {
	/**
	 * Initiate all the cron`s.
	 *
	 * @return void
	 */
	public function init(): void {
		parent::init();

		/**
		 * Add Hooks.
		 */
		$import_voice_hook = ACJPD_TEXT_TO_SPEECH_PREFIX . 'cron_import_voice';

		/**
		 * Actions.php.
		 */
		add_action( $import_voice_hook, array( $this, 'import_voice' ) );

		/**
		 * Schedules
		 */
		$this->schedule_cron( $import_voice_hook, ACJPD_TEXT_TO_SPEECH_PREFIX . 'import_eight_hours' );
	}

	/**
	 * Import token.
	 *
	 * @return void
	 *
	 * @throws \WP_Error|\HTTP_Request2_LogicException Exceptions error.
	 */
	public function import_voice(): void {
		$voice          = new Voices();
		$voice_request  = new Request( $voice );
		$voice_response = new Response( $voice_request );
		$voice_list     = $voice_response->get();
		if ( is_wp_error( $voice_list ) || empty( $voice_list ) ) {
			return;
		}

		$post_query = new PostQuery( new \WP_Query( array() ) );
		foreach ( $voice_list as $voice ) {
			$voice_id = $post_query->find_one(
				array(
					'post_type'  => 'speechify-voice',
					'meta_query' => array( // phpcs:ignore
						array(
							'key'     => 'speechify-voice-id',
							'value'   => $voice->id,
							'compare' => '=',
						),
					),
				)
			);

			if ( $voice_id instanceof \WP_Post ) {
				continue;
			}

			$voice_post_id = $post_query->save(
				array(
					'post_type'    => 'speechify-voice',
					'post_title'   => $voice->display_name,
					'post_content' => '',
					'post_excerpt' => '',
					'post_status'  => 'publish',
				)
			);

			if ( is_wp_error( $voice_post_id ) || empty( $voice_post_id ) ) {
				continue;
			}

			update_post_meta( $voice_post_id, 'speechify-voice-id', $voice->id );
			update_post_meta( $voice_post_id, 'speechify-voice-type', $voice->type );
			update_post_meta( $voice_post_id, 'speechify-voice-models', $voice->models );
			update_post_meta( $voice_post_id, 'speechify-voice-gender', $voice->gender );
			update_post_meta( $voice_post_id, 'speechify-voice-preview_audio', $voice->preview_audio );
			update_post_meta( $voice_post_id, 'speechify-voice-avatar_image', $voice->avatar_image );
			update_post_meta( $voice_post_id, 'speechify-voice-tags', $voice->tags );
		}
	}
}

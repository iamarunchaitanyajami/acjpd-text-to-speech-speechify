<?php
/**
 * Register Custom Cron.
 *
 * @package acjpd-speechify-text-to-speech
 * @sub-package WordPress
 */

namespace Acjpd\Speechify\TextToSpeech\Cron;

/**
 * Class Cron Jobs.
 */
class Cron {

	/**
	 * Initiate all the cron`s.
	 *
	 * @return void
	 */
	public function init(): void {

		/**
		 * Add cron interval
		 */
		add_filter( 'cron_schedules', array( $this, 'add_cron_interval' ) );
	}

	/**
	 * Schedule cron.
	 *
	 * @param string $hook_name Hook name.
	 * @param string $recurrence Time period.
	 * @param array  $args Arguments.
	 *
	 * @return void
	 */
	public function schedule_cron( string $hook_name, string $recurrence, array $args = array() ): void {
		if ( ! wp_next_scheduled( $hook_name ) ) {
			wp_schedule_event( time(), $recurrence, $hook_name, $args );
		}
	}

	/**
	 * Add required cron intervals.
	 *
	 * @param array $schedules Wp Cron Schedules.
	 *
	 * @return array
	 */
	public function add_cron_interval( array $schedules ): array {
		$schedules[ ACJPD_TEXT_TO_SPEECH_PREFIX . 'import_eight_hours' ] = array(
			'interval' => 28800,
			'display'  => esc_html__( 'NewsUk Sport Feed Cron Import: Every Eight Hours', 'acjpd-speechify-text-to-speech' ),
		);

		$schedules[ ACJPD_TEXT_TO_SPEECH_PREFIX . 'import_every_one_hour' ] = array(
			'interval' => HOUR_IN_SECONDS,
			'display'  => esc_html__( 'NewsUk Sport Feed Cron Import: Every One Hour', 'acjpd-speechify-text-to-speech' ),
		);

		return apply_filters( ACJPD_TEXT_TO_SPEECH_PREFIX . 'import_cron_schedules', $schedules );
	}

	/**
	 * Is Cron disabled.
	 *
	 * @return bool
	 */
	protected function is_cron_disabled(): bool {
		return false !== get_option( 'acjpd_speechify_cron_disabled' );
	}
}

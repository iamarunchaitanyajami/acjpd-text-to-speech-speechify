<?php
/**
 * Setup admin Menu.
 *
 * @package           acjpd-speechify-text-to-speech
 * @sub-package       WordPress
 */

namespace Acjpd\Speechify\TextToSpeech\Admin;

/**
 * Menu class
 */
class Menu {
	/**
	 * Initiate all the actions here.
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'admin_menu', array( $this, 'menu_page' ) );
	}

	/**
	 * Setup menu page.
	 *
	 * @return void
	 */
	public function menu_page(): void {
		add_menu_page(
			__( 'Speechify Editor', 'acjpd-speechify-text-to-speech' ),
			__( 'Speechify Editor', 'acjpd-speechify-text-to-speech' ),
			'manage_options',
			'acjpd-speechify-text-to-speech-editor',
			array( $this, 'callback' ),
			'',
			60
		);
	}

	/**
	 * Callback function for admin page.
	 *
	 * @return void
	 */
	public function callback(): void {
		printf( '<div id="acjpd-speechify-text-to-speech-ui">%s</div>', esc_attr( __( 'Loading....', 'acjpd-speechify-text-to-speech' ) ) );
	}
}

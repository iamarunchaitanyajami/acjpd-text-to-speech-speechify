<?php
/**
 * Setup admin Menu.
 *
 * @package           acjpd-text-to-speech-speechify
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
			__( 'Speechify Editor', 'acjpd-text-to-speech-speechify' ),
			__( 'Speechify Editor', 'acjpd-text-to-speech-speechify' ),
			'manage_options',
			'acjpd-text-to-speech-speechify-editor',
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
		printf( '<div id="acjpd-text-to-speech-speechify-ui">%s</div>', esc_attr( __( 'Loading....', 'acjpd-text-to-speech-speechify' ) ) );
	}
}

<?php
/**
 * Plugin Name:       Text to Speech with Speechify
 * Plugin URI:        https://github.com/iamarunchaitanyajami/acjpd-text-to-speech-speechify
 * Description:       Generate high quality speech with our state-of-the-art Speechify in just single click.
 * Requires WP:       6.0 ( Minimal )
 * Requires PHP:      8.0
 * Version:           1.0.2
 * Author:            Arun Chaitanya Jami
 * Text Domain:       acjpd-text-to-speech-speechify
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package           acjpd-text-to-speech-speechify
 * @sub-package       WordPress
 */

namespace Acjpd\Speechify\TextToSpeech;

/**
 * If this file is called directly, abort.
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ACJPD_TEXT_TO_SPEECH_PLUGIN_VERSION', '1.0.2' );
define( 'ACJPD_TEXT_TO_SPEECH_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'ACJPD_TEXT_TO_SPEECH_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'ACJPD_TEXT_TO_SPEECH_PREFIX', 'acjpdstts_' );

/**
 * Composer Autoload file.
 */
if ( is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
	include __DIR__ . '/vendor/autoload.php';
}

if ( ! function_exists( 'alch_get_option' ) ) {
	include __DIR__ . '/vendor/alchemyoptions/alchemyoptions/alchemy-options.php';
}

$options = alch_get_option( 'api-credentials', array() );
define( 'ACJPD_TEXT_TO_SPEECH_AUTH_KEY', ! empty( $options ) && $options['api_key'] ? $options['api_key'] : '' );

use Acjpd\Speechify\TextToSpeech\Admin\Hooks;
use Acjpd\Speechify\TextToSpeech\Admin\Menu;
use Acjpd\Speechify\TextToSpeech\Admin\PostType;
use Acjpd\Speechify\TextToSpeech\Admin\Settings;
use Acjpd\Speechify\TextToSpeech\Cron\Speechify;
use Acjpd\Speechify\TextToSpeech\Rest\RegisterMeta;

/**
 * Initiate the menu here.
 */
( new Menu() )->init();

/**
 * Cron.
 */
( new Speechify() )->init();

/**
 * Register Post type.
 */
try {
	( new PostType(
		'speechify-podcast',
		array(
			'labels'       => array(
				'name' => 'Speechify Podcast',
			),
			'show_in_menu' => true,
			'show_ui'      => true,
		) 
	) )->run();
} catch ( \Exception $e ) {
	if ( function_exists( 'newrelic_notice_error' ) ) {
		newrelic_notice_error( 'Unable to register Sports Feed post type in the sports feed plugin', $e );
	}
}

try {
	( new PostType( 'speechify-voice', array( 'labels' => array( 'name' => 'Speechify Voice List' ) ) ) )->run();
} catch ( \Exception $e ) {
	if ( function_exists( 'newrelic_notice_error' ) ) {
		newrelic_notice_error( 'Unable to register Sports Feed post type in the sports feed plugin', $e );
	}
}

/**
 * Settings.
 */
add_action(
	'init',
	function () {
		( new Settings() )->init();
		( new RegisterMeta() )->init();
		( new Hooks() )->init();

		wp_dequeue_script( 'alch_admin_scripts' );
	},
	100
);

/**
 * Enqueue scripts.
 *
 * @return void
 */
function acjpd_tts_enqueue_scripts(): void {
	$block_settings = array(
		'ajaxUrl'      => esc_url( admin_url( 'admin-ajax.php', 'relative' ) ),
		'nonce_update' => wp_create_nonce( 'acjpd_tts_nonce_update' ),
		'nonce_save'   => wp_create_nonce( 'acjpd_tts_nonce_save' ),
		'settings'     => array(
			'wpSettings'        => alch_get_option( 'wp-settings' ),
			'speechifySettings' => alch_get_option( 'speech-settings' ),
		),
	);

	/**
	 * Add inline script for live blog and live blog entry.
	 */
	$asset_file = include ACJPD_TEXT_TO_SPEECH_DIR_PATH . 'build/index.asset.php';
	wp_register_script( 'acjpd_tts_menu_assets-js', ACJPD_TEXT_TO_SPEECH_DIR_URL . 'build/index.js', $asset_file['dependencies'], ACJPD_TEXT_TO_SPEECH_PLUGIN_VERSION, true );
	wp_localize_script( 'acjpd_tts_menu_assets-js', 'AcjIeBlocksEditorSettings', $block_settings );
	wp_enqueue_script( 'acjpd_tts_menu_assets-js' );
	wp_enqueue_style( 'acjpd_tts_menu_assets-global-css', ACJPD_TEXT_TO_SPEECH_DIR_URL . 'build/index.css', array(), ACJPD_TEXT_TO_SPEECH_PLUGIN_VERSION );
}

add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\acjpd_tts_enqueue_scripts' );

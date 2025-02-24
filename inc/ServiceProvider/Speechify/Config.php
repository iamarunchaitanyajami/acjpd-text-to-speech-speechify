<?php
/**
 * Speechify Config trait.
 *
 * @package acjpd-speechify-text-to-speech
 * @sub-package WordPress
 */

namespace Acjpd\Speechify\TextToSpeech\ServiceProvider\Speechify;

/**
 * Config trait.
 */
trait Config {

	/**
	 * Get client protocol.
	 *
	 * @return string
	 */
	public function get_protocol(): string {
		return 'https://';
	}

	/**
	 * Get/Set Domain.
	 *
	 * @return string
	 */
	public function get_domain(): string {
		return 'api.sws.speechify.com';
	}

	/**
	 * Get/Set Feed outletAuthKey.
	 *
	 * @see https://docs.sws.speechify.com/reference/createaccesstoken
	 *
	 * @return string
	 */
	public function get_auth_key(): string {
		return ACJPD_TEXT_TO_SPEECH_AUTH_KEY;
	}

	/**
	 * Version.
	 *
	 * @return string
	 */
	public function get_version(): string {
		return 'v1';
	}

	/**
	 * Limit the string word count.
	 *
	 * @param string $content Input String.
	 *
	 * @return string
	 */
	public function limit_input( string $content ): string {
		// Remove extra spaces and line breaks.
		$content = trim( wp_strip_all_tags( $content ) );

		// If total words is less than or equal to 3000, return full content.
		if ( mb_strlen( $content ) <= 3000 ) {
			return $content;
		}

		return mb_substr( $content, 0, 3000 );
	}
}

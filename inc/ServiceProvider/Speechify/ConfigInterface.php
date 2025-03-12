<?php
/**
 * Speechify Config Interface.
 *
 * @package acjpd-text-to-speech-speechify
 * @sub-package WordPress
 */

namespace Acjpd\Speechify\TextToSpeech\ServiceProvider\Speechify;

interface ConfigInterface {

	/**
	 * Get client protocol.
	 *
	 * @return string
	 */
	public function get_protocol(): string;

	/**
	 * Get/Set Domain.
	 *
	 * @return string
	 */
	public function get_domain(): string;

	/**
	 * Get/Set Feed outletAuthKey.
	 *
	 * @see https://docs.sws.speechify.com/reference/createaccesstoken
	 *
	 * @return string
	 */
	public function get_auth_key(): string;

	/**
	 * Version.
	 *
	 * @return string
	 */
	public function get_version(): string;

	/**
	 * Platform.
	 *
	 * @example : audio, voice, auth.
	 *
	 * @return string
	 */
	public function get_platform(): string;

	/**
	 * Headers.
	 *
	 * @return array
	 */
	public function get_headers(): array;

	/**
	 * Body.
	 *
	 * @return array
	 */
	public function get_body(): array;

	/**
	 * Method.
	 *
	 * @example post,get methods.
	 *
	 * @return string
	 */
	public function get_method(): string;
}

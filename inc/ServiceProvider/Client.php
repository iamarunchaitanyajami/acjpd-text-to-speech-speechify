<?php
/**
 * Client Interface.
 *
 * @package acjpd-text-to-speech-speechify
 * @sub-package WordPress
 */

namespace Acjpd\Speechify\TextToSpeech\ServiceProvider;

/**
 * Client Interface.
 */
interface Client extends \JsonSerializable {

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
	 * Get headers.
	 *
	 * @return array
	 */
	public function get_headers(): array;

	/**
	 * Get Body.
	 *
	 * @return array
	 */
	public function get_body(): array;

	/**
	 * Get query Parma.
	 *
	 * @return array
	 */
	public function get_params(): array;

	/**
	 * Get method.
	 *
	 * @return string
	 */
	public function get_method(): string;
}

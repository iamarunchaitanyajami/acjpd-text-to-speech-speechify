<?php
/**
 * Response Class.
 *
 * @package acjpd-text-to-speech-speechify
 * @sub-package WordPress
 */

namespace Acjpd\Speechify\TextToSpeech\ServiceProvider;

/**
 * Class Response.
 */
class Response {

	/**
	 * Request.
	 *
	 * @var Request
	 */
	private Request $request;

	/**
	 * Construct.
	 *
	 * @param Request $request Rest request.
	 */
	public function __construct( Request $request ) {
		$this->request = $request;
	}

	/**
	 * Get data.
	 *
	 * @throws \HTTP_Request2_LogicException Throws error.
	 */
	public function get(): mixed {
		$response = $this->request->send();
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		return json_decode( $response );
	}
}

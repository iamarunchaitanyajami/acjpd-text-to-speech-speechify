<?php
/**
 * Request Class.
 *
 * @package acjpd-text-to-speech-speechify
 * @sub-package WordPress
 */

namespace Acjpd\Speechify\TextToSpeech\ServiceProvider;

/**
 * Request Class.
 */
class Request {
	/**
	 * Constructor.
	 *
	 * @param object $config Api config.
	 */
	public function __construct( private object $config ) {}

	/**
	 * Send Request.
	 *
	 * @throws \WP_Error|\HTTP_Request2_LogicException Throws wp error or https logic exception.
	 */
	public function send(): string|\WP_Error {
		$data = $this->config->jsonSerialize();

		$request = new \HTTP_Request2();
		$request->setUrl( $data['url'] );
		$request->setMethod( $data['method'] );
		$request->setConfig(
			array(
				'follow_redirects' => true,
			) 
		);
		$request->setHeader( $data['headers'] );
		$request->setBody( $data['body'] );
		try {
			$response = $request->send();
			if ( $response->getStatus() === 200 ) {
				return $response->getBody();
			} else {
				return new \WP_Error( $response->getStatus(), $response->getReasonPhrase(), $response );
			}
		} catch ( \HTTP_Request2_Exception $e ) {
			return new \WP_Error( $e->getCode(), $e->getMessage(), $e );
		}
	}
}

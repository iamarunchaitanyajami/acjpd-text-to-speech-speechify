<?php
/**
 * Speechify Voice config.
 *
 * @package acjpd-text-to-speech-speechify
 * @sub-package WordPress
 */

namespace Acjpd\Speechify\TextToSpeech\ServiceProvider\Speechify;

/**
 * Speech class.
 */
class Voices implements ConfigInterface {
	use Config;

	/**
	 * Route type.
	 *
	 * @var string
	 */
	public string $type = '';

	/**
	 * Constructor.
	 *
	 * @param array $params Request params.
	 */
	public function __construct( protected array $params = array() ) {
	}

	/**
	 * Add platform.
	 *
	 * @return string
	 */
	public function get_platform(): string {
		return 'voices';
	}

	/**
	 * Headers.
	 *
	 * @return array
	 */
	public function get_headers(): array {
		$headers                  = array();
		$headers['Accept']        = $params['header']['accept'] ?? '*/*';
		$headers['Authorization'] = sprintf( 'Bearer %s', $this->get_auth_key() );

		return $headers;
	}

	/**
	 * Body.
	 *
	 * @return array
	 */
	public function get_body(): array {
		return array();
	}

	/**
	 * Method.
	 *
	 * @return string
	 *
	 * @example post,get methods.
	 */
	public function get_method(): string {
		return 'GET';
	}

	/**
	 * Specify data which should be serialized to JSON
	 *
	 * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return array data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4
	 */
	public function jsonSerialize(): array {
		$url  = sprintf(
			'%s%s/%s/%s',
			esc_attr( $this->get_protocol() ),
			esc_attr( $this->get_domain() ),
			esc_attr( $this->get_version() ),
			esc_attr( $this->get_platform() )
		);
		$path = sprintf( '%s/%s', esc_attr( $this->get_version() ), esc_attr( $this->get_platform() ) );

		return array(
			'headers'  => $this->get_headers(),
			'platform' => $this->get_platform(),
			'body'     => $this->get_body(),
			'domain'   => $this->get_domain(),
			'protocol' => $this->get_protocol(),
			'path'     => $path,
			'url'      => $url,
			'method'   => $this->get_method(),
		);
	}
}

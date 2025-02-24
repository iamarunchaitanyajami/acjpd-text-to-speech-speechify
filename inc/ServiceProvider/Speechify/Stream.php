<?php
/**
 * Speechify Stream config.
 *
 * @package acjpd-speechify-text-to-speech
 * @sub-package WordPress
 */

namespace Acjpd\Speechify\TextToSpeech\ServiceProvider\Speechify;

/**
 * Speech class.
 */
class Stream implements ConfigInterface {
	use Config;

	/**
	 * Route type.
	 *
	 * @var string
	 */
	public string $type = 'stream';

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
		return 'audio';
	}

	/**
	 * Headers.
	 *
	 * @return array
	 */
	public function get_headers(): array {
		$headers                  = array();
		$headers['content-type']  = $params['header']['content-type'] ?? 'application/json';
		$headers['Accept']        = $params['header']['accept'] ?? 'audio/mpeg';
		$headers['Authorization'] = sprintf( 'Bearer %s', $this->get_auth_key() );

		return $headers;
	}

	/**
	 * Body.
	 *
	 * @return array
	 */
	public function get_body(): array {
		$input = sprintf( '<speak>%s</speak>', esc_attr( $params['body']['input'] ?? '' ) );

		return array(
			'input'     => $input,
			'languages' => $params['body']['languages'] ?? 'en',
			'model'     => $params['body']['model'] ?? 'simba-base',
			'voice_id'  => $params['body']['voice_id'] ?? 'en_us_male',
			'options'   => array(
				'loudness_normalization' => true,
			),
		);
	}

	/**
	 * Method.
	 *
	 * @return string
	 *
	 * @example post,get methods.
	 */
	public function get_method(): string {
		return 'POST';
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
			'%s%s/%s/%s/%s',
			esc_attr( $this->get_protocol() ),
			esc_attr( $this->get_domain() ),
			esc_attr( $this->get_version() ),
			esc_attr( $this->get_platform() ),
			esc_attr( $this->type )
		);
		$path = sprintf( '%s/%s/%s', esc_attr( $this->get_version() ), esc_attr( $this->get_platform() ), esc_attr( $this->type ) );

		return array(
			'headers'  => $this->get_headers(),
			'platform' => $this->get_platform(),
			'body'     => wp_json_encode( $this->get_body() ),
			'domain'   => $this->get_domain(),
			'protocol' => $this->get_protocol(),
			'path'     => $path,
			'url'      => $url,
			'method'   => $this->get_method(),
		);
	}
}

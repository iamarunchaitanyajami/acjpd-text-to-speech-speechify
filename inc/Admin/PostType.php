<?php
/**
 * Post type class for multiple post type instances.
 *
 * @package acjpd-text-to-speech-speechify
 * @sub-package WordPress
 */

namespace Acjpd\Speechify\TextToSpeech\Admin;

/**
 * PostType Class.
 */
class PostType {

	/**
	 * Post type.
	 *
	 * @var string
	 */
	public string $post_type;

	/**
	 * Post type arguments.
	 *
	 * @var array
	 */
	private array $args;

	/**
	 * Class constructor.
	 *
	 * @param string $post_type Post type.
	 * @param array  $args Post type args.
	 *
	 * @throws \Exception Throws exception.
	 */
	public function __construct( string $post_type, array $args = array() ) {
		if ( empty( $post_type ) ) {
			return;
		}

		if ( post_type_exists( $post_type ) ) {
			throw new \Exception( 'Post type already exists.' );
		}

		$this->post_type = $post_type;
		$this->args      = $this->prepare_args( $args );
	}

	/**
	 * Prepare arguments.
	 *
	 * @param array $args Arguments.
	 *
	 * @return array
	 */
	protected function prepare_args( array $args = array() ): array {
		$default_args = array(
			'labels'             => array(
				'name' => $this->post_type,
			),
			'public'             => true,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 20,
			'supports'           => array( 'title', 'editor', 'custom-fields' ),
			'show_in_rest'       => true,
		);

		return apply_filters( 'acjpd_speechify_text_to_speech_post_type_args', array_merge( $default_args, $args ) );
	}

	/**
	 * Based method to call in-order for the post types to be initiated.
	 *
	 * @return void
	 */
	public function run(): void {
		add_action( 'init', array( $this, 'register' ) );
	}

	/**
	 * Register Post type.
	 *
	 * @return void
	 */
	public function register(): void {
		register_post_type( $this->post_type, $this->args ); // @phpcs:ignore
	}
}

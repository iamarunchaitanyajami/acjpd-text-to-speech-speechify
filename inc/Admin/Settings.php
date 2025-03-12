<?php
/**
 * Settings Menu.
 *
 * @package           acjpd-text-to-speech-speechify
 * @sub-package       WordPress
 */

namespace Acjpd\Speechify\TextToSpeech\Admin;

/**
 * Settings Class.
 */
class Settings {

	/**
	 * Page id.
	 *
	 * @var string
	 */
	public string $page_id = 'acjpd-text-to-speech-speechify-page';

	/**
	 * Hooks init.
	 *
	 * @return void
	 */
	public function init(): void {
		$icon_hook = sprintf( 'alch_%s_icon', esc_attr( $this->page_id ) );
		add_filter(
			$icon_hook,
			array(
				$this,
				'page_icon',
			)
		);

		add_filter( 'alch_options', array( $this, 'add_options' ) );
		add_filter( 'alch_options_pages', array( $this, 'pages' ) );
	}

	/**
	 * Change Icon.
	 *
	 * @return string
	 */
	public function page_icon(): string {
		return 'dashicons-edit';
	}

	/**
	 * Add options pages to the plugin.
	 *
	 * @param array $pages Existing pages.
	 *
	 * @return array
	 */
	public function pages( array $pages ): array {
		$my_pages = array(
			array(
				'id'   => $this->page_id,
				'name' => __( 'Speechify Settings', 'acjpd-text-to-speech-speechify' ),
				'tabs' => array(
					array(
						'id'   => 'api-settings',
						'name' => __( 'Credentials', 'acjpd-text-to-speech-speechify' ),
					),
					array(
						'id'   => 'speech-settings',
						'name' => __( 'Speech / Stream', 'acjpd-text-to-speech-speechify' ),
					),
					array(
						'id'   => 'wp-settings',
						'name' => __( 'Settings', 'acjpd-text-to-speech-speechify' ),
					),
				),
			),
		);

		return array_merge( $pages, $my_pages );
	}

	/**
	 * Add options to pages.
	 *
	 * @param array $options Existing options.
	 *
	 * @return array
	 */
	public function add_options( array $options ): array {
		$api_settings_options = array(
			array(
				'id'     => 'api-credentials',
				'place'  => array(
					'page' => $this->page_id,
					'tab'  => 'api-settings',
				),
				'type'   => 'field_group',
				'fields' => array(
					array(
						'title' => __( 'API Key', 'acjpd-text-to-speech-speechify' ),
						'id'    => 'api_key',
						'desc'  => __( 'Get key from https://console.sws.speechify.com/api-keys', 'acjpd-text-to-speech-speechify' ),
						'type'  => 'password',
						'place' => array(
							'page' => $this->page_id,
							'tab'  => 'api-settings',
						),
					),
				),
			),
		);

		$speech_settings_options = array(
			array(
				'id'     => 'speech-settings',
				'place'  => array(
					'page' => $this->page_id,
					'tab'  => 'speech-settings',
				),
				'type'   => 'field_group',
				'fields' => array(
					array(
						'title'   => __( 'Audio Format', 'acjpd-text-to-speech-speechify' ),
						'id'      => 'audio_format',
						'desc'    => __( 'The format for the output audio, Note, that the current default is "wav"', 'acjpd-text-to-speech-speechify' ),
						'type'    => 'select',
						'place'   => array(
							'page' => $this->page_id,
							'tab'  => 'api-settings',
						),
						'default' => 'wav',
						'choices' => array(
							array(
								'value' => 'wav',
								'label' => 'wav',
							),
							array(
								'value' => 'mp3',
								'label' => 'mp3',
							),
							array(
								'value' => 'ogg',
								'label' => 'ogg',
							),
							array(
								'value' => 'aac',
								'label' => 'aac',
							),
						),
					),
					array(
						'title'   => __( 'Language', 'acjpd-text-to-speech-speechify' ),
						'id'      => 'languages',
						'desc'    => __( 'Language of the input.', 'acjpd-text-to-speech-speechify' ),
						'type'    => 'select',
						'place'   => array(
							'page' => $this->page_id,
							'tab'  => 'api-settings',
						),
						'default' => 'en',
						'choices' => array(
							array(
								'value' => 'en',
								'label' => 'English',
							),
							array(
								'value' => 'fr-FR',
								'label' => 'French',
							),
							array(
								'value' => 'de-DE',
								'label' => 'German',
							),
							array(
								'value' => 'es-ES',
								'label' => 'Spanish',
							),
							array(
								'value' => 'pt-BR',
								'label' => 'Portuguese',
							),
							array(
								'value' => 'pt-PT',
								'label' => 'Portuguese',
							),
						),
					),
					array(
						'title'   => __( 'Model', 'acjpd-text-to-speech-speechify' ),
						'id'      => 'model',
						'desc'    => __( 'Model used for audio synthesis.', 'acjpd-text-to-speech-speechify' ),
						'type'    => 'select',
						'place'   => array(
							'page' => $this->page_id,
							'tab'  => 'api-settings',
						),
						'default' => 'simba-base',
						'choices' => array(
							array(
								'value' => 'simba-base',
								'label' => 'simba-base',
							),
							array(
								'value' => 'simba-english',
								'label' => 'simba-english',
							),
							array(
								'value' => 'simba-multilingual',
								'label' => 'simba-multilingual',
							),
							array(
								'value' => 'simba-turbo',
								'label' => 'simba-turbo',
							),
						),
					),
					array(
						'title'     => __( 'Voice Model', 'acjpd-text-to-speech-speechify' ),
						'id'        => 'voice_id',
						'desc'      => __( 'Id of the voice to be used for synthesizing speech. Make sure your wordpress cron`s were running.', 'acjpd-text-to-speech-speechify' ),
						'place'     => array(
							'page' => $this->page_id,
							'tab'  => 'api-settings',
						),
						'post-type' => 'speechify-voice',
						'type'      => 'post_type_select',
					),
				),
			),
		);

		$post_types     = get_post_types(
			array(
				'public' => true,
			),
			'objects'
		);
		$post_type_list = array();
		if ( $post_types ) {
			foreach ( $post_types as $post_type ) {
				if ( str_contains( $post_type->name, 'speechify' ) ) {
					continue;
				}

				$post_type_list[] = array(
					'name'  => $post_type->labels->name,
					'value' => $post_type->name,
				);
			}
		}

		$wp_settings_options = array(
			array(
				'id'     => 'wp-settings',
				'place'  => array(
					'page' => $this->page_id,
					'tab'  => 'wp-settings',
				),
				'type'   => 'field_group',
				'fields' => array(
					array(
						'title'    => __( 'Allow Post type`s', 'acjpd-text-to-speech-speechify' ),
						'id'       => 'allowed_post_types',
						'desc'     => __( 'Select the list of post type to be allowed for the Speechify', 'acjpd-text-to-speech-speechify' ),
						'type'     => 'datalist',
						'multiple' => true,
						'place'    => array(
							'page' => $this->page_id,
							'tab'  => 'api-settings',
						),
						'options'  => $post_type_list,
					),
				),
			),
		);

		return array_merge( $options, $api_settings_options, $speech_settings_options, $wp_settings_options );
	}
}

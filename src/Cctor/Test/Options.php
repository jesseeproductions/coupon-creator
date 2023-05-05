<?php
/**
 * Handles the Options page in the admin.
 *
 * @since   0.1.0
 *
 * @package Cctor\Coupon\Test;
 */

namespace Cctor\Coupon\Test;

use Cctor\Coupon\Test\Template_Modifications as Test_Template_Modifications;

/**
 * Class Options
 *
 * @since   0.1.0
 *
 * @package Cctor\Coupon\Test;
 */
class Options {

	/**
	 * The prefix, in the context of pngx options, of each setting for these options.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	public static $option_prefix = 'cctor_test_';

	/**
	 * The internal id of the API integration.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	public static $api_id = 'test';

	/**
	 * Options constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param Api                               $api                    An instance of the API handler.
	 * @param Test_Template_Modifications $template_modifications An instance of the Template_Modifications handler.
	 * @param Url                               $url                    An instance of the URL handler.
	 */
	public function __construct( Api $api, Test_Template_Modifications $template_modifications, Url $url ) {
		$this->api                    = $api;
		$this->template_modifications = $template_modifications;
		$this->url                    = $url;
	}

	/**
	 * Filter Options Tabs Headings.
	 *
	 * @since 0.1.0
	 *
	 * @param array<string> $sections The current sections.
	 */
	public function filter_sections( $sections ) {
		$new_sections['test'] = __( 'Test', 'coupon-test' );

		// Insert the link before the specified key.
		$spliced_fields = array_splice( $sections, array_search( 'llm', array_keys( $sections ) ) );

		$sections = array_merge( $sections, $new_sections, $spliced_fields );

		return $sections;
	}

	/**
	 * Get the API fields to show on the Options page.
	 *
	 * @since 0.1.0
	 *
	 * @return array<string,array> The fields, as updated by the settings.
	 */
	public function get_fields() {
		$post_types = [
		    [
		        "text" => "Posts",
		        "id" => "post",
		        "selected" => true
		    ],
		    [
		        "text" => "Pages",
		        "id" => "page",
		        "selected" => true
		    ],
		    [
		        "text" => "Products",
		        "id" => "product",
		        "selected" => false
		    ],
		    [
		        "text" => "Coupons",
		        "id" => "cctor_coupon",
		        "selected" => false
		    ]
		];

		$profiles = [
		    [
		        "text" => "Mark 004",
		        "id" => "test_644baa847d4287.36053418",
		        "sort" => "Mark 004",
		        "primary" => "test-profile-primary-value"
		    ],
		    [
		        "text" => "John Doe",
		        "id" => "test_644baa847d4287.36053419",
		        "sort" => "John Doe",
		        "primary" => "test-profile-primary-value"
		    ],
		    [
		        "text" => "Jane Smith",
		        "id" => "test_644baa847d4287.36053420",
		        "sort" => "Jane Smith",
		        "primary" => "test-profile-primary-value"
		    ],
		    [
		        "text" => "Bob Johnson",
		        "id" => "test_644baa847d4287.36053421",
		        "sort" => "Bob Johnson",
		        "primary" => "test-profile-primary-value"
		    ]
		];

		$integration_fields = [
			static::$option_prefix . 'test_help'         => [
				'section' => 'test',
				'type'    => 'help'
			],
			static::$option_prefix . 'header'    => [
				'section' => 'test',
				'title'   => '',
				'desc'    => '',
				'type'    => 'html',
				'html'    => $this->get_intro_text(),
			],
			static::$option_prefix . 'post_types'              => [
				'section'      => 'test',
				'label'        => __( 'Embedding enabled post types', 'coupon-test' ),
				'desc'         => __( 'Select the post types to enable test.', 'coupon-test' ),
				'type'         => 'wooselect',
				'value'        => [ 'post', 'page' ],
				'options'      => $post_types,
				'attrs'        => [
					'data-placeholder'        => _x( 'Select post types to enable test.', 'The placeholder for a dropdown.', 'coupon-test' ),
					'data-prevent-clear'      => true,
					'data-force-search'       => true,
					'data-dropdown-css-width' => '0',
					'style'                   => 'width: 100%;',
					'multiple'                => true,
				],
				'tab'          => 'content',
				'fieldset_wrap' => [ 'cctor-coupon-test-content-select-field' ],
			],
			static::$option_prefix . 'authorize' => [
				'section' => 'test',
				'title'   => '',
				'desc'    => '',
				'type'    => 'html',
				'html'    => $this->get_authorize_fields(),
			],
			static::$option_prefix . 'default_profile' => [
				'section'      => 'test',
				'id'           => static::$option_prefix . 'default_profile',
				'title'        => '',
				'label'        => __( 'Default Test Access Profile', 'coupon-test' ),
				'desc'         => '',
				'value'        => '',
				'std'          => '',
				'type'         => 'wooselect',
				'options'      => $profiles,
				'attrs'        => [
					'data-placeholder'        => _x( 'Select default Test Access Profile', 'The placeholder for a dropdown.', 'coupon-test' ),
					'data-prevent-clear'      => true,
					'data-force-search'       => true,
					'data-dropdown-css-width' => '0',
					'style'                   => 'width: 100%;',
				],
				'fieldset_wrap' => [ 'cctor-coupon-test-content-select-field', 'pngx-engine-default-profile-select-field' ],
			],
		];

		/**
		 * Filters the options shown to the user for test.
		 *
		 * @since 0.1.0
		 *
		 * @param array<string,array> A map of the fields for the options page.
		 * @param Options $options An Options instance.
		 */
		$integration_fields = apply_filters( 'cctor_coupon_test_options_fields', $integration_fields, $this );

		return $integration_fields;
	}

	/**
	 * Adds the Integration fields to a specific section.
	 *
	 * @since 0.1.0
	 *
	 * @param array<string,array> $fields The current fields.
	 *
	 * @return array<string,array> The fields, as updated by the settings.
	 */
	public function add_fields( array $fields ) {
		$api_fields = $this->get_fields();

		// Insert the link before the specified key.
		$spliced_fields = array_splice( $fields, array_search( $this->get_integrations_fields_key(), array_keys( $fields ) ) );

		$fields = array_merge( $fields, $api_fields, $spliced_fields );

		return $fields;
	}

	/**
	 * Get the key to place the integration fields.
	 *
	 * @since 0.1.0
	 *
	 * @return string The key to place the API integration fields.
	 */
	protected function get_integrations_fields_key() {
		/**
		 * Filters the array key to place the API integration settings.
		 *
		 * @since 0.1.0
		 *
		 * @param string The default array key to place the API integration fields.
		 * @param Options $this This Settings instance.
		 */
		return apply_filters( 'cctor_coupon_test_options_field_placement_key', 'display', $this );
	}

	/**
	 * Provides the introductory text to the set up and configuration of the integration.
	 *
	 * @since 0.1.0
	 *
	 * @return string The introductory text to the the set up and configuration of the API integration.
	 */
	protected function get_intro_text() {
		return $this->template_modifications->get_intro_text();
	}

	/**
	 * Get the API authorization fields.
	 *
	 * @since 0.1.0
	 *
	 * @return string The HTML fields.
	 */
	protected function get_authorize_fields() {
		return $this->template_modifications->get_profile_authorize_fields( $this->api, $this->url );
	}
}


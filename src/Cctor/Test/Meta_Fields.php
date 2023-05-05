<?php
/**
 * Handles the Meta Fields for Test.
 *
 * @since   0.1.0
 *
 * @package Cctor\Coupon\Test;
 */

namespace Cctor\Coupon\Test;

/**
 * Class Meta Fields
 *
 * @since   0.1.0
 *
 * @package Cctor\Coupon\Test;
 */
class Meta_Fields {

	/**
	 * The fields prefix.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	public $fields_prefix = 'cctor_test_';

	/**
	 * Get Fields
	 *
	 * @since 0.1.0
	 *
	 * @param array<string|mixed> $fields The fields.
	 *
	 * @return array<string|mixed> $fields The fields.
	 */
	public function get_fields( array $fields = [] ) {
		$post_id = get_the_ID();
		$post_types = [
		    [
		        "text" => "Posts",
		        "id" => "post",
		        "selected" => true
		    ],
		    [
		        "text" => "Pages",
		        "id" => "page",
		        "selected" => false
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

		$fields[ $this->fields_prefix . 'content_source' ] = [
			'class'       => 'large',
			'label'       => __( 'Content Source', 'coupon-test' ),
			'desc'        => '',
			'id'          => $this->fields_prefix . 'content_source',
			'value'       => 'post',
			'type'        => 'wooselect',
			'options'     => $post_types,
			'attrs'       => [
				'data-placeholder'        => _x( 'Select a content source.', 'The placeholder for a dropdown.', 'coupon-test' ),
				'data-prevent-clear'      => true,
				'data-force-search'       => true,
				'data-dropdown-css-width' => '0',
				'style'                   => 'width: 100%;',
			],
			'section'      => 'coupon_creator_meta_box',
			'tab'          => 'test',
			'fieldset_wrap' => [ 'cctor-coupon-test-content-select-field' ],
			'priority'     => 0.01,
		];

		$fields[ $this->fields_prefix . 'test_actions' ] = [
			'label'   => '',
			'desc'    => '',
			'id'      => $this->fields_prefix . 'test_actions',
			'class'   => 'cctor-coupon-test-field',
			'type'    => 'test_actions',
			'section' => 'coupon_creator_meta_box',
			'tab'     => 'test',
			'priority' => 0.01,
		];

/*		$fields[ $this->fields_prefix . 'tokens' ] = [
			'label'   => __( 'Tokens', 'coupon-test' ),
			'desc'    => '',
			'id'      => $this->fields_prefix . 'tokens',
			'data'    => [],
			'value'    => '',
			'class'   => 'cctor-coupon-test-field',
			'type'    => 'read-only',
			'section' => 'coupon_creator_meta_box',
			'tab'     => 'test',
			'priority' => 0.01,
		];*/

/*		$fields[ $this->fields_prefix . 'test_profile' ] = [
			'class'         => 'large',
			'label'         => __( 'Test Profile', 'coupon-test' ),
			'desc'          => '',
			'id'            => $this->fields_prefix . 'test_profile',
			'value'         => '',
			'type'          => 'wooselect',
			'options'       => [],
			'attrs'         => [
				'data-placeholder'        => _x( 'Select test profile.', 'The placeholder for a dropdown.', 'coupon-test' ),
				'data-prevent-clear'      => true,
				'data-force-search'       => true,
				'data-dropdown-css-width' => '0',
				'style'                   => 'width: 100%;',
			],
			'section'       => 'coupon_creator_meta_box',
			'tab'           => 'test',
			'fieldset_wrap' => [ 'cctor-coupon-test-content-select-field' ],
			'priority'      => 0.01,
		];

		$fields[ $this->fields_prefix . 'profile_id' ] = [
			'class'         => 'large',
			'label'         => __( 'Profile.', 'coupon-test' ),
			'desc'          => '',
			'id'            => $this->fields_prefix . 'profile_id',
			'value'         => '',
			'type'          => 'wooselect',
			'options'       => [],
			'attrs'         => [
				'data-placeholder'        => _x( 'Select a profile.', 'The placeholder for a dropdown.', 'coupon-test' ),
				'data-prevent-clear'      => true,
				'data-force-search'       => true,
				'data-dropdown-css-width' => '0',
				'style'                   => 'width: 100%;',
			],
			'section'       => 'coupon_creator_meta_box',
			'tab'           => 'test',
			'fieldset_wrap' => [ 'cctor-coupon-test-content-select-field' ],
			'priority'      => 0.01,
		];*/

		return $fields;
	}
}


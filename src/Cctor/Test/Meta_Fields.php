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
		$post_id    = get_the_ID();
		$local_time  = current_datetime();

		$post_types = [
			[
				"text"     => "Posts",
				"id"       => "post",
				"selected" => true
			],
			[
				"text"     => "Pages",
				"id"       => "page",
				"selected" => false
			],
			[
				"text"     => "Products",
				"id"       => "product",
				"selected" => false
			],
			[
				"text"     => "Coupons",
				"id"       => "cctor_coupon",
				"selected" => false
			]
		];

		$fields[ $this->fields_prefix . 'content_source' ] = [
			'label'         => __( 'Content Source', 'coupon-test' ),
			'tooltip'       => __( 'Tooltip', 'test' ),
			'id'            => $this->fields_prefix . 'content_source',
			'version'       => 'v2',
			'value'         => 'post',
			'type'          => 'wooselect',
			'options'       => $post_types,
			'attrs'         => [
				'data-placeholder'        => _x( 'Select a content source.', 'The placeholder for a dropdown.', 'coupon-test' ),
				'data-prevent-clear'      => true,
				'data-force-search'       => false,
				'data-dropdown-css-width' => '0',
				'style'                   => 'width: 100%;',
			],
			'section'       => 'coupon_creator_meta_box',
			'tab'           => 'test',
			'fieldset_wrap' => [ 'cctor-coupon-test-content-select-field' ],
			'priority'      => 0.01,
		];

		$fields[ $this->fields_prefix . 'multiple' ] = [
			'label'         => __( 'multiple Source', 'coupon-test' ),
			'tooltip'       => __( 'Tooltip', 'test' ),
			'id'            => $this->fields_prefix . 'multiple',
			'version'       => 'v2',
			'value'         => 'post',
			'type'          => 'wooselect',
			'options'       => $post_types,
			'attrs'         => [
				'data-placeholder'        => _x( 'Select a content source.', 'The placeholder for a dropdown.', 'coupon-test' ),
				'data-prevent-clear'      => true,
				'data-force-search'       => true,
				'data-dropdown-css-width' => '0',
				'style'                   => 'width: 100%;',
				'multiple'                => true,
			],
			'section'       => 'coupon_creator_meta_box',
			'tab'           => 'test',
			'fieldset_wrap' => [ 'cctor-coupon-test-content-select-field' ],
			'priority'      => 0.01,
		];

		$fields[ $this->fields_prefix . 'test_actions' ] = [
			'label'    => '',
			'desc'     => '',
			'id'       => $this->fields_prefix . 'test_actions',
			'version'  => 'v2',
			'class'    => 'cctor-coupon-test-field',
			'type'     => 'test_actions',
			'section'  => 'coupon_creator_meta_box',
			'tab'      => 'test',
			'priority' => 0.01,
		];

		$fields[ $this->fields_prefix . 'textinput' ] = [
			'label'    => __( 'Text Label', 'test' ),
			'tooltip'  => __( 'Tooltip', 'test' ),
			'id'       => $this->fields_prefix . 'textinput',
			'version'  => 'v2',
			'type'     => 'text',
			'section'  => 'coupon_creator_meta_box',
			'tab'      => 'test',
			'priority' => 0.01,
		];

		$fields[ $this->fields_prefix . 'textarea' ] = [
			'label'    => __( 'Textarea Label', 'test' ),
			'tooltip'  => __( 'Tooltip', 'test' ),
			'id'       => $this->fields_prefix . 'textarea',
			'version'  => 'v2',
			'type'     => 'textarea',
			'section'  => 'coupon_creator_meta_box',
			'tab'      => 'test',
			'priority' => 0.01,
		];

		$fields[ $this->fields_prefix . 'number' ] = [
			'label'    => __( 'Number Label', 'test' ),
			'tooltip'  => __( 'Tooltip', 'test' ),
			'id'       => $this->fields_prefix . 'number',
			'version'  => 'v2',
			'type'     => 'number',
			'section'  => 'coupon_creator_meta_box',
			'tab'      => 'test',
			'priority' => 0.01,
		];

		$fields[ $this->fields_prefix . 'switchinput' ] = [
			'label'    => __( 'Switch Label', 'test' ),
			'switch_description'  => __( 'Turns off and On Something', 'test' ),
			'tooltip'  => __( 'Tooltip', 'test' ),
			'id'       => $this->fields_prefix . 'switchinput',
			'version'  => 'v2',
			'type'     => 'switch',
			'section'  => 'coupon_creator_meta_box',
			'tab'      => 'test',
			'priority' => 0.01,
		];

		$fields[ $this->fields_prefix . 'icon' ] = [
			'label'    => __( 'Icon', 'test_actions' ),
			'description'          => _x( 'Icon Helper Text', '', 'test_actions' ),
			'id'       => $this->fields_prefix . 'icon',
			'version'  => 'v2',
			'type'     => 'image',
			'image_msg' => 'Icon',
			'attrs' => [
				'data-toggle-upload_title' => _x( 'Upload Icon', 'Label of the PDF source for embeddings Media Manager upload button.', 'test_actions' ),
				'data-toggle-button_text'  => _x( 'Use Icon', 'Label of the PDF source for embeddings Media Manager use button.', 'test_actions' ),
				'data-upload-type'  => 'image',
			],
			'section'  => 'coupon_creator_meta_box',
			'tab'      => 'test',
			'priority' => 0.01,
		];

		$fields[ $this->fields_prefix . 'pdf' ] = [
			'label'          => _x( 'PDF', '', 'test_actions' ),
			'description'          => _x( 'PDF Helper Text', '', 'test_actions' ),
			'id'             => $this->fields_prefix . 'pdf',
			'version'        => 'v2',
			'type'           => 'file',
			'attrs' => [
				'data-toggle-upload_title' => _x( 'Upload PDF', '', 'test_actions' ),
				'data-toggle-button_text'  => _x( 'Use PDF', '', 'test_actions' ),
				'data-upload-type'  => 'application/pdf',
			],
			'section'  => 'coupon_creator_meta_box',
			'tab'      => 'test',
			'priority' => 0.01,
		];

/*		$fields[ $this->fields_prefix . 'date' ] = [
			'label'    => __( 'Date', 'test_actions' ),
			'id'       => $this->fields_prefix . 'date',
			'version'  => 'v2',
			'tooltip'  => _x( 'Choose Date', '', 'test_actions' ),
			'value'    => '',
			'attrs'    => [
				'data-date-format' => 'Y-m-d H:i:S',
				'data-alt-format' => 'F j, Y H:i',
				'data-default-date' => $local_time->format( 'Y-m-d H:i:s' ),
			],
			'type'     => 'date',
			'section'  => 'coupon_creator_meta_box',
			'tab'      => 'test',
			'priority' => 25.00,
		];*/

		return $fields;
	}
}


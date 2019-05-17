<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
if ( class_exists( 'Cctor__Coupon__Meta__Fields' ) ) {
	return;
}


class Cctor__Coupon__Meta__Fields {

	// fields id prefix
	public $fields_prefix = 'cctor_';


	/**
	 * Cctor__Coupon__Meta__Fields constructor
	 */
	public function __construct() {

		add_filter( 'cctor_filter_meta_template_fields', array( $this, 'get_template_fields' ), 5, 1 );

		add_filter( 'pngx_meta_fields', array( $this, 'get_fields' ), 5 );

		add_filter( 'pngx_meta_template_fields', array( $this, 'get_template_fields_for_filter' ), 5 );
	}


	public function get_template_fields_for_filter( $fields = array() ) {

		/**
		 * Filter the meta fields from Coupon Creator for custom templates
		 *
		 *
		 * @param array $fields an array of fields to display in meta tabs.
		 *
		 */
		return pngx( 'cctor.meta.order' )->get_ordered_template_fields( $fields );

	}

	/**
	 * Get Fields ID Prefix
	 *
	 * @return string
	 */
	public function get_fields_prefix() {
		return $this->fields_prefix;
	}

	/*
	* Get Fields
	*
	*/
	public function get_fields() {

		// fields prefix
		$prefix = $this->get_fields_prefix();

		// Content
		$fields[ $prefix . 'content_help' ] = array(
			'id'      => $prefix . 'content_help',
			'type'    => 'help',
			'section' => 'coupon_creator_meta_box',
			'tab'     => 'content',
			'priority' => 5.00,
		);

		$template_options = array(
			'default' => __( 'Default', 'coupon-creator' ),
			'image'   => __( 'Image', 'coupon-creator' ),
		);
		if ( class_exists( 'Cctor__Coupon__Addons__Main' ) && 1 == cctor_options( 'cctor_advanced_templates', true, 1 ) ) {
			$template_options = array(
				'default'     => __( 'Default', 'coupon-creator' ),
				'image'       => __( 'Image', 'coupon-creator' ),
				'modern'      => __( 'Modern', 'coupon-creator' ),
				'two-column'  => __( 'Two Columns', 'coupon-creator' ),
				'lower-third' => __( 'Lower Third', 'coupon-creator' ),
				'highlight'   => __( 'Highlight', 'coupon-creator' ),
			);
		}

		// Coupon Type
		$fields[ $prefix . 'coupon_type' ] = array(
			'label'   => __( 'Coupon Type', 'coupon-creator-pro' ),
			'desc'    => __( 'Choose a coupon type to use.', 'coupon-creator-pro' ),
			'id'      => $prefix . 'coupon_type',
			'data'    => array(
				'ajax_field'    => '.template-wrap-cctor_coupon_type',
				'ajax_field_id' => 'cctor_coupon_type',
				'ajax_action'   => 'pngx_templates',
			),
			'value'    => cctor_options( 'cctor_default_template' ),
			'class'   => 'pngx-template-chooser',
			'type'    => 'select',
			'choices' => $template_options,
			'section' => 'coupon_creator_meta_box',
			'tab'     => 'content',
			'priority' => 5.01,
		);

		$fields[ $prefix . 'template_chooser' ] = array(
			'label'     => '',
			'desc'      => '',
			'id'        => $prefix . 'template_chooser',
			'type'      => 'template_chooser',
			'alert'     => '',
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'content',
			'wrapclass' => '',
			'priority' => 5.02,
		);

		$fields[ $prefix . 'start_content_template' ] = array(
			'id'        => $prefix . 'start_content_template',
			'type'      => 'template_start',
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'content',
			'wrapclass' => 'cctor_coupon_type',
			'priority' => 29.00,
		);

		/**
		 * Filter the meta fields from Coupon Creator for custom templates
		 *
		 *
		 * @param array $fields an array of fields to display in meta tabs.
		 *
		 */
		$fields = pngx( 'cctor.meta.order' )->get_ordered_template_fields( $fields );

		$fields[ $prefix . 'end_content_template' ] = array(
			'id'        => $prefix . 'end_content_template',
			'type'      => 'template_end',
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'content',
			'wrapclass' => 'content_templates',
			'priority' => 80.00,
		);
		if ( ! defined( 'CCTOR_HIDE_UPGRADE' ) || ! CCTOR_HIDE_UPGRADE ) {
			$fields[ $prefix . 'pro_content_features_heading' ] = array(
				'id'      => $prefix . 'pro_content_features_heading',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'content',
				'title'   => '',
				'desc'    => __( 'Pro Content Features', 'coupon-creator' ),
				'type'    => 'pro_heading',
				'priority' => 80.01,
			);
			$fields[ $prefix . 'pro_content_features' ]         = array(
				'id'      => $prefix . 'pro_content_features',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'content',
				'title'   => '',
				'desc'    => '',
				'type'    => 'list',
				'std'     => '',
				'choices' => array(
					'0' => __( 'Use the Visual editor to easily style the coupons term\'s' ),
					'1' => __( 'Give visitors a reason to click on the coupon by only showing the deal in print view or by using the view shortcodes to selectively display content on either view' ),
					'2' => __( 'Insert columns and rows into the content editor for more unique looking coupons' ),
				),
				'priority' => 80.02,
			);
			$fields[ $prefix . 'pro_feature_content_link' ]     = array(
				'id'      => $prefix . 'pro_feature_content_link',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'content',
				'title'   => '', // Not used for headings.
				'desc'    => __( 'Pro Link', 'coupon-creator' ),
				'type'    => 'pro_link',
				'priority' => 80.03,
			);
		}

		//Style Tab
		$fields[ $prefix . 'style_help' ] = array(
			'id'      => $prefix . 'style_help',
			'type'    => 'help',
			'section' => 'coupon_creator_meta_box',
			'tab'     => 'style',
			'priority' => 10.00,
		);

		//Inside Border
		$fields[ $prefix . 'heading_inside_color' ] = array(
			'id'        => $prefix . 'heading_inside_color',
			'title'     => '',
			'desc'      => __( 'Inner Border', 'coupon-creator' ),
			'type'      => 'heading',
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'style',
			'wrapclass' => 'image-coupon-disable',
			'priority' => 10.06,
		);
		$fields[ $prefix . 'bordercolor' ]          = array(
			'label'     => __( 'Inside Border Color', 'coupon-creator' ),
			'desc'      => __( 'Choose inside border color', 'coupon-creator' ),
			'id'        => $prefix . 'bordercolor',
			'type'      => 'color', // color
			'value'     => cctor_options( 'cctor_border_color' ),
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'style',
			'wrapclass' => 'image-coupon-disable',
			'priority' => 10.07,
		);

		//Discount
		if ( ! defined( 'CCTOR_HIDE_UPGRADE' ) || ! CCTOR_HIDE_UPGRADE ) {
			$fields[ $prefix . 'pro_content_style_heading' ] = array(
				'id'      => $prefix . 'pro_content_style_heading',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'style',
				'title'   => '',
				'desc'    => __( 'Pro Content Features', 'coupon-creator' ),
				'type'    => 'pro_heading',
				'priority' => 12.01,
			);
			$fields[ $prefix . 'pro_content_style' ]         = array(
				'id'      => $prefix . 'pro_content_style',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'style',
				'title'   => '',
				'desc'    => '',
				'type'    => 'list',
				'std'     => '',
				'choices' => array(
					'0' => __( 'Use 4 style sections in Pro to create a unique coupon or a standard brand to attract customers' ),
					'2' => __( 'Choose between 5 different border styles in Pro, including Saw Tooth, Stitched, Dotted, Coupon, and None.<br> <img class="cctor-pro-img" alt="Coupon Creator Pro Border Examples" src="' . esc_url( pngx( 'cctor' )->resource_url ) . 'images/cctor-border-examples.gif"/>' ),
				),
				'priority' => 12.02,
			);
			$fields[ $prefix . 'pro_feature_style_link' ]    = array(
				'id'      => $prefix . 'pro_feature_style_link',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'style',
				'title'   => '', // Not used for headings.
				'desc'    => __( 'Pro Link', 'coupon-creator' ),
				'type'    => 'pro_link',
				'priority' => 12.03,
			);
		}

		//Expiration
		$fields[ $prefix . 'expiration_help' ]    = array(
			'id'      => $prefix . 'expiration_help',
			'type'    => 'help',
			'section' => 'coupon_creator_meta_box',
			'tab'     => 'expiration',
			'priority' => 15.00,
		);
		$fields[ $prefix . 'heading_expiration' ] = array(
			'id'      => $prefix . 'heading_expiration',
			'title'   => '',
			'desc'    => __( 'Expiration', 'coupon-creator' ),
			'type'    => 'heading',
			'section' => 'coupon_creator_meta_box',
			'tab'     => 'expiration',
			'priority' => 15.01,
		);

		$expiration_options = array(
			'1' => __( 'Ignore Expiration', 'coupon-creator' ),
			'2' => __( 'Expiration Date', 'coupon-creator' )
		);
		if ( class_exists( 'Coupon_Creator_Pro_Plugin' ) ) {
			$expiration_options = array(
				'1' => __( 'Ignore Expiration', 'coupon-creator' ),
				'2' => __( 'Expiration Date', 'coupon-creator' ),
				'3' => __( 'Recurring Expiration', 'coupon-creator' ),
				'4' => __( 'Expires in X Days', 'coupon-creator' ),
				'5' => __( 'Range Expiration', 'coupon-creator' ),
			);
		}

		$fields[ $prefix . 'expiration_option' ] = array(
			'label'    => __( 'Expiration Option', 'coupon-creator' ),
			'desc'     => __( 'Choose the expiration method for this coupon', 'coupon-creator' ),
			'id'       => $prefix . 'expiration_option',
			'value'    => cctor_options( 'cctor_expiration_option' ),
			'type'     => 'select',
			'choices'  => $expiration_options,
			'section'  => 'coupon_creator_meta_box',
			'tab'      => 'expiration',
			'bulkedit' => 'cctor_pro_expiration',
			'toggle'   => array(
				'field'    => 'select',
				'priority' => 10,
				'group'    => '.expiration-field',
				'show'     => '.expiration-'
			),
			'priority' => 15.02,
		);

		$fields[ $prefix . 'expiration_msg_1' ] = array(
			'desc'      => __( 'This coupon will not expire and always show on the front end of your site.', 'coupon-creator' ),
			'id'        => $prefix . 'expiration_msg_1',
			'type'      => 'message',
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'expiration',
			'wrapclass' => 'expiration-field expiration-1',
			'bulkedit'  => '',
			'priority' => 15.03,
		);
		$fields[ $prefix . 'expiration_msg_2' ] = array(
			'desc'      => __( 'This coupon will no longer show the day after the expiration date.', 'coupon-creator' ),
			'id'        => $prefix . 'expiration_msg_2',
			'type'      => 'message',
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'expiration',
			'wrapclass' => 'expiration-field expiration-2',
			'bulkedit'  => '',
			'priority' => 15.04,
		);
		$fields[ $prefix . 'expiration_msg_3' ] = array(
			'desc'      => __( 'This coupon\'s expiration will change based on the choosen pattern.', 'coupon-creator' ),
			'id'        => $prefix . 'expiration_msg_3',
			'type'      => 'message',
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'expiration',
			'wrapclass' => 'expiration-field expiration-3',
			'bulkedit'  => '',
			'priority' => 15.05,
		);
		$fields[ $prefix . 'expiration_msg_4' ] = array(
			'desc'      => __( 'This coupon will expire X days from when it is printed.', 'coupon-creator' ),
			'id'        => $prefix . 'expiration_msg_4',
			'type'      => 'message',
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'expiration',
			'wrapclass' => 'expiration-field expiration-4',
			'bulkedit'  => '',
			'priority' => 15.06,
		);
		$fields[ $prefix . 'expiration_msg_5' ] = array(
			'desc'      => __( 'This coupon will show a range of dates that it is valid.', 'coupon-creator' ),
			'id'        => $prefix . 'expiration_msg_5',
			'type'      => 'message',
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'expiration',
			'wrapclass' => 'expiration-field expiration-5',
			'bulkedit'  => '',
			'priority' => 15.07,
		);


		$fields[ $prefix . 'date_format' ] = array(
			'label'     => __( 'Date Format', 'coupon-creator' ),
			'desc'      => __( 'Choose the date format', 'coupon-creator' ),
			'id'        => $prefix . 'date_format',
			'value'     => cctor_options( 'cctor_default_date_format' ),
			'type'      => 'select',
			'choices'   => array(
				'0' => __( 'Month First - MM/DD/YYYY', 'coupon-creator' ),
				'1' => __( 'Day First - DD/MM/YYYY', 'coupon-creator' )
			),
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'expiration',
			'wrapclass' => 'expiration-field expiration-2 expiration-3 expiration-4 expiration-5',
			'bulkedit'  => 'cctor_pro_expiration',
			'priority' => 15.08,
		);

		$date_format = get_metadata( get_the_id(), $prefix . 'date_format', true );

		$fields[ $prefix . 'expiration' ] = array(
			'label'     => __( 'Expiration Date', 'coupon-creator' ),
			'id'        => $prefix . 'expiration',
			'desc'      => __( 'Choose a date this coupon will expire.', 'coupon-creator' ),
			'type'      => 'date',
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'expiration',
			'condition' => 'show_current_date',
			'format'    => ! empty( $date_format ) ? $date_format : cctor_options( 'cctor_default_date_format' ),
			'wrapclass' => 'expiration-field expiration-2 expiration-3 expiration-5',
			'bulkedit'  => 'cctor_pro_expiration',
			'priority' => 16.01,
		);

		$fields[ $prefix . 'ignore_expiration' ] = array(
			'label'     => __( 'Ignore Expiration Date', 'coupon-creator' ),
			'desc'      => __( 'Check this to ignore the expiration date', 'coupon-creator' ),
			'id'        => $prefix . 'ignore_expiration',
			'type'      => 'checkbox',
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'expiration',
			'wrapclass' => 'expiration-field',
			'priority' => 16.03,
		);
		if ( ! defined( 'CCTOR_HIDE_UPGRADE' ) || ! CCTOR_HIDE_UPGRADE ) {
			$fields[ $prefix . 'pro_content_expiration_heading' ] = array(
				'id'      => $prefix . 'pro_content_expiration_heading',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'expiration',
				'title'   => '',
				'desc'    => __( 'Pro Content Features', 'coupon-creator' ),
				'type'    => 'pro_heading',
				'priority' => 18.00,
			);
			$fields[ $prefix . 'pro_expiration_style' ]           = array(
				'id'      => $prefix . 'pro_expiration_style',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'expiration',
				'title'   => '',
				'desc'    => '',
				'type'    => 'list',
				'std'     => '',
				'choices' => array(
					'0' => __( 'Utilize five(5) different expiration options to help promote sales at your business' ),
					'1' => __( 'Save time by editing multiple coupons expiration and counter fields using Pro\'s Bulk Edits' ),
					'2' => __( 'Track coupon print views by using the unlimited counter or set a limit to restrict a coupon to a certain amount of customers' ),
				),
				'priority' => 18.01,
			);
			$fields[ $prefix . 'pro_feature_expiration_link' ]    = array(
				'id'      => $prefix . 'pro_feature_expiration_link',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'expiration',
				'title'   => '', // Not used for headings.
				'desc'    => __( 'Pro Link', 'coupon-creator' ),
				'type'    => 'pro_link',
				'priority' => 18.02,
			);
		}

		//Image Coupon
		$fields[ $prefix . 'img_border_message' ] = array(
			'id'      => $prefix . 'img_border_message',
			'title'   => '',
			'desc'    => __( 'The Image Coupon is now part of the template system. Go to the Content tab and choose the Image template to create one.', 'coupon-creator' ),
			'type'    => 'message',
			'section' => 'coupon_creator_meta_box',
			'tab'     => 'image_coupon',
			'priority' => 18.09,
		);

		//links
		$fields[ $prefix . 'link_coupon_help' ] = array(
			'id'      => $prefix . 'link_coupon_help',
			'type'    => 'help',
			'section' => 'coupon_creator_meta_box',
			'tab'     => 'links',
			'priority' => 20.00,
		);

		//Links
		if ( ! defined( 'CCTOR_HIDE_UPGRADE' ) || ! CCTOR_HIDE_UPGRADE ) {
			$fields[ $prefix . 'pro_content_links_heading' ] = array(
				'id'      => $prefix . 'pro_content_links_heading',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'links',
				'title'   => '',
				'desc'    => __( 'Pro Content Features', 'coupon-creator' ),
				'type'    => 'pro_heading',
				'priority' => 21.00,
			);
			$fields[ $prefix . 'pro_links_style' ]           = array(
				'id'      => $prefix . 'pro_links_style',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'links',
				'title'   => '',
				'desc'    => '',
				'type'    => 'list',
				'std'     => '',
				'choices' => array(
					'0' => __( 'Use the custom links and text to promote your affiliate links' ),
					'1' => __( 'Enable your visitors to print coupons while staying on the same page using the Pop Up Coupon' ),
				),
				'priority' => 21.01,
			);
			$fields[ $prefix . 'pro_feature_links_link' ]    = array(
				'id'      => $prefix . 'pro_feature_links_link',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'links',
				'title'   => '', // Not used for headings.
				'desc'    => __( 'Pro Link', 'coupon-creator' ),
				'type'    => 'pro_link',
				'priority' => 21.02,
			);
		}

		//Help
		$fields[ $prefix . 'all_help' ] = array(
			'id'      => $prefix . 'all_help',
			'type'    => 'help',
			'section' => 'coupon_creator_meta_box',
			'tab'     => 'help',
			'priority' => 25.00,
		);


		/**
		 * Get All Meta Fields Ordered by Priority
		 *
		 * @sicne TBD
		 *
		 * @param array $fields an array of fields to display in meta tabs.
		 */
		 $fields = pngx( 'cctor.meta.order' )->get_ordered_meta_fields( $fields );


		return $fields;
	}

	/*
	* Get Template Fields
	*
	*/
	public function get_template_fields( $fields ) {

		//fields prefix
		$prefix = $this->get_fields_prefix();


		/**
		 * Start Default Template
		 */
		$fields[ $prefix . 'amount' ] = array(
			'label'     => __( 'Deal', 'coupon-creator' ),
			'desc'      => __( 'Enter coupon deal - 30% OFF! or Buy One Get One Free, etc...', 'coupon-creator' ),
			'id'        => $prefix . 'amount',
			'type'      => 'text',
			'alert'     => '',
			'sanitize'  => 'titles',
			'section'   => 'coupon_creator_meta_box',
			'styles'    => array(
				'font-color'       => $prefix . 'colorheader',
				'background-color' => $prefix . 'colordiscount',
			),
			'template'  => array( 'default' ),
			'tab'       => 'content',
			'wrapclass' => 'image-coupon-disable deal-display deal-display-both deal-display-hook deal-display-print',
			'display'   => array(
				'type'  => class_exists( 'Cctor__Coupon__Pro__Main' ) ? 'deal' : 'title',
				'class' => 'cctor_deal cctor-deal',
				'wrap'  => 'h3',
			),
			'priority' => 30.01,
		);
		$fields[ $prefix . 'colorheader' ]   = array(
			'alpha'        => '',
			'label'        => '',
			'inside_label' => __( 'Font Color', 'coupon-creator' ),
			'desc'         => '',
			'id'           => $prefix . 'colorheader',
			'type'         => 'color',
			'section'      => 'coupon_creator_meta_box',
			'value'        => cctor_options( 'cctor_discount_text_color' ),
			'std'          => '',
			'priority' => 30.01,
		);

		$fields[ $prefix . 'colordiscount' ] = array(
			'alpha'        => 'true',
			'label'        => '',
			'inside_label' => __( 'Background Color', 'coupon-creator' ),
			'desc'         => '',
			'id'           => $prefix . 'colordiscount',
			'type'         => 'color',
			'section'      => 'coupon_creator_meta_box',
			'value'        => cctor_options( 'cctor_discount_bg_color' ),
			'std'          => '',
			'priority' => 30.01,
		);

		$fields[ $prefix . 'deal_display' ] = array(
			'id'        => $prefix . 'deal_display',
			'type'      => '',
			'section'   => 'coupon_creator_meta_box',
			'template'  => array( 'default' ),
			'tab'       => 'content',
			'wrapclass' => 'image-coupon-disable',
			'priority' => 30.02,
		);

		$fields[ $prefix . 'description' ] = array(
			'label'     => __( 'Terms', 'coupon-creator' ),
			'desc'      => __( 'Enter the terms of the discount', 'coupon-creator' ),
			'id'        => $prefix . 'description',
			'type'      => 'textarea',
			'class'     => 'code',
			'section'   => 'coupon_creator_meta_box',
			'template'  => array( 'default' ),
			'tab'       => 'content',
			'cols'      => 60,
			'rows'      => 4,
			'wrapclass' => 'image-coupon-disable',
			'display'   => array(
				'type'  => 'content',
				'tags'  => 'content_no_link',
				'class' => 'cctor-terms',
			),
			'priority' => 30.03,
		);


		$fields[ $prefix . 'default_expiration' ] = array(
			'id'       => $prefix . 'default_expiration',
			'type'     => '',
			'alert'    => '',
			'section'  => 'coupon_creator_meta_box',
			'template' => array( 'default' ),
			'display'  => array(
				'type'  => 'expiration',
				'class' => 'expiration-date',
			),
			'priority' => 76.00,
		);
		/**
		 * End Default Template
		 */

		/**
		 * Start Image Template
		 */
		$img_toggle = array(
			'field'    => 'input',
			'type'     => 'image',
			'priority' => 5,
			'group'    => '.image-coupon-disable',
			'show'     => '',
			'msg'      => array(
				'style' => __( ' Style Fields are disabled when using an Image Coupon', 'coupon-creator' )
			),
		);

		if ( class_exists( 'Cctor__Coupon__Pro__Main' ) ) {
			$img_toggle = array(
				'field'    => 'input',
				'type'     => 'image',
				'priority' => 6,
				'group'    => '.image-coupon-disable',
				'show'     => '',
				'msg'      => array(
					'style' => __( ' Only outer border styles are available when using an Image Coupon', 'coupon-creator' )
				),
			);
		}

		$fields[ $prefix . 'image' ] = array(
			'label'    => __( 'Image Coupon', 'coupon-creator' ),
			'desc'     => __( 'Upload an image to use as the entire coupon - Current image size is for 390 pixels in width with auto height', 'coupon-creator' ),
			'id'       => $prefix . 'image',
			'type'     => 'image',
			'imagemsg' => 'Image Coupon',
			'section'  => 'coupon_creator_meta_box',
			'tab'      => 'content',
			'template' => array( 'image' ),
			'toggle'   => $img_toggle,
			'function' => array(
				'upload_title' => 'Choose Coupon Image',
				'button_text'  => 'Use Image',
			),
			'display'  => array(
				'type'  => 'image_coupon',
				'class' => 'cctor_coupon_image',
			),
			'priority' => 35.01,
		);
		/**
		 * End Image Template
		 */

		// Variety Messages
		$fields[ $prefix . 'var_expiration_msg' ]         = array(
			'desc'    => __( 'The Expiration Date will display based off the selection in the Expiration / Counter Tab of this Coupon.', 'coupon-creator' ),
			'id'      => $prefix . 'expiration',
			'type'    => 'message',
			'section' => 'coupon_creator_meta_box',
			'display' => array(
				'type'  => 'expiration',
				'class' => 'expiration-date',
			),
			'priority' => 78.01,
		);
		$fields[ $prefix . 'var_counter_msg' ]            = array(
			'desc'    => __( 'The Counter will display based off the selection in the Expiration / Counter Tab of this Coupon.', 'coupon-creatorn' ),
			'id'      => $prefix . 'expiration',
			'type'    => 'message',
			'section' => 'coupon_creator_meta_box',
			'display' => array(
				'type'  => 'expiration',
				'class' => 'counter',
			),
			'priority' => 78.01,
		);
		$fields[ $prefix . 'var_expiration_counter_msg' ] = array(
			'desc'    => __( 'The Expiration Date and Counter will display based off the selection in the Expiration / Counter Tab of this Coupon.', 'coupon-creator' ),
			'id'      => $prefix . 'expiration',
			'type'    => 'message',
			'section' => 'coupon_creator_meta_box',
			'display' => array(
				'type'  => 'expiration',
				'class' => 'expiration-counter',
			),
			'priority' => 78.01,
		);

		return $fields;
	}
}
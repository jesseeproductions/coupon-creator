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
	public static $fields_prefix = 'cctor_';


	/**
	 * Cctor__Coupon__Meta__Fields constructor
	 */
	public function __construct() {

		add_filter( 'cctor_filter_meta_template_fields', array( __CLASS__, 'get_template_fields' ), 5, 1 );

		add_filter( 'pngx_meta_fields', array( __CLASS__, 'get_fields' ), 5 );

		add_filter( 'pngx_meta_template_fields', array( __CLASS__, 'get_template_fields_for_filter' ), 5 );
	}


	public static function get_template_fields_for_filter( $fields = array() ) {

		/**
		 * Filter the meta fields from Coupon Creator for custom templates
		 *
		 *
		 * @param array $fields an array of fields to display in meta tabs.
		 *
		 */
		return apply_filters( 'cctor_filter_meta_template_fields', $fields );

	}

	/**
	 * Get Fields ID Prefix
	 *
	 * @return string
	 */
	public static function get_fields_prefix() {
		return self::$fields_prefix;
	}

	/*
	* Get Fields
	*
	*/
	public static function get_fields() {

		// fields prefix
		$prefix = self::get_fields_prefix();

		// Content
		$fields[ $prefix . 'content_help' ] = array(
			'id'      => $prefix . 'content_help',
			'type'    => 'help',
			'section' => 'coupon_creator_meta_box',
			'tab'     => 'content',
		);

		$template_options = array(
			'default' => __( 'Default', 'coupon-creator' ),
			'image'   => __( 'Image', 'coupon-creator' ),
		);
		if ( class_exists( 'Cctor__Coupon__Addons__Main' ) ) {
			$template_options = array(
				'default'     => __( 'Default', 'coupon-creator' ),
				'image'       => __( 'Image', 'coupon-creator' ),
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
		);

		$fields[ $prefix . 'start_content_template' ] = array(
			'id'        => $prefix . 'start_content_template',
			'type'      => 'template_start',
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'content',
			'wrapclass' => 'cctor_coupon_type',
		);

		if ( has_filter( 'cctor_filter_meta_template_fields' ) ) {
			/**
			 * Filter the meta fields from Coupon Creator for custom templates
			 *
			 *
			 * @param array $fields an array of fields to display in meta tabs.
			 *
			 */
			$fields = apply_filters( 'cctor_filter_meta_template_fields', $fields );
		}

		$fields[ $prefix . 'end_content_template' ] = array(
			'id'        => $prefix . 'end_content_template',
			'type'      => 'template_end',
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'content',
			'wrapclass' => 'content_templates',
		);
		if ( ! defined( 'CCTOR_HIDE_UPGRADE' ) || ! CCTOR_HIDE_UPGRADE ) {
			$fields[ $prefix . 'pro_content_features_heading' ] = array(
				'id'      => $prefix . 'pro_content_features_heading',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'content',
				'title'   => '',
				'desc'    => __( 'Pro Content Features', 'coupon-creator' ),
				'type'    => 'pro_heading'
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
				)
			);
			$fields[ $prefix . 'pro_feature_content_link' ]     = array(
				'id'      => $prefix . 'pro_feature_content_link',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'content',
				'title'   => '', // Not used for headings.
				'desc'    => __( 'Pro Link', 'coupon-creator' ),
				'type'    => 'pro_link',
			);
		}

		//Style Tab
		$fields[ $prefix . 'style_help' ] = array(
			'id'      => $prefix . 'style_help',
			'type'    => 'help',
			'section' => 'coupon_creator_meta_box',
			'tab'     => 'style',
		);
		//Outer Border Placeholders
		$fields[ $prefix . 'heading_pro_display' ]  = array(
			'id'      => $prefix . 'heading_pro_display',
			'type'    => '',
			'section' => 'coupon_creator_meta_box',
			'tab'     => 'style',
		);
		$fields[ $prefix . 'coupon_border_themes' ] = array(
			'id'      => $prefix . 'coupon_border_themes',
			'type'    => '',
			'section' => 'coupon_creator_meta_box',
			'tab'     => 'style',
		);
		$fields[ $prefix . 'outer_border_color' ]   = array(
			'id'      => $prefix . 'outer_border_color',
			'type'    => '',
			'section' => 'coupon_creator_meta_box',
			'tab'     => 'style'
		);
		$fields[ $prefix . 'outer_radius' ]         = array(
			'id'      => $prefix . 'outer_radius',
			'type'    => '',
			'section' => 'coupon_creator_meta_box',
			'tab'     => 'style'
		);

		//Inside Border
		$fields[ $prefix . 'heading_inside_color' ] = array(
			'id'        => $prefix . 'heading_inside_color',
			'title'     => '',
			'desc'      => __( 'Inner Border', 'coupon-creator' ),
			'type'      => 'heading',
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'style',
			'wrapclass' => 'image-coupon-disable'
		);
		$fields[ $prefix . 'bordercolor' ]          = array(
			'label'     => __( 'Inside Border Color', 'coupon-creator' ),
			'desc'      => __( 'Choose inside border color', 'coupon-creator' ),
			'id'        => $prefix . 'bordercolor',
			'type'      => 'color', // color
			'value'     => cctor_options( 'cctor_border_color' ),
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'style',
			'wrapclass' => 'image-coupon-disable'
		);
		$fields[ $prefix . 'inside_radius' ]        = array(
			'id'        => $prefix . 'inside_radius',
			'type'      => '',
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'style',
			'wrapclass' => 'image-coupon-disable'
		);

		//Discount
		if ( ! defined( 'CCTOR_HIDE_UPGRADE' ) || ! CCTOR_HIDE_UPGRADE ) {
			$fields[ $prefix . 'pro_content_style_heading' ] = array(
				'id'      => $prefix . 'pro_content_style_heading',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'style',
				'title'   => '',
				'desc'    => __( 'Pro Content Features', 'coupon-creator' ),
				'type'    => 'pro_heading'
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
					'2' => __( 'Choose between 5 different border styles in Pro, including Saw Tooth, Stitched, Dotted, Coupon, and None.<br> <img class="cctor-pro-img" alt="Coupon Creator Pro Border Examples" src="' . esc_url( Cctor__Coupon__Main::instance()->resource_url ) . 'images/cctor-border-examples.gif"/>' ),
				)
			);
			$fields[ $prefix . 'pro_feature_style_link' ]    = array(
				'id'      => $prefix . 'pro_feature_style_link',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'style',
				'title'   => '', // Not used for headings.
				'desc'    => __( 'Pro Link', 'coupon-creator' ),
				'type'    => 'pro_link'
			);
		}

		//Expiration
		$fields[ $prefix . 'expiration_help' ]    = array(
			'id'      => $prefix . 'expiration_help',
			'type'    => 'help',
			'section' => 'coupon_creator_meta_box',
			'tab'     => 'expiration',
		);
		$fields[ $prefix . 'heading_expiration' ] = array(
			'id'      => $prefix . 'heading_expiration',
			'title'   => '',
			'desc'    => __( 'Expiration', 'coupon-creator' ),
			'type'    => 'heading',
			'section' => 'coupon_creator_meta_box',
			'tab'     => 'expiration'
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
			)
		);

		$fields[ $prefix . 'expiration_msg_1' ] = array(
			'desc'      => __( 'This coupon will not expire and always show on the front end of your site.', 'coupon-creator' ),
			'id'        => $prefix . 'expiration_msg_1',
			'type'      => 'message',
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'expiration',
			'wrapclass' => 'expiration-field expiration-1',
			'bulkedit'  => '',
		);
		$fields[ $prefix . 'expiration_msg_2' ] = array(
			'desc'      => __( 'This coupon will no longer show the day after the expiration date.', 'coupon-creator' ),
			'id'        => $prefix . 'expiration_msg_2',
			'type'      => 'message',
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'expiration',
			'wrapclass' => 'expiration-field expiration-2',
			'bulkedit'  => '',
		);
		$fields[ $prefix . 'expiration_msg_3' ] = array(
			'desc'      => __( 'This coupon\'s expiration will change based on the choosen pattern.', 'coupon-creator' ),
			'id'        => $prefix . 'expiration_msg_3',
			'type'      => 'message',
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'expiration',
			'wrapclass' => 'expiration-field expiration-3',
			'bulkedit'  => '',
		);
		$fields[ $prefix . 'expiration_msg_4' ] = array(
			'desc'      => __( 'This coupon will expire X days from when it is printed.', 'coupon-creator' ),
			'id'        => $prefix . 'expiration_msg_4',
			'type'      => 'message',
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'expiration',
			'wrapclass' => 'expiration-field expiration-4',
			'bulkedit'  => '',
		);
		$fields[ $prefix . 'expiration_msg_5' ] = array(
			'desc'      => __( 'This coupon will show a range of dates that it is valid.', 'coupon-creator' ),
			'id'        => $prefix . 'expiration_msg_5',
			'type'      => 'message',
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'expiration',
			'wrapclass' => 'expiration-field expiration-5',
			'bulkedit'  => '',
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
		);

		$date_format = get_metadata( get_the_id(), $prefix . 'date_format', true );

		$fields[ $prefix . 'start_date' ] = array(
			'id'      => $prefix . 'start_date',
			'type'    => '',
			'section' => 'coupon_creator_meta_box',
			'tab'     => 'expiration'
		);

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
		);

		$fields[ $prefix . 'ignore_expiration' ] = array(
			'label'     => __( 'Ignore Expiration Date', 'coupon-creator' ),
			'desc'      => __( 'Check this to ignore the expiration date', 'coupon-creator' ),
			'id'        => $prefix . 'ignore_expiration',
			'type'      => 'checkbox',
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'expiration',
			'wrapclass' => 'expiration-field'
		);
		if ( ! defined( 'CCTOR_HIDE_UPGRADE' ) || ! CCTOR_HIDE_UPGRADE ) {
			$fields[ $prefix . 'pro_content_expiration_heading' ] = array(
				'id'      => $prefix . 'pro_content_expiration_heading',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'expiration',
				'title'   => '',
				'desc'    => __( 'Pro Content Features', 'coupon-creator' ),
				'type'    => 'pro_heading'
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
				)
			);
			$fields[ $prefix . 'pro_feature_expiration_link' ]    = array(
				'id'      => $prefix . 'pro_feature_expiration_link',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'expiration',
				'title'   => '', // Not used for headings.
				'desc'    => __( 'Pro Link', 'coupon-creator' ),
				'type'    => 'pro_link'
			);
		}

		//Image Coupon
		$fields[ $prefix . 'image_coupon_help' ]  = array(
			'id'      => $prefix . 'image_coupon_help',
			'type'    => 'help',
			'section' => 'coupon_creator_meta_box',
			'tab'     => 'image_coupon',
		);
		$fields[ $prefix . 'img_border_message' ] = array(
			'id'      => $prefix . 'img_border_message',
			'title'   => '',
			'desc'    => __( 'The Image Coupon is now part of the template system. Go to the Content tab and choose the Image template to create one.', 'coupon-creator-pro' ),
			'type'    => 'message',
			'section' => 'coupon_creator_meta_box',
			'tab'     => 'image_coupon'
		);

		//Help
		$fields[ $prefix . 'all_help' ] = array(
			'id'      => $prefix . 'all_help',
			'type'    => 'help',
			'section' => 'coupon_creator_meta_box',
			'tab'     => 'help',
		);

		//Links
		if ( ! defined( 'CCTOR_HIDE_UPGRADE' ) || ! CCTOR_HIDE_UPGRADE ) {
			$fields[ $prefix . 'pro_content_links_heading' ] = array(
				'id'      => $prefix . 'pro_content_links_heading',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'links',
				'title'   => '',
				'desc'    => __( 'Pro Content Features', 'coupon-creator' ),
				'type'    => 'pro_heading'
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
				)
			);
			$fields[ $prefix . 'pro_feature_links_link' ]    = array(
				'id'      => $prefix . 'pro_feature_links_link',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'links',
				'title'   => '', // Not used for headings.
				'desc'    => __( 'Pro Link', 'coupon-creator' ),
				'type'    => 'pro_link'
			);
		}

		if ( has_filter( 'cctor_filter_meta_fields' ) ) {
			/**
			 * Filter the meta fields from Coupon Creator
			 *
			 *
			 * @param array $fields an array of fields to display in meta tabs.
			 *
			 */
			$fields = apply_filters( 'cctor_filter_meta_fields', $fields );
		}

		return $fields;
	}

	/*
	* Get Template Fields
	*
	*/
	public static function get_template_fields( $fields ) {

		//fields prefix
		$prefix = self::get_fields_prefix();


		/**
		 * Start Default Template
		 */
		$fields[ $prefix . 'amount' ]        = array(
			'label'     => __( 'Deal', 'coupon-creator' ),
			'desc'      => __( 'Enter coupon deal - 30% OFF! or Buy One Get One Free, etc...', 'coupon-creator' ),
			'id'        => $prefix . 'amount',
			'type'      => 'text',
			'alert'     => '',
			'section'   => 'coupon_creator_meta_box',
			'styles'    => array(
				'font-color'       => $prefix . 'colorheader',
				'background-color' => $prefix . 'colordiscount',
			),
			'template'  => array( 'default' ),
			'tab'       => 'content',
			'wrapclass' => 'image-coupon-disable deal-display deal-display-both deal-display-hook deal-display-print',
			'display'   => array(
				'type'  => 'title',
				'class' => 'cctor_deal',
				'wrap'  => 'h3',
			),
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
		);

		$fields[ $prefix . 'deal_display' ] = array(
			'id'        => $prefix . 'deal_display',
			'type'      => '',
			'section'   => 'coupon_creator_meta_box',
			'template'  => array( 'default' ),
			'tab'       => 'content',
			'wrapclass' => 'image-coupon-disable',
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
			)
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
				)
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
		);

		return $fields;
	}
}
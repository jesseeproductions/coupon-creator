<?php
//If Direct Access Kill the Script
if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ ) {
	die( 'Access denied.' );
}


/*
* Admin Custom Meta Class
*
*/


class Cctor__Coupon__Admin__Meta extends Pngx__Admin__Meta {

	//fields id prefix
	protected $fields_prefix = 'cctor_';

	//post type
	protected $post_type = array( 'cctor_coupon' );

	//user capability
	protected $user_capability = 'edit_cctor_coupon';

	/*
	* Construct
	*/
	public function __construct() {

		parent::__construct();

		//Setup Coupon Fields at Init for Translation
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );

		//Setup Coupon Meta Boxes
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_boxes' ) );

		//Coupon Expiration Information
		add_action( 'edit_form_after_title', array( __CLASS__, 'coupon_messages' ), 5 );
		add_action( 'edit_form_after_title', array( __CLASS__, 'coupon_information_box' ) );

		//Add Plugin Only Fields
		add_filter( 'pngx_field_types', array( 'Cctor__Coupon__Admin__Fields', 'display_field' ), 5, 5 );

		//Modify Expiration Field
		add_filter( 'pngx_before_save_meta_fields', array( __CLASS__, 'modify_ignore_expiration' ) );

	}

	/**
	 * Admin Init
	 */
	public static function admin_init() {

		self::instance()->set_tabs();
		self::instance()->set_fields();

	}

	/**
	 * Add Hook on Coupon CPT Editor
	 */
	public static function coupon_information_box() {

		$current_screen = self::get_screen_variables();

		//Display Message on Coupon Edit Screen, but not on a new coupon until saved
		if ( 'post-new.php' != $current_screen['pagenow'] && in_array( $current_screen['type'], self::get_post_types() ) ) {

			/**
			 * Display Message on Individual Coupon Editor Page
			 *
			 * @since 1.90
			 *
			 * @param int $coupon_id
			 *
			 */
			do_action( 'pngx_meta_message', $current_screen['post'] );

		}

	}

	/**
	 * Add Messages to Coupon Message Hook
	 */
	public static function coupon_messages() {

		if ( class_exists( 'Cctor__Coupon__Pro__Expiration' ) ) {
			$coupon_expiration = new Cctor__Coupon__Pro__Expiration();
		} else {
			$coupon_expiration = new Cctor__Coupon__Expiration();
		}

		add_action( 'pngx_meta_message', array( $coupon_expiration, 'get_coupon_status' ), 15, 1 );
		add_action( 'pngx_meta_message', array( $coupon_expiration, 'the_coupon_status_msg' ), 20, 1 );

	}

	/*
	* Add Meta Boxes
	*/
	public static function add_meta_boxes() {

		$current_screen = self::get_screen_variables();

		if ( in_array( $current_screen['pagenow'], array( 'post.php', 'post-new.php' ) ) && in_array( $current_screen['type'], self::get_post_types() ) ) {

			add_meta_box( 'coupon_creator_meta_box', // id
				__( 'Coupon Fields', 'coupon-creator' ), // title
				array( __CLASS__, 'display_fields' ), // callback
				self::get_post_types(), // post_type
				'normal', // context
				'high' // priority
			);

			if ( 'post-new.php' != $current_screen['pagenow'] ) {
				add_meta_box( 'coupon_creator_shortcode', // id
					__( 'Coupon Shortcode', 'coupon-creator' ), // title
					array( __CLASS__, 'show_coupon_shortcode' ), // callback
					self::get_post_types(), // post_type
					'side' // context
				);
			}

			/**
			 * Additional Coupons Hook
			 */
			do_action( 'cctor_add_meta_box', $current_screen['post'] );

		}
	}

	/**
	 * Load Shortcode
	 *
	 * @param $post
	 */
	public static function show_coupon_shortcode( $post ) {
		?><p class="shortcode">
		<?php _e( 'Place this coupon in your posts, pages, custom post types, or widgets by using the shortcode below:<br><br>', 'coupon-creator' ); ?>
		<code>[coupon couponid="<?php echo $post->ID; ?>" name="<?php echo $post->post_title; ?>"]</code>
		</p><?php

	}

	/*
	* Set Tabs
	*/
	public function set_tabs() {

		//CPT Fields Tabs
		$tabs['content']      = __( 'Content', 'coupon-creator' );
		$tabs['style']        = __( 'Style', 'coupon-creator' );
		$tabs['expiration']   = __( 'Expiration', 'coupon-creator' );
		$tabs['image_coupon'] = __( 'Image Coupon', 'coupon-creator' );
		$tabs['help']         = __( 'Help', 'coupon-creator' );
		! defined( 'CCTOR_HIDE_UPGRADE' ) || ! CCTOR_HIDE_UPGRADE ? $tabs['pro'] = __( 'Upgrade to Pro', 'coupon-creator' ) : null;

		//Filter Option Tabs
		if ( has_filter( 'cctor_filter_meta_tabs' ) ) {

			/**
			 * Filter the Coupon Creator Meta Tab Header
			 *
			 *
			 * @param array $tabs an array of tab headings.
			 *
			 */
			$tabs = apply_filters( 'cctor_filter_meta_tabs', $tabs );
		}

		self::$tabs = $tabs;
	}

	/*
	* Load Fields
	*
	*/
	public function set_fields() {

		//fields pregix
		$prefix = self::get_fields_prefix();

		//Content
		$fields[ $prefix . 'content_help' ]  = array(
			'id'      => $prefix . 'content_help',
			'type'    => 'help',
			'section' => 'coupon_creator_meta_box',
			'tab'     => 'content',
		);
		$fields[ $prefix . 'heading_deal' ]  = array(
			'id'        => $prefix . 'heading_deal',
			'title'     => '',
			'desc'      => __( 'Coupon Deal', 'coupon-creator' ),
			'type'      => 'heading',
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'content',
			'wrapclass' => 'image-coupon-disable'
		);
		$fields[ $prefix . 'amount' ]        = array(
			'label'     => __( 'Deal', 'coupon-creator' ),
			'desc'      => __( 'Enter coupon deal - 30% OFF! or Buy One Get One Free, etc...', 'coupon-creator' ),
			'id'        => $prefix . 'amount',
			'type'      => 'text',
			'alert'     => '',
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'content',
			'wrapclass' => 'image-coupon-disable deal-display'
		);
		$fields[ $prefix . 'deal_display' ]  = array(
			'id'        => $prefix . 'deal_display',
			'type'      => '',
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'content',
			'wrapclass' => 'image-coupon-disable'
		);
		$fields[ $prefix . 'heading_terms' ] = array(
			'id'        => $prefix . 'heading_terms',
			'title'     => '',
			'desc'      => __( 'Coupon Terms', 'coupon-creator' ),
			'type'      => 'heading',
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'content',
			'wrapclass' => 'image-coupon-disable'
		);
		$fields[ $prefix . 'description' ]   = array(
			'label'     => __( 'Terms', 'coupon-creator' ),
			'desc'      => __( 'Enter the terms of the discount', 'coupon-creator' ),
			'id'        => $prefix . 'description',
			'type'      => 'textarea',
			'class'     => 'code',
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'content',
			'cols'      => 60,
			'rows'      => 4,
			'wrapclass' => 'image-coupon-disable'
		);

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
			'tab'     => 'style'
		);
		$fields[ $prefix . 'coupon_border_themes' ] = array(
			'id'      => $prefix . 'coupon_border_themes',
			'type'    => '',
			'section' => 'coupon_creator_meta_box',
			'tab'     => 'style'
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
			'id'      => $prefix . 'inside_radius',
			'type'    => '',
			'section' => 'coupon_creator_meta_box',
			'tab'     => 'style',
			'wrapclass' => 'image-coupon-disable'
		);

		//Discount
		$fields[ $prefix . 'heading_color' ] = array(
			'id'        => $prefix . 'heading_color',
			'title'     => '',
			'desc'      => __( 'Deal Field Colors', 'coupon-creator' ),
			'type'      => 'heading',
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'style',
			'wrapclass' => 'image-coupon-disable deal-display'
		);
		$fields[ $prefix . 'colordiscount' ] = array(
			'label'     => __( 'Deal Background Color', 'coupon-creator' ),
			'desc'      => __( 'Choose background color', 'coupon-creator' ),
			'id'        => $prefix . 'colordiscount',
			'type'      => 'color', // color
			'value'     => cctor_options( 'cctor_discount_bg_color' ),
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'style',
			'wrapclass' => 'image-coupon-disable deal-display'
		);
		$fields[ $prefix . 'colorheader' ]   = array(
			'label'     => __( 'Deal Text Color', 'coupon-creator' ),
			'desc'      => __( 'Choose color for discount text', 'coupon-creator' ),
			'id'        => $prefix . 'colorheader',
			'type'      => 'color', // color
			'value'     => cctor_options( 'cctor_discount_text_color' ),
			'section'   => 'coupon_creator_meta_box',
			'tab'       => 'style',
			'wrapclass' => 'image-coupon-disable deal-display'
		);

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
				'4' => __( 'Expires in X Days', 'coupon-creator' )
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
				'field' => 'select',
				'group' => '.expiration-field',
				'show'  => '.expiration-'
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
			'wrapclass' => 'expiration-field expiration-2 expiration-3 expiration-4',
			'bulkedit'  => 'cctor_pro_expiration',
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
			'wrapclass' => 'expiration-field expiration-2 expiration-3',
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

		//Image Coupon
		$fields[ $prefix . 'image_coupon_help' ] = array(
			'id'      => $prefix . 'image_coupon_help',
			'type'    => 'help',
			'section' => 'coupon_creator_meta_box',
			'tab'     => 'image_coupon',
		);

		$img_toggle = array(
			'field' => 'input',
			'type'  => 'image',
			'group' => '.image-coupon-disable',
			'show'  => '',
			'msg'   => array(
				'content' => __( ' Content Fields are disabled when using an Image Coupon', 'coupon-creator' ),
				'style'   => __( ' Style Fields are disabled when using an Image Coupon', 'coupon-creator' )
			)
		);

		if ( class_exists( 'Cctor__Coupon__Pro__Main' ) ) {
			$img_toggle = array(
				'field' => 'input',
				'type'  => 'image',
				'group' => '.image-coupon-disable',
				'show'  => '',
				'msg'   => array(
					'content' => __( ' Content Fields are disabled when using an Image Coupon', 'coupon-creator' ),
					'style'   => __( ' Only outer border styles are available when using an Image Coupon', 'coupon-creator' )
				)
			);

		}
		$fields[ $prefix . 'image' ] = array(
			'label'    => '',
			'desc'     => __( 'Upload an image to use as the entire coupon - Current image size is for 390 pixels in width with auto height', 'coupon-creator' ),
			'id'       => $prefix . 'image',
			'type'     => 'image',
			'imagemsg' => 'Image Coupon',
			'section'  => 'coupon_creator_meta_box',
			'tab'      => 'image_coupon',
			'toggle'   => $img_toggle
		);
		//Help
		$fields[ $prefix . 'all_help' ] = array(
			'id'      => $prefix . 'all_help',
			'type'    => 'help',
			'section' => 'coupon_creator_meta_box',
			'tab'     => 'help',
		);

		//Upgreade to Pro
		$fields[ $prefix . 'upgrade_to_pro' ] = array(
			'label'   => '',
			'id'      => $prefix . 'upgrade_to_pro',
			'type'    => 'pro',
			'section' => 'coupon_creator_meta_box',
			'tab'     => 'pro'
		);

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

		self::$fields = $fields;
	}


	/**
	 * Set Ignore Expiration Field
	 */
	public static function modify_ignore_expiration() {

		//Expiration Option Auto Check Ignore Input
		if ( isset( $_POST['cctor_ignore_expiration'] ) && 1 == $_POST['cctor_expiration_option'] ) {
			$_POST['cctor_ignore_expiration'] = 'on';
		} elseif ( isset( $_POST['cctor_ignore_expiration'] ) && 'on' == $_POST['cctor_ignore_expiration'] && 1 != $_POST['cctor_expiration_option'] ) {
			unset( $_POST['cctor_ignore_expiration'] );
		}
	}

	/**
	 * Static Singleton Factory Method
	 *
	 * @return Pngx__Admin__Options
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			$className      = __CLASS__;
			self::$instance = new $className;
		}

		return self::$instance;
	}

}


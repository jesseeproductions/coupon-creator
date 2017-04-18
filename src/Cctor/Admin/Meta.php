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

		// Add default template
		add_filter( 'pngx-default-template', array( __CLASS__, 'default_template' ) );

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
		$tabs['style']        = __( 'Border & Background', 'coupon-creator' );
		$tabs['expiration']   = __( 'Expiration', 'coupon-creator' );
		$tabs['image_coupon'] = __( 'Image Coupon', 'coupon-creator' );
		! defined( 'CCTOR_HIDE_UPGRADE' ) || ! CCTOR_HIDE_UPGRADE ? $tabs['links'] = __( 'Links', 'coupon-creator' ) : null;
		$tabs['help'] = __( 'Help', 'coupon-creator' );


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
		self::$fields = Cctor__Coupon__Meta__Fields::get_fields();
	}


	/**
	 * Add default template
	 *
	 * @param $template
	 *
	 * @return bool|null
	 */
	public static function default_template( $template ) {
		if ( ! $template ) {
			$template = cctor_options( 'cctor_default_template' );
		}

		return $template;
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


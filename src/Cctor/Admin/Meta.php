<?php
use Cctor\Coupon\Templates\Admin_Template;

/**
 * Class Cctor__Coupon__Admin__Meta
 */
class Cctor__Coupon__Admin__Meta extends Pngx__Admin__Meta {

	//fields id prefix
	protected $fields_prefix = 'cctor_';

	//post type
	protected $post_type = array( 'cctor_coupon' );

	//user capability
	protected $user_capability = 'edit_cctor_coupon';

	/**
	 * An instance of the admin template handler.
	 *
	 * @since 0.1.0
	 *
	 * @var Admin_Template
	 */
	protected $admin_template;

	/**
	 * Meta constructor.
	 *
	 * @since 3.4.0
	 *
	 * @param Admin_Template $template An instance of the backend template handler.
	 */
	public function __construct( Admin_Template $admin_template ) {
		$this->admin_template = $admin_template;
		parent::__construct();
	}

	/**
	 * Admin Init
	 */
	public function setup() {

		//Setup Menu Meta Boxes
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		//Coupon Expiration Information
		add_action( 'edit_form_after_title', array( $this, 'coupon_messages' ), 5 );
		add_action( 'edit_form_after_title', array( $this, 'coupon_information_box' ) );

		// Add default template
		add_filter( 'pngx-default-template', array( $this, 'default_template' ) );

		//Modify Expiration Field
		add_filter( 'pngx_before_save_meta_fields', array( $this, 'modify_ignore_expiration' ) );

		$this->set_tabs();
		$this->set_fields();

	}

	/**
	 * Add Hook on Coupon CPT Editor
	 */
	public function coupon_information_box() {

		$current_screen = $this->get_screen_variables();

		//Display Message on Coupon Edit Screen, but not on a new coupon until saved
		if ( 'post-new.php' != $current_screen['pagenow'] && in_array( $current_screen['type'], $this->get_post_types() ) ) {

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
	public function coupon_messages() {

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
	public function add_meta_boxes() {

		$current_screen = $this->get_screen_variables();

		if ( in_array( $current_screen['pagenow'], array( 'post.php', 'post-new.php' ) ) && in_array( $current_screen['type'], $this->get_post_types() ) ) {

			add_meta_box( 'coupon_creator_meta_box', // id
				__( 'Coupon Fields', 'coupon-creator' ), // title
				array( $this, 'display_fields' ), // callback
				$this->get_post_types(), // post_type
				'normal', // context
				'high' // priority
			);

			if ( 'post-new.php' != $current_screen['pagenow'] ) {
				add_meta_box( 'coupon_creator_shortcode', // id
					__( 'Coupon Shortcode', 'coupon-creator' ), // title
					array( $this, 'show_coupon_shortcode' ), // callback
					$this->get_post_types(), // post_type
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
	public function show_coupon_shortcode( $post ) {
		?><p class="shortcode">
		<?php esc_html_e( 'Place this coupon in your posts, pages, custom post types, or widgets by using the shortcode below:', 'coupon-creator' ); ?>
		<br><br><code>[coupon couponid="<?php echo absint( $post->ID ); ?>" name="<?php echo esc_html( $post->post_title ); ?>"]</code>
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

		$this->tabs = $tabs;
	}

	/*
	* Load Fields
	*
	*/
	public function set_fields() {
		$this->fields = pngx( 'cctor.meta' )->get_fields();
	}


	/**
	 * Add default template
	 *
	 * @param $template
	 *
	 * @return bool|null
	 */
	public function default_template( $template ) {
		$default = cctor_options( 'cctor_default_template' );

		if ( $default ) {
			$template = $default;
		}

		return $template;
	}

	/**
	 * Set Ignore Expiration Field
	 */
	public function modify_ignore_expiration() {

		//Expiration Option Auto Check Ignore Input
		if ( isset( $_POST['cctor_ignore_expiration'] ) && 1 == $_POST['cctor_expiration_option'] ) {
			$_POST['cctor_ignore_expiration'] = 'on';
		} elseif ( isset( $_POST['cctor_ignore_expiration'] ) && 'on' == $_POST['cctor_ignore_expiration'] && 1 != $_POST['cctor_expiration_option'] ) {
			unset( $_POST['cctor_ignore_expiration'] );
		}
	}

}


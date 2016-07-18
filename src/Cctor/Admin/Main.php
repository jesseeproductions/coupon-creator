<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


/**
 * Coupon Admin Class
 *
 *
 */
class Cctor__Coupon__Admin__Main {


	/*
	* Admin Construct
	*/
	public function __construct() {

		//Setup Admin
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );

	}


	/**
	 * Admin Init
	 */
	public static function admin_init() {

		if ( !class_exists( 'Coupon_Creator_Pro_Plugin' ) ) {
			//Add Button for Coupons in Editor
			Coupon_Creator_Plugin::include_file( 'admin/cctor-inserter-class.php' );
			new Coupon_Creator_Inserter();
		}
		//Add Options Link on Plugin Activation Page
		add_action('plugin_action_links', array( __CLASS__, 'plugin_setting_link' ) , 10, 2);

		//Load Admin Coupon Scripts
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'cctor_edit_enqueue_style_scripts' ) );

	} //end admin_init


}
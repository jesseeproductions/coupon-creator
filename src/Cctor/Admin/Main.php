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
		add_action( 'admin_init', array( $this, 'admin_init' ) );

		//Update Version Number
		add_action( 'admin_init', pngx_callback( 'cctor.admin.upgrades' , 'admin_upgrade_version' ) );

		//handle older versions of Pro so they can update before 2.4
		if ( defined( 'CCTOR_PRO_VERSION_NUM' ) && 2.4 > CCTOR_PRO_VERSION_NUM ) {

			new Cctor__Coupon__Admin__Pro_License_Pre_24();

		}

	}

	/**
	 * Admin Init
	 */
	public function admin_init() {

		if ( ! class_exists( 'Cctor__Coupon__Pro__Main' ) ) {
			new Cctor__Coupon__Admin__Inserter();
		}

		//Add Options Link on Plugin Activation Page
		add_action( 'plugin_action_links', array( $this, 'plugin_setting_link' ), 10, 2 );


	} //end admin_init


	/*
	* Add Options Link in Plugin entry of Plugins Menu
	*
	*/
	public function plugin_setting_link( $links, $file ) {
		static $this_plugin;

		if ( ! $this_plugin ) {
			$this_plugin = 'coupon-creator/coupon_creator.php';
		}

		// make sure this is the coupon creator
		if ( $file == $this_plugin ) {

			//Show Options Link
			$plugin_links[] = '<a href="' . esc_url( get_admin_url() ) . 'edit.php?post_type=cctor_coupon&page=coupon-options">' . esc_html__( 'Options', 'coupon-creator' ) . '</a>';

			//Show Upgrade to Pro Link
			if ( ! defined( 'CCTOR_HIDE_UPGRADE' ) || ! CCTOR_HIDE_UPGRADE ) {
				$plugin_links[] = '<a href="http://cctor.link/Abqoi">Upgrade to Pro!</a>';
			}

			// add the settings link to the links
			foreach ( $plugin_links as $link ) {
				array_unshift( $links, $link );
			}
		}

		return $links;
	}

}
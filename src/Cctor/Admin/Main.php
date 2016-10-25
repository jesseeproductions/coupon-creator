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

		//Check to flush permalinks
		add_action( 'init', array( 'Pngx__Admin__Fields', 'flush_permalinks' ) );

		//Setup Admin
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );

		//Update Version Number
		add_action( 'admin_init', array( 'Cctor__Coupon__Admin__Updates', 'admin_upgrade_version' ) );

		Cctor__Coupon__Admin__Options::instance();

		new Cctor__Coupon__Admin__Columns();
		Cctor__Coupon__Admin__Meta::instance();

		//handle older versions of Pro so they can update before 2.4
		if ( defined( 'CCTOR_PRO_VERSION_NUM' ) && 2.4 > CCTOR_PRO_VERSION_NUM ) {

			new Cctor__Coupon__Admin__Pro_License_Pre_24();

		}

	}

	/**
	 * Admin Init
	 */
	public static function admin_init() {

		if ( ! class_exists( 'Coupon_Creator_Pro_Plugin' ) ) {
			new Cctor__Coupon__Admin__Inserter();
		}


		//Add Options Link on Plugin Activation Page
		add_action( 'plugin_action_links', array( __CLASS__, 'plugin_setting_link' ), 10, 2 );

		//Load Admin Assets
		add_action( 'admin_enqueue_scripts', array( 'Cctor__Coupon__Admin__Assets', 'load_assets' ) );

		//Load License Fields for Old Versions of Pro to Upgrade
		//$pro_version = get_option( 'cctor_coupon_pro_version' );
		//if ( $pro_version && version_compare( $pro_version , Cctor__Coupon__Main::CCTOR_VERSION_NUM, '<' ) )  {
		//new Cctor__Coupon__Admin__Upgrades();
		//}


	} //end admin_init

	/*
	* Add Options Link in Plugin entry of Plugins Menu
	*
	*/
	public static function plugin_setting_link( $links, $file ) {
		static $this_plugin;

		if ( ! $this_plugin ) {
			$this_plugin = 'coupon-creator/coupon_creator.php';
		}

		// make sure this is the coupon creator
		if ( $file == $this_plugin ) {

			//Show Options Link
			$plugin_links[] = '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/edit.php?post_type=cctor_coupon&page=coupon-options">Options</a>';

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
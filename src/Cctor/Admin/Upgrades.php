<?php
//If Direct Access Kill the Script
if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ ) {
	die( 'Access denied.' );
}


/**
 * Class Cctor__Coupon__Admin__Upgrades
 *
 * Show the License Fields If Core was Already Updated but not Pro
 *
 */
class Cctor__Coupon__Admin__Upgrades {

	/*
	* Construct
	*/
	public function __construct() {

		//Load Pro Option Tabs
		add_filter( 'cctor_option_sections', array( __CLASS__, 'cctor_pro_option_tabs' ), 10, 1 );

		//Load Pro Option Fields
		add_filter( 'cctor_option_filter', array( __CLASS__, 'cctor_pro_option_fields' ), 10, 1 );

		//License Update
		add_action( 'admin_init', array( __CLASS__, 'cctor_pro_plugin_updater' ), 0 );

	}

	public static function cctor_pro_plugin_updater() {

		// retrieve our license key from the DB
		$cctor_license_info = get_option( 'cctor_pro_license' );

		//Check if the License has changed and deactivate
		if ( ( isset( $cctor_license_info['key'] ) && '' != $cctor_license_info['key'] ) && ( isset( $cctor_license_info['status'] ) && 'valid' == $cctor_license_info['status'] ) ) {

			// setup the updater
			$edd_updater = new EDD_SL_Plugin_Updater( COUPON_CREATOR_STORE_URL, CCTOR_PRO_PATH . 'coupon-creator-pro.php', array(
				'version'   => get_option( CCTOR_PRO_VERSION_KEY ),    // current version number
				'license'   => trim( $cctor_license_info['key'] ),
				'item_name' => COUPON_CREATOR_PRO,        // name of this plugin
				'author'    => 'Brian Jessee'    // author of this plugin
			) );

		}

	}

	/*
	* Coupon Creator Pro Option Tabs
	*/
	public static function cctor_pro_option_tabs( $sections ) {

		$sections['license'] = __( 'Licenses', 'coupon-creator-pro' );

		return $sections;

	}

	/*
	* Coupon Creator Pro Option Fields
	*/
	public static function cctor_pro_option_fields( $options ) {

		//Pro License
		$options['cctor_pro_license_head']   = array(
			'section' => 'license',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Coupon Creator Pro License', 'coupon-creator-pro' ),
			'type'    => 'heading'
		);
		$options['cctor_pro_license']        = array(
			'title'   => __( 'License Key', 'coupon-creator-pro' ),
			'desc'    => __( 'Enter your license key for Coupon Creator Pro ', 'coupon-creator-pro' ),
			'std'     => '',
			'type'    => 'license',
			'section' => 'license',
			'class'   => 'cctor_pro_license'
		);
		$options['cctor_pro_license_status'] = array(
			'title'     => __( 'Activate Key', 'coupon-creator-pro' ),
			'desc'      => '',
			'std'       => '',
			'condition' => COUPON_CREATOR_PRO,
			'type'      => 'license_status',
			'section'   => 'license',
			'class'     => 'cctor_pro_license'
		);

		return $options;

	}

}
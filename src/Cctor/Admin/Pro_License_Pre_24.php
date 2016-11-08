<?php
//If Direct Access Kill the Script
if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ ) {
	die( 'Access denied.' );
}


/**
 * Class Cctor__Coupon__Admin__Upgrades
 *
 * Init License Field for Older Versions of Pro
 *
 */
class Cctor__Coupon__Admin__Pro_License_Pre_24 {

	/*
	* Construct
	*/
	public function __construct() {

		//License Class
		$license_handler = new Pngx__Admin__EDD_License(
			Cctor__Coupon__Main::instance()->get_shop_url(),
			Cctor__Coupon__Main::OPTIONS_ID,
			'cctor_pro_license'
		);
		add_action( 'admin_init', array( $license_handler, 'activate_license' ) );
		add_action( 'admin_init', array( $license_handler, 'deactivate_license' ) );

	}

}
<?php
//If Direct Access Kill the Script
if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ ) {
	die( 'Access denied.' );
}


/**
 * Class Cctor__Coupon__Admin__License_Setup
 *
 * @since 3.0
 *
 * Init License Field on Plugins and enable Automatic Updates for Pre 3.0 Versions
 *
 */
class Cctor__Coupon__Admin__License_Setup {

	/*
	* Construct
	*/
	public function __construct( $name, $license, $key, $version, $path, $file, $status ) {

		//setup license and update system for Pro
		$license_handler = new Pngx__Admin__EDD_License(
			Cctor__Coupon__Main::instance()->get_shop_url(),
			Cctor__Coupon__Main::OPTIONS_ID,
			$license,
			$name
		);

		new Pngx__Admin__Updates(
			$name,
			$key,
			$version,
			$license,
			Cctor__Coupon__Main::instance()->get_shop_url(),
			$path
		);

		$license_key = new Pngx__Admin__Plugin_License_List(
			$file,
			$license,
			$name,
			$status,
			Cctor__Coupon__Admin__Options::instance()->get_option_fields(),
			Cctor__Coupon__Main::instance()->get_shop_url(),
			array (
				__( 'Options', 'coupon-creator' ) => 'edit.php?post_type=cctor_coupon&page=coupon-options',
			)
		);

		add_action( 'admin_init', array( $license_handler, 'activate_license' ), 30 );
		add_action( 'admin_init', array( $license_handler, 'deactivate_license' ), 30 );

	}

}
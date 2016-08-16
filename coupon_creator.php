<?php
/*
Plugin Name: Coupon Creator
Description: This plugin creates a custom post type for coupons with a shortcode to display it on website and a single view template for printing.
Version: 2.4dev
Author: Brian Jessee
Author URI: http://couponcreatorplugin.com
Text Domain: coupon-creator
License: GPLv2 or later
*/
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

/**
 * Get Options from Array
 *
 * echo cctor_options('cctor_coupon_base');
 *
 * @param      $option
 * @param null $falseable
 * @param null $default
 *
 * @return bool|null
 */
function cctor_options( $option, $falseable = null, $default = null ) {
	$options = get_option( 'coupon_creator_options' );

	if ( isset( $options[ $option ] ) &&  $options[ $option ] != '' ) {
		return $options[ $option ];
	} elseif ( $falseable ) {
		return false;
	} elseif ( $default ) {
		return $default;
	} else {
		return false;
	}

}

// the main plugin class
require_once dirname( __FILE__ ) . '/src/Cctor/Main.php';
Cctor__Coupon__Main::instance();
register_activation_hook( __FILE__, array( 'Cctor__Coupon__Main', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Cctor__Coupon__Main', 'deactivate' ) );

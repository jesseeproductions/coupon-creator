<?php
/*
Plugin Name: Coupon Creator
Description: This plugin creates a custom post type for coupons with a shortcode to display it on website and a single view template for printing.
Version: 2.5
Author: Brian Jessee
Author URI: http://couponcreatorplugin.com
Text Domain: coupon-creator
License: GPLv2 or later
*/
//If Direct Access Kill the Script
if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ ) {
	die( 'Access denied.' );
}

// the main plugin class
require_once dirname( __FILE__ ) . '/src/Cctor/Main.php';
Cctor__Coupon__Main::instance();
register_activation_hook( __FILE__, array( 'Cctor__Coupon__Main', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Cctor__Coupon__Main', 'deactivate' ) );


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
	$options = get_option( Cctor__Coupon__Main::OPTIONS_ID );

	if ( isset( $options[ $option ] ) && $options[ $option ] != '' ) {
		return $options[ $option ];
	} elseif ( $falseable ) {
		return false;
	} elseif ( $default ) {
		return $default;
	} else {
		return false;
	}

}

if( ! class_exists( 'Plugin_Usage_Tracker') ) {
	require_once dirname( __FILE__ ) . '/src/tracking/class-plugin-usage-tracker.php';
}
if( ! function_exists( 'coupon_creator_start_plugin_tracking' ) ) {
	function coupon_creator_start_plugin_tracking() {
		$wisdom = new Plugin_Usage_Tracker(
			__FILE__,
			'https://couponcreatorplugin.com',
			array('coupon_creator_options'),
			true,
			true,
			1
		);
	}
	coupon_creator_start_plugin_tracking();
}

/**
 * Custom Deactivation Reasons
 */
add_filter( 'wisdom_form_text_' . __FILE__, 'cctor_filter_deactivation_form' );
function cctor_filter_deactivation_form( $form ) {

	$form['heading'] = __( 'Sorry to see you go', 'coupon-creator' );

	$form['body'] = __( 'Before you deactivate the plugin, would you quickly give us your reason for doing so?', 'coupon-creator' );

	$form['options'] = array(
		__( 'Could not create a coupon', 'coupon-creator' ),
		__( 'Could not display my coupons', 'coupon-creator' ),
		__( 'Looking for affiliate coupon features', 'coupon-creator' ),
		__( 'Could not find where to get started', 'coupon-creator' ),
		__( 'Not the features I wanted', 'coupon-creator' ),
		__( 'Only required temporarily', 'coupon-creator' ),
		__( 'Lack of documentation', 'coupon-creator' ),
		__( 'Found a better plugin', 'coupon-creator' ),
	);

	return $form;

}
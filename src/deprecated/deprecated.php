<?php
//If Direct Access Kill the Script
if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ ) {
	die( 'Access denied.' );
}

if ( class_exists( 'Coupon_Creator_Plugin' ) ) {

	if ( ! function_exists( 'cctor_expiration_check' ) ) {
		/*
		* Coupon Creator Check if Coupon Should Show
		* @deprecated since 2.3
		*/
		function cctor_expiration_check( $coupon_id ) {

			_deprecated_function( __FUNCTION__, '2.3', 'Cctor__Coupon__Expiration' );

			//Ignore Expiration Value
			$ignore_expiration = get_post_meta( $coupon_id, 'cctor_ignore_expiration', true );

			//Return If Not Passed Expiration Date
			$expiration = cctor_expiration_and_current_date( $coupon_id );

			//Enable Filter to stop coupon from showing
			$show_coupon_check = false;

			$show_coupon_check = apply_filters( 'cctor_show_coupon_check', $show_coupon_check, $coupon_id );
			if ( ( $expiration || $ignore_expiration == 1 ) && ! $show_coupon_check ) {
				return true;

			} else {
				return false;

			}
		}
	}

	if ( ! function_exists( 'cctor_expiration_and_current_date' ) ) {
		/*
		* Coupon Creator Return Expiration Date and Current Date
		* @deprecated since 2.3
		*/
		function cctor_expiration_and_current_date( $coupon_id ) {

			_deprecated_function( __FUNCTION__, '2.3', 'Cctor__Coupon__Expiration' );

			//Coupon Expiration Date
			$expirationco             = get_post_meta( $coupon_id, 'cctor_expiration', true );
			$expiration['expiration'] = strtotime( $expirationco );

			//Blog Time According to WordPress
			$cc_blogtime = current_time( 'mysql' );
			list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = preg_split( '([^0-9])', $cc_blogtime );
			$expiration['today'] = strtotime( $today_month . "/" . $today_day . "/" . $today_year );

			if ( $expiration['expiration'] >= $expiration['today'] ) {
				return true;
			} else {
				return false;
			}
		}
	}
}
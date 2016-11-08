<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
if ( class_exists( 'Cctor__Coupon__Admin__Field__Pro_Heading' ) ) {
	return;
}


/**
 * Class Cctor__Coupon__Admin__Field__Pro_Header
 * Coupon Creator Pro Field Header
 */
class Cctor__Coupon__Admin__Field__Pro_Heading {

	public static function display( $field = array(), $options = array(), $options_id = null, $meta = null ) {

		if ( ! defined( 'CCTOR_HIDE_UPGRADE' ) || ! CCTOR_HIDE_UPGRADE ) {

			if ( isset( $options_id ) && ! empty( $options_id ) ) {
				echo '</td></tr><tr valign="top"><td colspan="2">';
			}

			echo '<div class="pro-heading"><img style="width: 180px; height: auto;" alt="Get Coupon Creator Pro!" src="' . esc_url( Cctor__Coupon__Main::instance()->resource_url ) . 'images/cctor-logo.png"/></div>';
		}

	}
}

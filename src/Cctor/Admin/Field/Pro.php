<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
if ( class_exists( 'Cctor__Coupon__Admin__Field__Pro' ) ) {
	return;
}


/**
 * Class Cctor__Coupon__Admin__Field__Pro
 * Coupon Creator Pro Field
 */
class Cctor__Coupon__Admin__Field__Pro {

	public static function display( $field = array(), $options = array(), $options_id = null, $meta = null ) {

		echo ! defined( 'CCTOR_HIDE_UPGRADE' ) || ! CCTOR_HIDE_UPGRADE ? Cctor__Coupon__Admin__Options::display_pro_section() : '';

	}

}

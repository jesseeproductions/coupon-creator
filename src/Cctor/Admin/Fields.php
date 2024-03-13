<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


/**
 * Class Cctor__Coupon__Admin__Fields
 * Coupon Fields for Meta and Options
 */
class Cctor__Coupon__Admin__Fields {

	/*
	* Display Individual Fields
	*/
	public static function display_field( $field = array(), $options = array(), $options_id = null, $meta = null, $wp_version = null ) {

		//Create Different Name for Option Fields and Not Meta Fields
		if ( $options ) {
			$options_id = $options_id . '[' . $field['id'] . ']';
		}

		switch ( $field['type'] ) {

			case 'coupon_image':

				Cctor__Coupon__Admin__Field__Help::display( $field, $options, $options_id, $meta );

				break;

			case 'help':

				Cctor__Coupon__Admin__Field__Help::display( $field, $options, $options_id, $meta );

				break;

			case 'pro_heading':
				Cctor__Coupon__Admin__Field__Pro_Heading::display( $field, $options, $options_id, $meta );

				break;

			case 'pro_link':

				Cctor__Coupon__Admin__Field__Pro_Link::display( $field, $options, $options_id, $meta );

				break;

		}

		//return field so other filters can use it
		return $field;

	}

}
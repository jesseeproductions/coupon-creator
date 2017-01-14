<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
if ( class_exists( 'Cctor__Coupon__Admin__Field__Counter_Expiration_Msg' ) ) {
	return;
}


/**
 * Class Cctor__Coupon__Admin__Field__Counter_Expiration_Msg
 * Message Field
 */
class Cctor__Coupon__Admin__Field__Counter_Expiration_Msg {

	public static function display( $field = array(), $options = array(), $options_id = null, $meta = null ) {

		echo '<span class="description">' . $field['desc'] . '</span>';

	}

}

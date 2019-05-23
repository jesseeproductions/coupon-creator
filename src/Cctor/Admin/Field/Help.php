<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
if ( class_exists( 'Cctor__Coupon__Admin__Field__Help' ) ) {
	return;
}


/**
 * Class Cctor__Coupon__Admin__Field__Help
 * Help Fields
 */
class Cctor__Coupon__Admin__Field__Help {

	public static function display( $field = array(), $options = array(), $options_id = null, $meta = null ) {

		if ( isset( $options_id ) && ! empty( $options_id ) ) {
			$tab       = $field['section'];
			$screen_id = 'cctor_coupon_page_coupon-options';
		} else {
			$tab       = $field['tab'];
			$screen_id = '';
		}

		if ( 'cctor_all_help' == $field['id'] ) {
			$help_class = new Cctor__Coupon__Admin__Help();
			$help_class->display_help( 'all', false, 'coupon' );
			echo $help_class->get_cctor_support_core_contact();

			//Return as this is only showing all the help documents
			return;
		}

		//Display Help Per Tab
		$help_class = new Cctor__Coupon__Admin__Help();
		$help_class->display_help( $tab, $screen_id, 'coupon' );

	}

}

<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


/**
 * Class Cctor__Coupon__Meta__Order
 */
class Cctor__Coupon__Meta__Order {

	protected static $instance;

	/**
	 * Static Singleton Factory Method
	 *
	 * @return Cctor__Coupon__Meta__Order
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			$className      = __CLASS__;
			self::$instance = new $className;
		}

		return self::$instance;
	}

	/**
	 * Get the Ordered MEta Fields
	 *
	 * @param array $fields
	 *
	 * @return mixed|void
	 */
	public function get_ordered_meta_fields( $fields = array() ) {

		/**
		 * Filter the meta fields from Coupon Creator
		 *
		 *
		 * @param array $fields an array of fields to display in meta tabs.
		 *
		 */
		$fields = apply_filters( 'cctor_filter_meta_fields', $fields );

		if ( get_bloginfo( 'version' ) >= 4.7 ) {
			$fields = wp_list_sort( $fields, 'priority', 'ASC', true );
		} else {
			usort( $fields, array( $this, 'orderby_priority' ) );
		}

		return $fields;

	}

	/**
	 * Get the Ordered Template Fields
	 *
	 * @param array $fields
	 *
	 * @return mixed|void
	 */
	public function get_ordered_template_fields( $fields = array() ) {

		/**
		 * Filter the meta fields from Coupon Creator for custom templates
		 *
		 *
		 * @param array $fields an array of fields to display in meta tabs.
		 *
		 */

		$fields = apply_filters( 'cctor_filter_meta_template_fields', $fields );

		if ( get_bloginfo( 'version' ) >= 4.7 ) {
			$fields = wp_list_sort( $fields, 'priority', 'ASC', true );
		} else {
			usort( $fields, array( $this, 'orderby_priority' ) );
		}

		return $fields;

	}

	/**
	 * Order Fields by Priority
	 *
	 * @since 2.5.5
	 *
	 * @todo  remove once 4.7 is the minimum version
	 *
	 * @param $a
	 * @param $b
	 *
	 * @return mixed
	 */
	public function orderby_priority( $a, $b ) {

		if ( $a['priority'] == $b['priority'] ) {
			return 0;
		}

		return ( $a['priority'] < $b['priority'] ) ? - 1 : 1;

	}

}

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
	 * @return self
	 */
	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Get the Ordered Meta Fields
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

		$fields = wp_list_sort( $fields, 'priority', 'ASC', true );

		return $fields;

	}

	/**
	 * Get the Ordered Template Fields
	 *
	 * @param array $fields
	 *
	 * @return mixed|void
	 */
	public function get_ordered_template_fields( $fields = [] ) {

		/**
		 * Filter the meta fields from Coupon Creator for custom templates
		 *
		 *
		 * @param array $fields an array of fields to display in meta tabs.
		 *
		 */
		$fields = apply_filters( 'cctor_filter_meta_template_fields', $fields );

		$fields = wp_list_sort( $fields, 'priority', 'ASC', true );

		return $fields;

	}
}

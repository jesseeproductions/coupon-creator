<?php
/**
 * Handles the Meta for Test.
 *
 * @since 0.1.0
 *
 * @package Cctor\Coupon\Admin;
 */

namespace Cctor\Coupon\Test;

/**
 * Class Meta
 *
 * @since 0.1.0
 *
 * @package Cctor\Coupon\Admin;
 */
class Meta {

	/**
	 * Add Tabs.
	 *
	 * @since 0.1.0
	 */
	public function add_tab( $tabs ) {
		$new_tabs['test']      = __( 'Test', 'coupon-test' );

		return array_merge( $new_tabs, $tabs );
	}

	/**
	 * Load Fields
	 *
	 * @since 0.1.0
	 */
	public function add_meta( array $fields = [] ) {
		return pngx( Meta_Fields::class )->get_fields( $fields );
	}
}


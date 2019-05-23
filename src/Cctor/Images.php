<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


/**
 * Add Image Sizes and Filters for Image Coupons
 *
 *
 */
class Cctor__Coupon__Images {

	/**
	 * Register Image Sizes
	 */
	public function add_image_sizes() {

		$cctor_img_size           = array();
		$cctor_img_size['single'] = 300;
		$cctor_img_size['print']  = 390;

		if ( has_filter( 'cctor_img_size' ) ) {
			/**
			 * Filter Image Coupon Image Sizes
			 *
			 *
			 * @param array $cctor_img_size
			 *
			 */
			$cctor_img_size = apply_filters( 'cctor_img_size', $cctor_img_size );
		}

		add_image_size( 'single_coupon', $cctor_img_size['single'] );
		add_image_size( 'print_coupon', $cctor_img_size['print'] );
	}

}
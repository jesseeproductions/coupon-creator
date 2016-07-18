<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Load Front End Assets
 *
 *
 */
class Cctor__Coupon__Assets {

	/*
	* Register Coupon Creator CSS
	*/
	public static function load_assets() {

		// @formatter:off
		wp_register_style(
			'coupon_creator_css',
			Cctor__Coupon__Main::instance()->resource_url . 'css/coupon.css',
			false,
			filemtime( Cctor__Coupon__Main::instance()->resource_path . 'css/coupon.css' )
		);
		// @formatter:on

	}

	/*
	* Add Inline Style From Coupon Options
	*/
	public static function inline_style() {

		$cctor_option_css = "";

		if ( has_filter( 'cctor_filter_inline_css' ) ) {
			$coupon_css = "";
			/**
			 * Filter Coupon Inline Styles
			 *
			 *
			 * @param string $coupon_css .
			 *
			 */
			$cctor_option_css = apply_filters( 'cctor_filter_inline_css', $coupon_css );
		}
		//Add Custom CSS from Options
		if ( cctor_options( 'cctor_custom_css' ) ) {

			$cctor_option_css .= cctor_options( 'cctor_custom_css' );
		}

		wp_add_inline_style( 'coupon_creator_css', wp_kses_post( $cctor_option_css ) );
	}

}
<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


/**
 * Print Template Class
 *
 * For display of coupons in print view.
 *
 */
class Cctor__Coupon__Print {

	/*
	* Use Single Coupon Template from Plugin when creating the print version
	*
	*/
	public static function get_coupon_post_type_template( $print_template ) {

		global $post;
		if ( ! is_search() && is_object( $post ) && 'cctor_coupon' == $post->post_type ) {
			$print_template = Cctor__Coupon__Main::instance()->plugin_path . 'src/functions/templates/print-coupon.php';
		}

		return $print_template;
	}

	/*
	* Hook Custom CSS into Print Template
	*
	*/
	public static function print_css() {

		$cctor_option_css = "";
		/*
		*  Filter to Add More Custom CSS
		*/
		if ( has_filter( 'cctor_filter_inline_css' ) ) {
			$coupon_css = "";
			/**
			 * Filter Print View Inline Styles
			 *
			 *
			 * @param array $cctor_img_size .
			 *
			 */
			$cctor_option_css = apply_filters( 'cctor_filter_inline_css', $coupon_css );
		}
		//Add Custom CSS from Options
		if ( cctor_options( 'cctor_custom_css' ) ) {

			$cctor_option_css .= cctor_options( 'cctor_custom_css' );
		}

		if ( $cctor_option_css ) {
			ob_start(); ?>
			<!--  Coupon Style from the Options Page and Filter -->
			<style type='text/css'>
				<?php echo wp_kses_post( $cctor_option_css ); ?>
			</style>
			<?php echo ob_get_clean();
		}
	}

}
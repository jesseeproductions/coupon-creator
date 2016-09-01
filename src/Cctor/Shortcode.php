<?php
//If Direct Access Kill the Script
if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ ) {
	die( 'Access denied.' );
}
/*
* Coupon Creator Shortcode Class
*
*/
class Cctor__Coupon__Shortcode {

	/*
	* Coupon Creator Shortcode
	*/
	public static function core_shortcode( $atts ) {

		//Load Stylesheet for Coupon Creator when Shortcode Called
		if ( ! wp_style_is( 'coupon_creator_css' ) ) {
			wp_enqueue_style( 'coupon_creator_css' );
		}
		/**
		 * Core Coupon Shortcode Starting Hook
		 *
		 * @since 1.90
		 *
		 */
		do_action( 'cctor_shortcode_start' );

		//Coupon ID is the Custom Post ID
		$cctor_atts = shortcode_atts( array(
			"totalcoupons"  => '-1',
			"couponid"      => '',
			"coupon_align"  => 'cctor_alignnone',
			"couponorderby" => 'date',
			"category"      => '',
			"bordertheme"   => '',
			"filterid"      => ''
		), $atts, 'coupon' );

		$filterid     = '';
		$coupon_align = '';

		$filterid     = $cctor_atts['filterid'];
		$coupon_align = $cctor_atts['coupon_align'];

		// Setup Query for Either Single Coupon or a Loop
		$cctor_args = array(
			'p'                     => esc_attr( $cctor_atts['couponid'] ),
			'posts_per_page'        => esc_attr( $cctor_atts['totalcoupons'] ),
			'cctor_coupon_category' => esc_attr( $cctor_atts['category'] ),
			'post_type'             => 'cctor_coupon',
			'post_status'           => 'publish',
			'orderby'               => esc_attr( $cctor_atts['couponorderby'] )
		);

		//Filter for all Shortcodes
		if ( has_filter( 'cctor_shortcode_query_args' ) ) {
			/**
			 * Filter Core ShortCode Query Arguments
			 *
			 *
			 * @param array $cctor_args .
			 *
			 */
			$cctor_args = apply_filters( 'cctor_shortcode_query_args', $cctor_args );
		}

		//Custom Filter ID Set in Shortcode
		if ( $filterid ) {
			if ( has_filter( 'cctor_shortcode_query_args_' . $filterid ) ) {
				/**
				 * Filter Core ShortCode Query Arguments by ID
				 *
				 *
				 * @param array $cctor_args .
				 *
				 */
				$cctor_args = apply_filters( 'cctor_shortcode_query_args_' . $filterid, $cctor_args );
			}
		}

		$coupons = new WP_Query( $cctor_args );

		ob_start();

		/**
		 * Before Core Coupon Shortcode Wrap
		 *
		 * @since 1.90
		 *
		 */
		do_action( 'cctor_before_coupon_wrap' );

		// The Coupon Loop
		while ( $coupons->have_posts() ) {

			$coupons->the_post();

			$coupon_id = $coupons->post->ID;


			if ( class_exists( 'Cctor__Coupon__Pro__Expiration' ) ) {
				$coupon_expiration = new Cctor__Coupon__Pro__Expiration( $coupon_id );
			} else {
				$coupon_expiration = new Cctor__Coupon__Expiration( $coupon_id );
			}
			/**
			 * Before Core Coupon Shortcode Individual Coupon
			 *
			 * @since 1.90
			 *
			 * @param int $coupon_id
			 *
			 */
			do_action( 'cctor_before_coupon', $coupon_id );
			//Check to show the Coupon
			if ( $coupon_expiration->check_expiration() ) {
				/**
				 * Filter Individual Coupon Outer Wrap
				 *
				 *
				 * @param int $coupon_id .
				 * @param string|null $coupon_align .
				 * @param string $cctor_atts['bordertheme'] .
				 *
				 */
				$outer_coupon_wrap = apply_filters( 'cctor_outer_content_wrap', $coupon_id, $coupon_align, $cctor_atts['bordertheme'] );

				echo $outer_coupon_wrap['start_wrap'];

				/**
				 * Before Core Shortcode Individual Coupon Wrap
				 *
				 * @since 1.90
				 *
				 * @param int $coupon_id
				 *
				 */
				do_action( 'cctor_before_coupon_inner_wrap', $coupon_id );

				//Return If Not Passed Expiration Date
				/**
				 * Filter Individual Image Coupon URL
				 *
				 *
				 * @param int $coupon_id
				 * @param string single_coupon (image size)
				 *
				 */
				$couponimage = apply_filters( 'cctor_image_url', $coupon_id, 'single_coupon' );

				if ( $couponimage ) {
					/**
					 * Display Coupon Image Hook
					 *
					 * @since 1.90
					 *
					 * @param int    $coupon_id
					 * @param string $couponimage
					 * @param array  $cctor_atts ['bordertheme']
					 */
					do_action( 'cctor_img_coupon', $coupon_id, $couponimage, $cctor_atts['bordertheme'] );

				} else {
					/**
					 * Filter Individual Coupon Inner Wrap
					 *
					 *
					 * @param int $coupon_id .
					 * @param string|null $coupon_align .
					 * @param string $cctor_atts['bordertheme'] .
					 *
					 */
					$inner_coupon_wrap = apply_filters( 'cctor_inner_content_wrap', $coupon_id, $cctor_atts['bordertheme'] );

					echo $inner_coupon_wrap['start_wrap'];
					/**
					 * Coupon Deal Hook
					 *
					 * @since 1.90
					 *
					 * @param int $coupon_id
					 *
					 */
					do_action( 'cctor_coupon_deal', $coupon_id );
					/**
					 * Coupon Terms Hook
					 *
					 * @since 1.90
					 *
					 * @param int $coupon_id
					 *
					 */
					do_action( 'cctor_coupon_terms', $coupon_id );
					/**
					 * Coupon Expiration Display Hook
					 *
					 * @since 1.90
					 *
					 * @param int    $coupon_id
					 * @param object $coupon_expiration
					 */
					do_action( 'cctor_coupon_expiration', $coupon_id, $coupon_expiration );

					echo $inner_coupon_wrap['end_wrap'];

				}
				/**
				 * Individual Coupon Link
				 *
				 * @since 1.90
				 *
				 * @param int $coupon_id
				 *
				 */
				do_action( 'cctor_coupon_link', $coupon_id );

				echo $outer_coupon_wrap['end_wrap'];

			} else {
				/**
				 * coupon Expired Hook
				 * Only Shows for expired coupon
				 *
				 * @since 1.90
				 *
				 * @param int    $coupon_id
				 * @param object $coupon_expiration
				 */
				do_action( 'cctor_no_show_coupon', $coupon_id, $coupon_expiration );
			}
			/**
			 * After Core Shortcode Wrap
			 *
			 * @since 1.90
			 *
			 * @param int $coupon_id
			 *
			 */
			do_action( 'cctor_after_coupon', $coupon_id );

		} //End While

		/**
		 * End Core Shortcode
		 *
		 * @since 1.90
		 *
		 *
		 */
		do_action( 'cctor_shortcode_end' );

		/* Restore original Post Data */
		wp_reset_postdata();

		// Return Variables
		return ob_get_clean();

	}

}
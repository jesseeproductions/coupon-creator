<?php
/**
 * Coupon Creator Functions
 */

//If Direct Access Kill the Script
if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ ) {
	die( 'Access denied.' );
}

if ( ! class_exists( 'Cctor__Coupon__Main' ) ) {
	return;
}
/**
 * Coupon Type Test
 *
 * Checks type of $postId to determine if it is an Coupon
 *
 * @category Coupons
 *
 * @param int $postId (optional)
 *
 * @return bool true if this post is an Coupon post type
 */
function cctor_is_coupon( $postId = null ) {
	return apply_filters( 'cctor_is_coupon', pngx( 'cctor' )->is_coupon( $postId ), $postId );
}

/**
 * Conditional tag to check if current page is an coupon category page
 *
 * @return bool
 **/
function cctor_is_coupon_category() {
	global $wp_query;
	$cctor_is_coupon_category = ! empty( $wp_query->cctor_is_coupon_category );

	return apply_filters( 'cctor_query_is_coupon_category', $cctor_is_coupon_category );
}

/**
 * Conditional tag to check if current page is displaying coupon query
 *
 * @return bool
 **/
function cctor_is_coupon_query() {
	global $wp_query;
	$cctor_is_coupon_query = ! empty( $wp_query->cctor_is_coupon_query );

	return apply_filters( 'cctor_query_is_coupon_query', $cctor_is_coupon_query );
}

/**
 * Conditional tag to check if current page is displaying coupon taxonomy
 *
 * @return bool
 **/
if ( ! function_exists( 'cctor_is_coupon_taxonomy' ) ) {
	function cctor_is_coupon_taxonomy() {
		global $wp_query;
		$cctor_is_coupon_taxonomy = ! empty( $wp_query->cctor_is_coupon_taxonomy );

		return apply_filters( 'cctor_query_is_coupon_taxonomy', $cctor_is_coupon_taxonomy );
	}
}
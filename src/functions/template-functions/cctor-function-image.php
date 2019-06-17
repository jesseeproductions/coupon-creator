<?php
//If Direct Access Kill the Script
if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ ) {
	die( 'Access denied.' );
}

/*
* Coupon Creator Return Image URL using Meta Field ID for Shortcode
 *
 * @since 1.90
 *
*/
function cctor_get_image_url( $coupon_id, $cctor_img_size = 'full' ) {

	$couponimage_id = get_post_meta( $coupon_id, 'cctor_image', true );
	$couponimage    = wp_get_attachment_image_src( $couponimage_id, $cctor_img_size );
	$couponimage    = isset( $couponimage[0] ) ? $couponimage[0] : '';

	return wp_normalize_path( $couponimage );
}

/*
* Coupon Creator Print Template Image Coupon
 *
 * @since 1.90
 *
*/
function cctor_show_print_img( $coupon_id, $couponimage ) {

	?><img class='cctor_coupon_image' src='<?php echo esc_url( $couponimage ); ?>' alt='<?php echo get_the_title(); ?>' title='<?php echo get_the_title(); ?>'><?php

}
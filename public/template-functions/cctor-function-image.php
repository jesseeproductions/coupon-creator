<?php
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

/*
* Coupon Creator Return Image URL using Meta Field ID for Shortcode
* @version 1.90
*/
function cctor_get_image_url( $coupon_id, $cctor_img_size='full' ) {

	$couponimage_id = get_post_meta($coupon_id, 'cctor_image', true);
	$couponimage = wp_get_attachment_image_src($couponimage_id, $cctor_img_size);
	$couponimage = $couponimage[0];
	
	return $couponimage;
}

/*
* Coupon Creator Print Template Image Coupon
* @version 1.90
*/
function cctor_show_print_img( $coupon_id, $couponimage) {

	?><img class='cctor_coupon_image' src='<?php echo esc_url($couponimage); ?>' alt='' title=''><?php
	
}
<?php
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

/*
* Build Shortcode
* @version 1.90
*/
function cctor_shortcode_functions() {	 

	add_filter('cctor_image_url', 'cctor_get_image_url', 10 , 2);

	add_filter('cctor_outer_content_wrap', 'cctor_return_outer_coupon_wrap', 10 , 3);

	add_action('cctor_img_coupon', 'cctor_show_img_coupon', 10, 3 );

	add_filter('cctor_inner_content_wrap', 'cctor_return_inner_coupon_wrap', 10 , 2);

	add_action('cctor_coupon_deal', 'cctor_show_deal', 10, 1 ); 

	add_action('cctor_coupon_terms', 'cctor_show_terms', 10, 1 ); 

	add_action('cctor_coupon_expiration', 'cctor_show_expiration', 10, 1 ); 

	add_action('cctor_coupon_link', 'cctor_show_link', 10, 1 ); 

	add_action('cctor_no_show_coupon', 'cctor_show_no_coupon_comment', 10, 2 );

	do_action( 'cctor_shortcode_template_functions' );
}
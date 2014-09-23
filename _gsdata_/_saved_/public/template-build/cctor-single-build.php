<?php
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

/*
* Coupon Creator Pro Print Template Functions
* @version 1.00
*/

/*
* Add Content to Print Template
* @version 1.90
*/
function coupon_print_template() {	 

	add_action('coupon_print_meta', 'cctor_print_head_and_meta', 5, 1 ); 

	add_filter('cctor_print_expiration_check', 'cctor_expiration_and_current_date', 10 , 1);

	add_filter('cctor_print_image_url', 'cctor_show_print_img_url', 10 , 1);

	add_filter('cctor_print_outer_content_wrap', 'cctor_return_outer_coupon_wrap', 10 , 1);

	add_action('cctor_print_image_coupon', 'cctor_show_print_img', 10, 1 ); 

	add_filter('cctor_print_inner_content_wrap', 'cctor_return_print_inner_coupon_wrap', 10 , 1);

	add_action('cctor_print_title_coupon', 'cctor_show_title', 10, 1 ); 

	add_action('cctor_print_deal_coupon', 'cctor_show_deal', 10, 1 ); 

	add_action('cctor_print_expiration_coupon', 'cctor_show_expiration', 10, 1 ); 

	add_action('cctor_click_to_print_coupon', 'cctor_show_print_click', 10, 1 ); 

	add_action('cctor_print_no_show_coupon', 'cctor_show_no_coupon_comment', 10, 1 ); 		

	do_action( 'cctor_print_template_functions' );
}		 
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
function cctor_print_template() {	 

	add_action('coupon_print_meta', 'cctor_print_head_and_meta', 5, 1 );

	add_action('coupon_print_meta', 'cctor_print_base_css', 8, 1 );

	add_action('coupon_print_meta', 'cctor_print_stylesheets_and_script', 10, 1 );

	//todo remove as it is unused?
	//add_filter('cctor_print_expiration_check', array( 'CCtor_Expiration_Class', 'is_coupon_before_expiration' ), 10 , 1);

	add_filter('cctor_print_image_url', 'cctor_get_image_url', 10 , 2);

	add_filter('cctor_print_outer_content_wrap', 'cctor_return_print_outer_coupon_wrap', 10 , 1);

	add_action('cctor_print_image_coupon', 'cctor_show_print_img', 10, 2 ); 

	add_filter('cctor_print_inner_content_wrap', 'cctor_return_print_inner_coupon_wrap', 10 , 1);

	add_action('cctor_print_coupon_deal', 'cctor_show_deal', 10, 1 ); 

	add_action('cctor_print_coupon_terms', 'cctor_show_terms', 10, 1 ); 

	add_action('cctor_print_coupon_expiration', 'cctor_show_expiration', 10, 1 ); 

	add_action('cctor_click_to_print_coupon', 'cctor_show_print_click', 10, 1 ); 

	add_action('cctor_print_no_show_coupon', 'cctor_show_no_coupon_comment', 10, 1 ); 		

	do_action( 'cctor_print_template_functions' );
}		 
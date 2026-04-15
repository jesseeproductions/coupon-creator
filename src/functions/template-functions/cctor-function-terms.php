<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/*
* Coupon Creator Print Template Deal
*
*/
function cctor_show_terms($coupon_id) {
	
	$cctor_terms_tags = '';	

	$terms = get_post_meta( $coupon_id, 'cctor_description', true );
	//Apply all the_content filters manually
	$terms = wptexturize( $terms );
	$terms = convert_smilies( $terms );

	//WPAutop
	if ( cctor_options('cctor_wpautop', TRUE , 1) != 1 ) {
		$terms = wpautop( $terms );
	}
	$terms = shortcode_unautop( $terms );
	$terms = prepend_attachment( $terms );
	//Run Shortcodes
	$terms = do_shortcode( $terms );
	
	?><div class="cctor-terms"><?php echo wp_kses_post( $terms ); ?></div><?php

}
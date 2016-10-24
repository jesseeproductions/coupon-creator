<?php
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

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
	
	?><div class="cctor_terms"><?php echo strip_tags( $terms, apply_filters( 'cctor_filter_terms_tags', $cctor_terms_tags ) );  ?></div><?php

}
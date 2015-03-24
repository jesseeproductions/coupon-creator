<?php
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

/*
* Coupon Creator Print Template Deal
* @version 1.90
*/
function cctor_show_terms($coupon_id) {
	
	$cctor_terms_tags = '';	
	
	$terms = apply_filters( 'the_content',get_post_meta( $coupon_id, 'cctor_description', true ) );
	
	?><div class="cctor_terms"><?php echo strip_tags( $terms, 
	apply_filters( 'cctor_filter_terms_tags', $cctor_terms_tags ) );  ?></div><?php

}
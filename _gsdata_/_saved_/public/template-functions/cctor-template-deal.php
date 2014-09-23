<?php
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

/*
* Coupon Creator Print Template Deal
* @version 1.90
*/
function cctor_show_deal($coupon_id) {
	?><div class="cctor_deal"><?php echo get_post_meta($coupon_id, 'cctor_description', true);  ?></div><?php

}
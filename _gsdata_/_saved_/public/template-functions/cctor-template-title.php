<?php
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

/*
* Coupon Creator Print Template Title
* @version 1.90
*/
function cctor_show_title($coupon_id) {
	?><h3 style="background-color:<?php echo get_post_meta($coupon_id, 'cctor_colordiscount', true);  ?>; color:<?php echo get_post_meta($coupon_id, 'cctor_colorheader', true); ?>!important;"> <!--style bg of discount -->
	<?php echo get_post_meta($coupon_id, 'cctor_amount', true);  ?></h3><?php

}
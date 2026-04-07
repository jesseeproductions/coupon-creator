<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/*
* Coupon Creator Print Template Title
* @version 1.90
*/
function cctor_show_deal($coupon_id) {
	?><h3 class="cctor-deal" style="background-color:<?php echo esc_attr(get_post_meta($coupon_id, 'cctor_colordiscount', true));  ?>; color:<?php echo esc_attr(get_post_meta($coupon_id, 'cctor_colorheader', true)); ?>;"><?php echo esc_html(get_post_meta($coupon_id, 'cctor_amount', true));  ?></h3><?php

}
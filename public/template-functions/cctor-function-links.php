<?php
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

/*
* Coupon Creator Image Coupon Link
* @version 1.90
*/
function cctor_show_img_coupon($coupon_id, $couponimage) {
	//Build Click to Print Link for the Image - First Check if Option to Hide is Checked
	if (cctor_options('cctor_hide_print_link') == 0) {

		if (cctor_options('cctor_nofollow_print_link') == 1) {
			$nofollow = "rel='nofollow'";
		}
		//Set Image Link
		?><a class="coupon_link" target='_blank' <?php echo esc_attr($nofollow); ?> href='<?php echo esc_url(get_permalink($coupon_id)); ?>' title='Click to Open in Print View'><img class='cctor_coupon_image' src='<?php echo  esc_url($couponimage); ?>' alt='<?php echo get_the_title($coupon_id); ?>' title='Coupon <?php echo get_the_title($coupon_id); ?>'></a><?php
	} else {
		//No Links for Image Coupon or Click to Print
		?><img class='cctor_coupon_image' src='<?php echo esc_url($couponimage); ?>' alt='<?php echo get_the_title($coupon_id); ?>' title='Coupon <?php echo get_the_title($coupon_id); ?>'><?php
	}	

}
/*
* Coupon Creator Click to Open in Print View Link
* @version 1.90
*/
function cctor_show_link($coupon_id) {
	//Build Click to Print Link For Coupon - First Check if Option to Hide is Checked
	if (cctor_options('cctor_hide_print_link') == 0) {

		if (cctor_options('cctor_nofollow_print_link') == 1) {
			$nofollow = "rel='nofollow'";
		} 
		?><div class='cctor_opencoupon'><a class="print-link"<?php echo esc_attr($nofollow); ?> href='<?php echo esc_url(get_permalink($coupon_id)); ?>' onclick='window.open(this.href);return false;'><?php echo __('Click to Open in Print View','coupon_creator'); ?></a></div><!--end .opencoupon --><?php
		
	 } else {
		?><div class='cctor_opencoupon'></div><?php
	 }	
}	
/*
* Coupon Creator Print Template Click to Print
* @version 1.90
*/
function cctor_show_print_click($coupon_id) {
	?>
		<div class="cctor_opencoupon"> <!-- We Need a Click to Print Button -->
			<a class="print-link" href="javascript:window.print();" rel="nofollow"><?php echo __('Click to Print', 'coupon_creator'); ?></a>

		</div> <!--end .opencoupon -->
		<?php

}
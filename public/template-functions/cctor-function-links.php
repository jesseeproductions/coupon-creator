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
	
	$nofollow = cctor_options('cctor_nofollow_print_link', TRUE, 1) == 1 ? 'rel="nofollow"' : '';
	$cctor_onclick = !defined( 'CCTOR_PREVENT_OPEN_IN_NEW_TAB' ) || !CCTOR_PREVENT_OPEN_IN_NEW_TAB ? "window.open(this.href);return false;" : '';
	
	if (cctor_options('cctor_hide_print_link') == 0) {

		//Set Image Link
		?><a class="coupon_link" onclick='<?php echo esc_js( $cctor_onclick ); ?>' <?php echo $nofollow; ?> href='<?php echo esc_url( get_permalink( $coupon_id )) ; ?>' title='<?php echo __( 'Click to Open in Print View', 'coupon-creator' ); ?>'><img class='cctor_coupon_image' src='<?php echo  esc_url( $couponimage ); ?>' alt='<?php echo get_the_title($coupon_id); ?>' title='<?php echo __( 'Coupon', 'coupon-creator' ); ?> <?php echo get_the_title( $coupon_id ); ?>'></a><?php
	} else {
		//No Links for Image Coupon or Click to Print
		?><img class='cctor_coupon_image' src='<?php echo $couponimage; ?>' alt='<?php echo get_the_title( $coupon_id ); ?>' title='<?php echo __( 'Coupon', 'coupon-creator' ); ?> <?php echo get_the_title( $coupon_id ); ?>'><?php
	}

}
/*
* Coupon Creator Click to Open in Print View Link
* @version 1.90
*/
function cctor_show_link($coupon_id) {
	
	$nofollow = cctor_options('cctor_nofollow_print_link', TRUE, 1) == 1 ? 'rel="nofollow"' : '';

	$cctor_onclick = !defined( 'CCTOR_PREVENT_OPEN_IN_NEW_TAB' ) || !CCTOR_PREVENT_OPEN_IN_NEW_TAB ? "window.open(this.href);return false;" : '';

	//Build Click to Print Link For Coupon - First Check if Option to Hide is Checked
	if (cctor_options('cctor_hide_print_link') == 0) {

		?><div class='cctor_opencoupon'><a class="print-link"<?php echo $nofollow; ?> href='<?php echo esc_url( get_permalink( $coupon_id ) ); ?>' onclick='<?php echo esc_js( $cctor_onclick ); ?>' ><?php echo __('Click to Open in Print View','coupon-creator'); ?></a></div><!--end .opencoupon --><?php

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
			<a class="print-link" href="javascript:window.print();" rel="nofollow"><?php echo __('Click to Print', 'coupon-creator'); ?></a>

		</div> <!--end .opencoupon -->
		<?php

}
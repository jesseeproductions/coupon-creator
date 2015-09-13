<?php
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

/*
* Coupon Creator Check if Coupon Should Show
* @version 1.90
*/
function cctor_expiration_check($coupon_id) {
		
		//Ignore Expiration Value
		$ignore_expiration = get_post_meta($coupon_id, 'cctor_ignore_expiration', true);
		
		//Return If Not Passed Expiration Date
		$expiration = cctor_expiration_and_current_date($coupon_id);
		
		//Enable Filter to stop coupon from showing
		$show_coupon_check = false;
		
		$show_coupon_check = apply_filters('cctor_show_coupon_check', $show_coupon_check, $coupon_id);


		if (($expiration || $ignore_expiration == 1) && !$show_coupon_check) {

			return true;
			
		}	else {

			return false;
			
		}
}	

/*
* Coupon Creator Return Expiration Date and Current Date
* @version 1.90
*/
function cctor_expiration_and_current_date($coupon_id) { 

	//Coupon Expiration Date
	$expirationco = get_post_meta($coupon_id, 'cctor_expiration', true);
	$expiration['expiration'] = strtotime($expirationco);
	
	//Blog Time According to WordPress
	$cc_blogtime = current_time('mysql');
	list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = preg_split( '([^0-9])', $cc_blogtime ); 
	$expiration['today'] = strtotime($today_month."/".$today_day."/". $today_year);
	
	if ($expiration['expiration'] >= $expiration['today']) {
		return true;
	} else {
		return false;
	}
}
	
/*
* Coupon Creator Print Template Expiration
* @version 1.90
*/
function cctor_show_expiration($coupon_id) {
	//Coupon Expiration Date
	$expirationco = get_post_meta($coupon_id, 'cctor_expiration', true);
	
	$cc_expiration_date = strtotime($expirationco);
	
	if ($expirationco) { // Only Display Expiration if Date
		$daymonth_date_format = get_post_meta($coupon_id, 'cctor_date_format', true); //Date Format
		
		if ($daymonth_date_format == 1 ) { //Change to Day - Month Style
		$expirationco = date("d/m/Y", $cc_expiration_date);
		}	?>
	
	<div class="cctor_expiration core"><?php echo __('Expires on:', 'coupon-creator'); ?>&nbsp;<?php echo esc_html($expirationco); ?></div>
	
	<?php }
}	
/*
* Coupon Creator Print Template No Show Coupon
* @version 1.90
*/
function cctor_show_no_coupon_comment($coupon_id) {
	//Coupon Expiration Date
	$expirationco = get_post_meta($coupon_id, 'cctor_expiration', true);
	
	?><!-- Coupon "<?php echo get_the_title($coupon_id);?>" Has Expired on <?php echo esc_html($expirationco); ?>--><?php

}	
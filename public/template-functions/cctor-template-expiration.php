<?php
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

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
	
	if ($expirationco) { // Only Display Expiration if Date
		$daymonth_date_format = get_post_meta($coupon_id, 'cctor_date_format', true); //Date Format
		
		if ($daymonth_date_format == 1 ) { //Change to Day - Month Style
		$expirationco = date("d/m/Y", $cc_expiration_date);
		}	?>
	
	<div class="cctor_expiration"><?php _e('Expires on:', 'coupon_creator'); ?>&nbsp;<?php echo $expirationco; ?></div>
	
	<?php }
}	
/*
* Coupon Creator Print Template No Show Coupon
* @version 1.90
*/
function cctor_show_no_coupon_comment($coupon_id) {

	?><!-- Coupon "<?php echo get_the_title($coupon_id);?>" Has Expired --><?php

}	
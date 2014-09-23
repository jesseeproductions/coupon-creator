<?php
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

/*
* Coupon Creator Outer Wrap
* @version 1.90
*/
function cctor_return_outer_coupon_wrap($coupon_id, $coupon_align) { 
	
	$outer_coupon_wrap = array();
	$outer_coupon_wrap['start_wrap'] = '<!--start coupon container -->
		<div id="coupon-creator-'. $coupon_id.'" class="cctor_coupon_container '.$coupon_align.'">';
	
	$outer_coupon_wrap['end_wrap'] = '</div> <!--end #cctor_coupon_container -->';
							
	return $outer_coupon_wrap;
}			
/*
* Coupon Creator Inner Wrap
* @version 1.90
*/
function cctor_return_inner_coupon_wrap($coupon_id) { 

	$coupon_inner_content_wrap = array();
	
	//Build Click to Print Link For Coupon - First Check if Option to Hide is Checked
	if (coupon_options('cctor_hide_print_link') == 0) {
		
		if (coupon_options('cctor_nofollow_print_link') == 1) {
			$nofollow = "rel='nofollow'";
		} 
			
		$coupon_inner_content_wrap['start_wrap'] = '<a '.$nofollow.' href="'. get_permalink($coupon_id).'" onclick="window.open(this.href);return false;">
			<div class="cctor_coupon">
			<div class="cctor_coupon_content" style="border-color:'. get_post_meta($coupon_id, 'cctor_bordercolor', true).'">';
	
		$coupon_inner_content_wrap['end_wrap'] = '</div> <!--end .cctor_coupon_content -->
												</div> <!--end .cctor_coupon --></a>';

	 } else {
			$coupon_inner_content_wrap['start_wrap'] = '<div class="cctor_coupon">
			<div class="cctor_coupon_content" style="border-color:'. get_post_meta($coupon_id, 'cctor_bordercolor', true).'">';
	
			$coupon_inner_content_wrap['end_wrap'] = '</div> <!--end .cctor_coupon_content -->
												</div> <!--end .cctor_coupon -->';
	 }
	 
	return $coupon_inner_content_wrap;
}	

/*
*  Coupon Creator Print Inner Wrap
*  @version 1.90
*/
function cctor_return_print_inner_coupon_wrap($coupon_id) { 
	
	$coupon_inner_content_wrap = array();
	$coupon_inner_content_wrap['start_wrap'] = '<div class="cctor_coupon">
	<div class="cctor_coupon_content" style="border-color:'. get_post_meta($coupon_id, 'cctor_bordercolor', true).'">';
	
	$coupon_inner_content_wrap['end_wrap'] = '</div> <!--end .cctor_coupon_content -->
							</div> <!--end .cctor_coupon -->';
							
	return $coupon_inner_content_wrap;
}	


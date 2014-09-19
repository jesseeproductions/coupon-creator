<?php
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

/*
* Coupon Creator Shortcode Class
* @version 1.90
*/
class Coupon_Creator_Shortcode {

	/*
	* Register Coupon Creator Shortcode
	* @version 1.90
	*/
	public static function cctor_allcoupons_shortcode($atts) {
	   //Load Stylesheet for Coupon Creator when Shortcode Called
	   if( !wp_style_is( 'coupon_creator_css' ) ) {
		 wp_enqueue_style('coupon_creator_css');
	   }	 
	   
	   //Coupon ID is the Custom Post ID
	   extract(shortcode_atts(array(
		"totalcoupons" => '-1',
		"couponid" => '',
		"coupon_align" => 'cctor_alignnone',
		"couponorderby" => 'date',
		"category" => '',
		"filterid" => ''
		), $atts ) );

		// Setup Query for Either Single Coupon or a Loop
			$couponargs = array(
			'p' => $couponid,
			'posts_per_page' => $totalcoupons,
			'cctor_coupon_category' => $category,
			'post_type' => 'cctor_coupon',
			'post_status' => 'publish',
			'orderby' => $couponorderby
		);
		
		//Filter for all Shortcodes
		if(has_filter('cctor_shortcode_query_args')) {
			$couponargs = apply_filters( 'cctor_shortcode_query_args', $couponargs );
		}
		
		//Custom Filter ID Set in Shortcode
		if(has_filter('cctor_shortcode_query_args_'.$filterid)) {
			$couponargs = apply_filters( 'cctor_shortcode_query_args_'.$filterid, $couponargs );
		}		
		
		$coupons = new WP_Query($couponargs);
		
		ob_start();
		
		do_action( 'cctor_before_coupon' ); 
		
		// The Coupon Loop
		while ($coupons->have_posts()) {

			$coupons->the_post();
						
			$coupon_id = $coupons->post->ID;
			
				//Ignore Expiration Value
				$ignore_expiration = get_post_meta($coupon_id, 'cctor_ignore_expiration', true);
				
				//Return If Not Passed Expiration Date
				$expiration = apply_filters( 'cctor_expiration_check' , $coupon_id  );
				
				if ($expiration || $ignore_expiration == 1 ) {
				
					$outer_coupon_wrap  = apply_filters( 'cctor_outer_content_wrap' , $coupon_id , $coupon_align  ); 
							
					echo $outer_coupon_wrap['start_wrap'];	
				
						//Return If Not Passed Expiration Date
						$couponimage = apply_filters( 'cctor_image_url' , $coupon_id  );
						
						if ($couponimage) {
						
							do_action( 'cctor_img_coupon' , $coupon_id , $couponimage ); 
						
						} else { 
						
							$inner_coupon_wrap  = apply_filters( 'cctor_inner_content_wrap' , $coupon_id  ); 
							
							echo $inner_coupon_wrap['start_wrap'];

								do_action( 'cctor_title_coupon' , $coupon_id ); 
								
								do_action( 'cctor_deal_coupon' , $coupon_id ); 
								
								do_action( 'cctor_expiration_coupon' , $coupon_id ); 
							
							echo $inner_coupon_wrap['end_wrap'];
						
						}
					
						do_action( 'cctor_coupon_link' , $coupon_id ); 
					
					echo $outer_coupon_wrap['end_wrap'];	
					
				} else {
					//No Coupon Will Show So Print HTML Comment
					do_action( 'cctor_no_show_coupon' , $coupon_id );
				}
			} //End While
			
			do_action( 'cctor_after_coupon' ); 
			
			/* Restore original Post Data */
			wp_reset_postdata();

			// Return Variables
			return ob_get_clean();	 

	} 

	/***************************************************************************/	
	
	/*
	* Add Content to Shortcode
	* @version 1.90
	*/
	public static function coupon_shortcode_functions() {	 
	
		add_filter('cctor_expiration_check', 'cctor_expiration_and_current_date', 10 , 1);

		add_filter('cctor_image_url', 'cctor_return_image_url', 10 , 1);

		add_filter('cctor_outer_content_wrap', 'cctor_return_outer_coupon_wrap', 10 , 2);

		add_action('cctor_img_coupon', 'cctor_show_img_coupon', 10, 2 ); 

		add_filter('cctor_inner_content_wrap', 'cctor_return_inner_coupon_wrap', 10 , 1);

		add_action('cctor_title_coupon', 'cctor_show_title', 10, 1 ); 

		add_action('cctor_deal_coupon', 'cctor_show_deal', 10, 1 ); 

		add_action('cctor_expiration_coupon', 'cctor_show_expiration', 10, 1 ); 

		add_action('cctor_coupon_link', 'cctor_show_link', 10, 1 ); 

		add_action('cctor_no_show_coupon', 'cctor_show_no_coupon_comment', 10, 1 ); 		

		do_action( 'cctor_shortcode_template_functions' );
	}		
}
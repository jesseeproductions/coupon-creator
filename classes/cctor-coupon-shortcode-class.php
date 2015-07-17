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
	   
		do_action( 'cctor_shortcode_start' );

	   //Coupon ID is the Custom Post ID
	   $cctor_atts = shortcode_atts(array(
		"totalcoupons" => '-1',
		"couponid" => '',
		"coupon_align" => 'cctor_alignnone',
		"couponorderby" => 'date',
		"category" => '',
		"border-theme" => '',
		"filterid" => ''
		), $atts, 'coupon' );
		
		$filterid = '';
		$coupon_align = '';
		
		$filterid = esc_attr($cctor_atts['filterid']);		
		$coupon_align = esc_attr($cctor_atts['coupon_align']);
		
		// Setup Query for Either Single Coupon or a Loop
		$cctor_args = array(
			'p' => esc_attr($cctor_atts['couponid']),
			'posts_per_page' =>  esc_attr($cctor_atts['totalcoupons']),
			'cctor_coupon_category' =>  esc_attr($cctor_atts['category']),
			'post_type' => 'cctor_coupon',
			'post_status' => 'publish',
			'orderby' =>  esc_attr($cctor_atts['couponorderby'])
		);

		//Filter for all Shortcodes
		if(has_filter('cctor_shortcode_query_args')) {
			$cctor_args = apply_filters( 'cctor_shortcode_query_args', $cctor_args );
		}
		
		//Custom Filter ID Set in Shortcode
		if ($filterid) {
			if(has_filter('cctor_shortcode_query_args_'.$filterid)) {
				$cctor_args = apply_filters( 'cctor_shortcode_query_args_'.$filterid, $cctor_args );
			}		
		}

		$coupons = new WP_Query($cctor_args);
		
		ob_start();

		do_action( 'cctor_before_coupon_wrap' ); 

		// The Coupon Loop
		while ($coupons->have_posts()) {

			$coupons->the_post();
						
			$coupon_id = $coupons->post->ID;


			do_action( 'cctor_before_coupon' , $coupon_id ); 
				//Check to show the Coupon
				if ( cctor_expiration_check( $coupon_id ) ) {
					
					$outer_coupon_wrap  = apply_filters( 'cctor_outer_content_wrap' , $coupon_id , $coupon_align, $cctor_atts['border-theme'] );
							
					echo $outer_coupon_wrap['start_wrap'];					

						do_action( 'cctor_before_coupon_inner_wrap' , $coupon_id );

						//Return If Not Passed Expiration Date
						$couponimage = apply_filters( 'cctor_image_url' , $coupon_id, 'single_coupon'  );

						if ($couponimage) {
						
							do_action( 'cctor_img_coupon' , $coupon_id , $couponimage ); 
						
						} else { 

							$inner_coupon_wrap  = apply_filters( 'cctor_inner_content_wrap' , $coupon_id, $cctor_atts['border-theme']  );

							echo  $inner_coupon_wrap['start_wrap'];

								do_action( 'cctor_coupon_deal' , $coupon_id ); 

								do_action( 'cctor_coupon_terms' , $coupon_id );

								do_action( 'cctor_coupon_expiration' , $coupon_id ); 

							echo $inner_coupon_wrap['end_wrap'];

						}

						do_action( 'cctor_coupon_link' , $coupon_id ); 

					echo $outer_coupon_wrap['end_wrap'];	
					
				} else {
					//No Coupon Will Show So Print HTML Comment
					do_action( 'cctor_no_show_coupon' , $coupon_id );
				}

				do_action( 'cctor_after_coupon' , $coupon_id );

			} //End While


			do_action( 'cctor_shortcode_end' ); 
			
			/* Restore original Post Data */
			wp_reset_postdata();

			// Return Variables
			return ob_get_clean();

	}
	
}
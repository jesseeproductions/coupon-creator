<?php
/*
* Single Template For Custom Post Type Coupon
* Custom Template with WordPress loaded no header, footer, or theme styling
*/
	//Do Not Cache Print Templates
	if ( ! defined( 'DONOTCACHEPAGE' ) )
        define( 'DONOTCACHEPAGE', true);
	
	$coupon_id = get_the_ID();
	
	do_action( 'cctor_action_print_template', $coupon_id );

	$coupon_expiration = new CCtor_Expiration_Class( $coupon_id );

?>
<!DOCTYPE html>
<html>
	<head>
		<?php

		//do_action( 'wp_head' );

		//Coupon Creator Print Template Meta Hook
		do_action( 'coupon_print_meta' );

		//Coupon Creator Print Template Head Hook-->
		do_action( 'coupon_print_head' );

		?>
	</head>
<body class="print_coupon">
<?php
if ( have_posts() ) while ( have_posts() ) : the_post();

	$coupon_id = get_the_ID();

	do_action( 'cctor_print_before_coupon', $coupon_id );

		//Check to show the Coupon
	if ( $coupon_expiration->check_expiration() ) {

			$outer_print_coupon_wrap  = apply_filters( 'cctor_print_outer_content_wrap' , $coupon_id  );

			echo $outer_print_coupon_wrap['start_wrap'];

				//Return If Not Passed Expiration Date
				$couponimage = apply_filters( 'cctor_print_image_url' , $coupon_id, 'print_coupon'  );

				if ($couponimage) {

					do_action( 'cctor_print_image_coupon' ,  $coupon_id, $couponimage );

				} else {

					$inner_print_coupon_wrap  = apply_filters( 'cctor_print_inner_content_wrap' , $coupon_id  );

					echo  $inner_print_coupon_wrap['start_wrap'];

						do_action( 'cctor_print_coupon_deal' , $coupon_id );

						do_action( 'cctor_print_coupon_terms' , $coupon_id );

						do_action( 'cctor_print_coupon_expiration' , $coupon_expiration );

					echo $inner_print_coupon_wrap['end_wrap'];

				}

				do_action( 'cctor_click_to_print_coupon' , $coupon_id );

			echo $outer_print_coupon_wrap['end_wrap'];

		} else {
			//No Coupon Will Show So Print HTML Comment
			do_action( 'cctor_print_no_show_coupon' , $coupon_id, $coupon_expiration );
		}

	do_action( 'cctor_print_after_coupon' , $coupon_id );

endwhile; // end the coupon creator loop

do_action( 'coupon_footer' );

//do_action( 'wp_footer' );
?>
</body>
</html>
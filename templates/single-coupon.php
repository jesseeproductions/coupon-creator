<?php
/*
Single Template For Custom Post Type Coupon - Permalink displays the Coupon with Print Button
Custom Template with Basic WordPress loaded no header footer, etc
*/
?>
<!DOCTYPE html>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width">

	<?php
		//Add nofollow and noindex if option checked
		if (coupon_options('cctor_nofollow_print_template') == 1) { ?>
		<meta name="robots" content="noindex,nofollow"/>
	<?php }?>

	<title><?php echo get_the_title(); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />

	<?php $coupon_url_dir = plugins_url(); // Declare Plugin Directory ?>

	<!--Make Background White to Print Coupon -->
	<style type='text/css'>
		body {
			background-color: #fff;
			background-image: none;
		}
	</style>
	<!--Load StyleSheet for Coupons -->
	<?php
		//Get File Time of CSS File so that CSS Updates when changes made
		$cctor_file = plugin_dir_path( __FILE__ );
		$cctor_file = str_replace("/templates/", "/", $cctor_file);
		$cctor_file = $cctor_file.'css/cctor_coupon.css';
		$cctor_style = @filemtime($cctor_file);

		if($cctor_style == NULL)
		    $cctor_style = @filemtime(utf8_decode($cctor_file));

	?>
	<link rel='stylesheet' id='coupon-style-css'  href='<?php echo $coupon_url_dir; ?>/coupon-creator/css/cctor_coupon.css?<?php echo $cctor_style; ?>' type='text/css' media='all' />

	<?php
		//Print Template Hook
		do_action( 'coupon_print_head' );
	?>
</head>


<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	<?php //Check Expiration if past date then exit
		$expirationco = get_post_meta($post->ID, 'cctor_expiration', true); //Expiration Date
		$cc_blogtime = current_time('mysql'); //Blog Time According to WordPress
		list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = preg_split( '([^0-9])', $cc_blogtime ); //List out the Different Fields to Use
		$cc_today = strtotime($today_month."/".$today_day."/". $today_year); //Combine the data we need to compare
		$cc_expiration_date = strtotime($expirationco); //php fun and we are ready to compare
		$ignore_expiration = get_post_meta($post->ID, 'cctor_ignore_expiration', true); //get the ignore expiration checkbox value
		$daymonth_date_format = get_post_meta($post->ID, 'cctor_date_format', true); //get the ignore expiration checkbox value

		if ($cc_expiration_date >= $cc_today || $ignore_expiration == 1 ) {  // Display coupon if expiration date is in future or if ignore box checked

			$couponimage_id = get_post_meta($post->ID, 'cctor_image', true); // Get Image Meta
			$couponimage = wp_get_attachment_image_src($couponimage_id, 'print_coupon'); //Get Right Size Image for Print Template
			$couponimage = $couponimage[0]; //Make sure we only have first attached image not that there should be any others
	?>

		<div class="cctor_coupon_container print_coupon"> <!--start coupon container -->

			<?php if ($couponimage) { //if there is an image only display that and forget the rest ?>

				<img class='cctor_coupon_image' src='<?php echo $couponimage; ?>' alt='' title=''>

			<?php } else { //No Image so lets create a coupon ?>
				<div class="cctor_coupon">
					<div class="cctor_coupon_content" style="border-color:<?php echo get_post_meta($post->ID, 'cctor_bordercolor', true); ?>!important;"> <!--style border -->

						<h3 style="background-color:<?php echo get_post_meta($post->ID, 'cctor_colordiscount', true);  ?>!important; color:<?php echo get_post_meta($post->ID, 'cctor_colorheader', true); ?>!important;"> <!--style bg of discount -->
						<?php echo get_post_meta($post->ID, 'cctor_amount', true);  ?></h3>

						<div class="cctor_deal"><?php echo get_post_meta($post->ID, 'cctor_description', true);  ?></div>
						<?php if ($expirationco) { // Only Display Expiration if Date
						if ($daymonth_date_format == 1 ) { //Change to Day - Month Style
						$expirationco = date("d-m-Y", $cc_expiration_date);
						}	?>


						<div class="cctor_expiration"><?php _e('Expires on:', 'coupon_creator'); ?>&nbsp;<?php echo $expirationco; ?></div>


						<?php } //end if expiration ?>
					</div> <!--end .cctor_coupon_content -->
				</div> <!--end .cctor_coupon -->

			<?php } // End the Else ?>

			<div class="cctor_opencoupon"> <!-- We Need a Click to Print Button -->
				<a href="javascript:window.print();" rel="nofollow"><?php _e('Click to Print', 'coupon_creator'); ?></a>

			</div> <!--end .opencoupon -->
		</div> <!--end #cctor_coupon_container -->

	<?php } // End the If Expiration Date?>

<?php endwhile; // end the coupon creator loop ?>
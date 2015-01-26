<?php
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );
/*
* Coupon Creator Print Template Head
* @version 1.90
*/
function cctor_print_head_and_meta() { ?>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width">
	<?php
		//Add nofollow and noindex if option checked
		if (cctor_options('cctor_nofollow_print_template') == 1) { ?>
		<meta name="robots" content="noindex,nofollow"/>
	<?php }?>
	<title><?php echo get_the_title(); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />

	<!--Make Background White to Print Coupon -->
	<style type='text/css'>
		body {
			background-color: #fff;
			background-image: none;
		}
	</style>
	
	<!--Load StyleSheet for Coupons -->
	<?php
		$coupon_url_dir = plugins_url(); // Declare Plugin Directory
		
		//Get File Time of CSS File so that CSS Updates when changes made
		$cctor_file = plugin_dir_path( __FILE__ );
		$cctor_file = str_replace("/templates/", "/", $cctor_file);
		$cctor_file = $cctor_file.'css/cctor_coupon.css';
		$cctor_style = @filemtime($cctor_file);

		if($cctor_style == NULL)
			$cctor_style = @filemtime(utf8_decode($cctor_file));
			

	?>
	<link rel='stylesheet' id='coupon-style-css'  href='<?php echo esc_url($coupon_url_dir); ?>/coupon-creator/css/cctor_coupon.css?<?php echo esc_attr($cctor_style); ?>' type='text/css' media='all' />
	
	<!--Load jQuery for Counter from WordPress Install -->
	<script type='text/javascript' src='/wp-includes/js/jquery/jquery.js?ver=1.11.1'></script>
	<link rel='stylesheet' id='cctor_colorbox_css-css'  href='http://coupon.jesseeproductions.com/wp-content/plugins/coupon-creator/admin/colorbox/colorbox.css?ver=1422280407' type='text/css' media='all' />
	<script type='text/javascript' src='http://coupon.jesseeproductions.com/wp-content/plugins/coupon-creator/admin/colorbox/jquery.colorbox-min.js?ver=1422280277'></script>
	
<?php 
}
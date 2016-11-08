<?php
//If Direct Access Kill the Script
if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ ) {
	die( 'Access denied.' );
}
/*
* Coupon Creator Print Template Head
* @version 1.90
*/
function cctor_print_head_and_meta() { ?>
	<meta charset="<?php bloginfo( 'charset' ); ?>"/>
	<meta name="viewport" content="width=device-width">
	<?php
	//Add nofollow and noindex if option checked
	if ( cctor_options( 'cctor_nofollow_print_template', true, 1 ) == 1 ) { ?>
		<meta name="robots" content="noindex,nofollow"/>
	<?php } ?>
	<title><?php echo get_the_title(); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11"/>

	<!--Make Background White to Print Coupon -->
	<style type='text/css'>
		body {
			background-color: #fff;
			background-image: none;
			margin: 0;
		}
	</style>

	<?php
}

/*
* Coupon Creator Print Template Head
* @version 2.1
*/
function cctor_print_base_css() {

	if ( cctor_options( 'cctor_print_base_css' ) != 1 ) { ?>
		<!--Default Styling -->
		<style type='text/css'>
			/* ## Typographical Elements
			--------------------------------------------- */
			body {
				font-family: Georgia, serif;
				font-size: 16px;
				font-weight: 300;
				line-height: 1.625;
			}

			p {
				margin: 0 0 15px;
				padding: 0;
			}

			ol,
			ul {
				margin: 0;
				padding: 0;
			}

			b,
			strong {
				font-weight: 700;
			}

			blockquote,
			cite,
			em,
			i {
				font-style: italic;
			}

			blockquote {
				margin: 40px;
			}

			/* ## Headings
			--------------------------------------------- */
			h1,
			h2,
			h3,
			h4,
			h5,
			h6 {
				color: #000;
				font-family: Arial, Helvetica, sans-serif;
				font-weight: 400;
				line-height: 1.2;
				margin: 0 0 10px;
			}

			h1 {
				font-size: 36px;
			}

			h2 {
				font-size: 30px;
			}

			h3 {
				font-size: 24px;
			}

			h4 {
				font-size: 20px;
			}

			h5 {
				font-size: 18px;
			}

			h6 {
				font-size: 16px;
			}
		</style><?php
	}
}

/*
* Coupon Creator Print Stylesheets and Script
* @version 1.90
*/
function cctor_print_stylesheets_and_script() { ?>

	<!--Load StyleSheet for Coupons -->
	<?php
	$coupon_url_dir = plugins_url(); // Declare Plugin Directory

	//Get File Time of CSS File so that CSS Updater when changes made
	$cctor_file  = wp_normalize_path( plugin_dir_path( __FILE__ ) );
	$cctor_file  = str_replace( "/functions/template-functions/", "/", $cctor_file );
	$cctor_file  = $cctor_file . 'resources/css/coupon.css';
	$cctor_style = @filemtime( $cctor_file );

	if ( $cctor_style == null ) {
		$cctor_style = @filemtime( utf8_decode( $cctor_file ) );
	}

	?>
	<link rel='stylesheet' id='coupon-style-css'
	      href='<?php echo esc_url( $coupon_url_dir ); ?>/coupon-creator/src/resources/css/coupon.css?<?php echo esc_attr( $cctor_style ); ?>'
	      type='text/css' media='all'/>

	<!--Load jQuery for Counter from WordPress Install -->
	<script type='text/javascript' src='<?php echo site_url(); ?>/wp-includes/js/jquery/jquery.js?ver=1.12.3'></script>
	<?php
}
<?php
/*
Plugin Name: Coupon Creator
Plugin URI: http://jesseeproductions.com/coupon_creator/
Version: 1.81

Description: This plugin creates a custom post type for coupons with a shortcode to display it on website and a single view template for printing.

Author: Brian Jessee
Author URI: http://jesseeproductions.com

Text Domain: coupon_creator
Domain Path: /languages/

License: GPL2

*/
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

/*
* Coupon Creator Minimum Requirements
* @version 1.80
*/
if (!defined('CCTOR_PATH'))				define( 'CCTOR_PATH',	plugin_dir_path( __FILE__ ));
if (!defined('CCTOR_URL'))				define( 'CCTOR_URL',	plugin_dir_url( __FILE__ ));
if (!defined('CCTOR_MIN_PHP_VERSION'))	define( 'CCTOR_MIN_PHP_VERSION',	'5.2');
if (!defined('CCTOR_MIN_WP_VERSION'))	define( 'CCTOR_MIN_WP_VERSION',		'3.6');
if (!defined('CCTOR_VERSION_KEY')) 		define( 'CCTOR_VERSION_KEY', 	'cctor_coupon_version');
if (!defined('CCTOR_VERSION_NUM'))  	define( 'CCTOR_VERSION_NUM', 	'1.81');

/*
* Check Requirements for WordPress and PHP
* @version 1.70
*/
function cctor_requirements() {
	global $wp_version;

	if( version_compare( PHP_VERSION, CCTOR_MIN_PHP_VERSION, '<' ) )
		return false;

	if( version_compare( $wp_version, CCTOR_MIN_WP_VERSION, '<' ) )
		return false;

	return true;
}
/*
* Print Error for Requirements check on WordPress and PHP
* @version 1.70
*/
function cctor_error_requirements() {
	global $wp_version;
	$class = 'error';
	ob_start(); ?>
    <div class="error">

		<?php if( version_compare( PHP_VERSION, CCTOR_MIN_PHP_VERSION, '<' ) ) { ?>
			<p><?php _e( 'Coupon Creator Requires PHP version: '.CCTOR_MIN_PHP_VERSION . ' You currently have PHP version: '.PHP_VERSION.'', 'coupon_creator' ); ?></p>
		<?php } ?>

		<?php if( version_compare( $wp_version, CCTOR_MIN_WP_VERSION, '<' ) ) { ?>
			<p><?php _e( 'Coupon Creator Requires WordPress version: '.CCTOR_MIN_WP_VERSION . ' You currently have WordPress version: '.$wp_version.'', 'coupon_creator' ); ?></p>
		<?php } ?>

    </div>
<?php echo ob_get_clean();
}

// Check requirements and load files if met
if	( cctor_requirements() ) {

		// Main Class
		require_once( dirname( __FILE__ ) . '/classes/cctor-coupon-class.php' );
		//Admin Class
		require_once( dirname( __FILE__ ) . '/admin/cctor-admin-class.php' );

		//Coupon Creator Start!
		Coupon_Creator_Plugin::bootstrap( __FILE__ );
		
		register_activation_hook( __FILE__, array('Coupon_Creator_Plugin', 'activate') );
		register_deactivation_hook(  __FILE__, array( 'Coupon_Creator_Plugin', 'deactivate' ) );

		//Coupon Options echo coupon_options('cctor_coupon_base');
		function coupon_options( $option ) {
			$options = get_option( 'coupon_creator_options' );

			if ( isset( $options[$option] ) )
				return $options[$option];
			else
				return false;
		}

} else {

	add_action( 'admin_notices', 'cctor_error_requirements' );

}
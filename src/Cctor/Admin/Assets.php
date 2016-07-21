<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


/**
 * Load Front End Assets
 *
 *
 */
class Cctor__Coupon__Admin__Assets {

	/*
	* Enqueue Coupon Creator Assets
	*/
	public static function load_assets() {

		$screen = get_current_screen();

		if ( 'cctor_coupon' == $screen->id ) {

			//Styles
			$cctor_meta_css = Cctor__Coupon__Main::instance()->resource_path . 'css/admin-style.css';
			wp_enqueue_style( 'coupon-admin-style', Cctor__Coupon__Main::instance()->resource_url . 'css/admin-style.css', array( 'pngx-admin' ), filemtime( $cctor_meta_css ) );

			//Style or WP Color Picker
			wp_enqueue_style( 'wp-color-picker' );
			//Image Upload CSS
			wp_enqueue_style( 'thickbox' );

			//jQuery UI
			global $wp_scripts;
			$jquery_version = isset( $wp_scripts->registered['jquery-ui-core']->ver ) ? $wp_scripts->registered['jquery-ui-core']->ver : '1.12.3';
			wp_enqueue_style( 'jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/' . $jquery_version . '/themes/smoothness/jquery-ui.css' );

			//Media Manager from 3.5
			wp_enqueue_media();

			//Script for WP Color Picker
			wp_enqueue_script( 'wp-color-picker' );
			$cctor_coupon_meta_js = Cctor__Coupon__Main::instance()->resource_path . 'js/cctor_coupon_meta.js';
			wp_enqueue_script( 'cctor_coupon_meta_js', Cctor__Coupon__Main::instance()->resource_url . 'js/cctor_coupon_meta.js', array(
				'jquery',
				'media-upload',
				'thickbox',
				'farbtastic',
				'pngx-admin'
			), filemtime( $cctor_coupon_meta_js ), true );

			//Localize Pro Meta Script
			wp_localize_script( 'cctor_coupon_meta_js', 'cctor_meta_js', array(
				'cctor_disable_content_msg' => __( ' Content Fields are disabled when using an Image Coupon', 'coupon_creator_pro' ),
				'cctor_disable_style_msg'   => __( ' Style Fields are disabled when using an Image Coupon', 'coupon_creator_pro' )
			) );


			//Script for Datepicker
			wp_enqueue_script( 'jquery-ui-datepicker' );

			//Tabs
			wp_enqueue_script( 'jquery-ui-tabs' );

			//Accordian
			wp_enqueue_script( 'jquery-ui-accordion' );

			//Dialogs
			wp_enqueue_script( 'jquery-ui-dialog' );

			//Color Box For How to Videos
			$cctor_colorbox_css = Cctor__Coupon__Main::instance()->plugin_path . 'vendor/colorbox/colorbox.css';
			wp_enqueue_style( 'cctor_colorbox_css', Cctor__Coupon__Main::instance()->plugin_url . 'vendor/colorbox/colorbox.css', false, filemtime( $cctor_colorbox_css ) );

			$cctor_colorbox_js = Cctor__Coupon__Main::instance()->plugin_path . 'vendor/colorbox/jquery.colorbox-min.js';
			wp_enqueue_script( 'cctor_colorbox_js', Cctor__Coupon__Main::instance()->plugin_url . 'vendor/colorbox/jquery.colorbox-min.js', array( 'jquery' ), filemtime( $cctor_colorbox_js ), true );

			//Hook to Load New Styles and Scripts
			do_action( 'cctor_meta_scripts_styles' );

		}

	}

}
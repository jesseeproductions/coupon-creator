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
	public function load_assets() {

		$screen = get_current_screen();

		if ( ! isset( $screen->id ) ) {
			return;
		}

		if (
			'cctor_coupon' !== $screen->id &&
			'settings_page_plugin-engine-options' !== $screen->id &&
			'cctor_coupon_page_coupon-options' !== $screen->id
		) {
			return;
		}

		//Styles
		// @formatter:off
		wp_enqueue_style(
			'coupon-admin-style',
			pngx( 'cctor' )->resource_url . 'css/admin-style.css',
			array( 'pngx-admin' ),
			filemtime( pngx( 'cctor' )->resource_path . 'css/admin-style.css' )
		);
		// @formatter:on

		//Style or WP Color Picker
		wp_enqueue_style( 'wp-color-picker' );
		//Image Upload CSS
		wp_enqueue_style( 'thickbox' );

		//jQuery UI Style
		global $wp_scripts;

		$jquery_version = isset( $wp_scripts->registered['jquery-ui-core']->ver ) ? $wp_scripts->registered['jquery-ui-core']->ver : '1.12.3';
		$css_file       = '//ajax.googleapis.com/ajax/libs/jqueryui/' . $jquery_version . '/themes/smoothness/jquery-ui.min.css';

		if ( ! pngx( 'admin.assets' )->detect_external_asset( 'https:' . $css_file ) ) {
			$css_file = Pngx__Main::instance()->resource_url . 'css/jquery-ui.min.css';
		}

		wp_enqueue_style( 'jquery-ui-style', esc_url( $css_file ) );

		//Media Manager from 3.5
		wp_enqueue_media();

		//Script for WP Color Picker
		wp_enqueue_script( 'wp-color-picker' );

		//Script for Datepicker
		wp_enqueue_script( 'jquery-ui-datepicker' );

		//core
		wp_enqueue_script( 'jquery-ui-core' );

		//Tabs
		wp_enqueue_script( 'jquery-ui-tabs' );

		//Accordian
		wp_enqueue_script( 'jquery-ui-accordion' );

		//Dialogs
		wp_enqueue_script( 'jquery-ui-dialog' );

		// @formatter:off
		wp_enqueue_script(
			'cctor_admin_js',
			pngx( 'cctor' )->resource_url . 'js/coupon-admin.js',
			array(
				'jquery',
				'media-upload',
				'thickbox',
				'farbtastic',
				'pngx-admin'
			),
			filemtime( pngx( 'cctor' )->resource_path . 'js/coupon-admin.js' ),
			true
		);
		// @formatter:on

		//Hook to Load New Styles and Scripts
		do_action( 'cctor_admin_assets' );

	}

}
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
class Cctor__Coupon__Assets {

	public function __construct() {

		add_action( 'enqueue_block_editor_assets', array( $this, 'blocks_editor_styles' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'blocks_editor_scripts' ) );
		add_action( 'enqueue_block_editor_assets',  array( $this, 'inline_style' ), 100 );

		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'inline_style' ), 100 );
	}

	/**
	 * Enqueue block editor CSS
	 *
	 * @since 3.0
	 */
	public function blocks_editor_styles() {

		// @formatter:off
		wp_enqueue_style(
			'cctor-coupon-editor-element',
			pngx( 'cctor' )->resource_url . 'css/elements.css',
			array(
			),
			filemtime( pngx( 'cctor' )->resource_path . 'css/elements.css' )
		);
		wp_enqueue_style(
			'coupon_creator_css',
			pngx( 'cctor' )->resource_url . 'css/coupon.css',
			array(
			),
			filemtime( pngx( 'cctor' )->resource_path . 'css/coupon.css' )
		);
		wp_enqueue_style(
			'cctor-coupon-editor-blocks',
			pngx( 'cctor' )->resource_url . 'css/blocks.css',
			array(
			),
			filemtime( pngx( 'cctor' )->resource_path . 'css/blocks.css' )
		);
		// @formatter:on
	}

	/**
	 * Enqueue block editor Scripts
	 *
	 * @since 3.0
	 */
	public function blocks_editor_scripts() {
		// @formatter:off
		wp_enqueue_script(
			'cctor-coupon-editor-blocks',
			pngx( 'cctor' )->resource_url . 'js/blocks.js',
			array(
				'react',
				'react-dom',
				'wp-components',
				'wp-editor',
				'wp-api',
				'wp-api-request',
				'wp-blocks',
				'wp-i18n',
				'wp-element',
			),
			filemtime( pngx( 'cctor' )->resource_path . 'js/blocks.js' )
		);
		$localized_data = array(
			'data' => get_option(  pngx( 'cctor' )->OPTIONS_ID ),
			'constants' => array (
				'hide_upgrade' => ( defined( 'CCTOR_HIDE_UPGRADE' ) && CCTOR_HIDE_UPGRADE ) ? 'true' : 'false',
			),
		);
		wp_localize_script( 'cctor-coupon-editor-blocks', 'pngx_blocks_editor_settings', $localized_data );

		wp_enqueue_script(
			'cctor-coupon-editor',
			pngx( 'cctor' )->resource_url . 'js/editor.js',
			array(
				'react',
				'react-dom',
				'wp-components',
				'wp-editor',
				'wp-api',
				'wp-api-request',
				'wp-blocks',
				'wp-i18n',
				'wp-element',
			),
			filemtime( pngx( 'cctor' )->resource_path . 'js/editor.js' )
		);

		wp_enqueue_script(
			'cctor-coupon-editor-elements',
			pngx( 'cctor' )->resource_url . 'js/elements.js',
			array(
				'react',
				'react-dom',
				'wp-components',
				'wp-editor',
				'wp-api',
				'wp-api-request',
				'wp-blocks',
				'wp-i18n',
				'wp-element',
			),
			filemtime( pngx( 'cctor' )->resource_path . 'js/elements.js' )
		);
		// @formatter:on
	}

	/*
	* Register Coupon Creator CSS
	*/
	public function register_assets() {

		// @formatter:off
		wp_register_style(
			'coupon_creator_css',
			pngx( 'cctor' )->resource_url . 'css/coupon.css',
			false,
			filemtime( pngx( 'cctor' )->resource_path . 'css/coupon.css' )
		);
		// @formatter:on

	}

	/*
	* Add Inline Style From Coupon Options
	*/
	public function inline_style() {

		$cctor_option_css = "";

		if ( has_filter( 'cctor_filter_inline_css' ) ) {
			$coupon_css = "";
			/**
			 * Filter Coupon Inline Styles
			 *
			 *
			 * @param string $coupon_css .
			 *
			 */
			$cctor_option_css = apply_filters( 'cctor_filter_inline_css', $coupon_css );
		}
		//Add Custom CSS from Options
		if ( cctor_options( 'cctor_custom_css' ) ) {

			$cctor_option_css .= cctor_options( 'cctor_custom_css' );
		}

		wp_add_inline_style( 'coupon_creator_css', wp_kses_post( $cctor_option_css ) );
	}

}
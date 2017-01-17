<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
if ( class_exists( 'Cctor__Coupon__Admin__Field__Template' ) ) {
	return;
}


/**
 * Class Cctor__Coupon__Admin__Field__Template
 * Image Coupon Field
 */
class Cctor__Coupon__Admin__Field__Template {

	public static function display( $field = array(), $options = array(), $options_id = null, $meta = null ) {

		if ( isset( $options_id ) && ! empty( $options_id ) ) {
			$name  = $options_id;
			$value = $options[ $field['id'] ];
		} else {
			$name  = $field['id'];
			$value = $meta;
		}

		global $post;

		$settings = array();
		if ( ! class_exists( '_WP_Editors' ) ) {
			require( ABSPATH . WPINC . '/class-wp-editor.php' );
		}
		$set = _WP_Editors::parse_settings( 'apid', $settings );
		if ( ! current_user_can( 'upload_files' ) ) {
			$set['media_buttons'] = false;
		}
		if ( $set['media_buttons'] ) {
			wp_enqueue_script( 'thickbox' );
			wp_enqueue_style( 'thickbox' );
			wp_enqueue_script( 'media-upload' );
			$post = get_post();
			if ( ! $post && ! empty( $GLOBALS['post_ID'] ) ) {
				$post = $GLOBALS['post_ID'];
			}
			wp_enqueue_media( array(
				'post' => $post
			) );
		}

		_WP_Editors::editor_settings( 'apid', $set );
		$ap_vars = array(
			'url'          => get_home_url(),
			'includes_url' => includes_url()
		);

		wp_enqueue_script( 'cctor-wp-editor', Cctor__Coupon__Main::instance()->resource_url . 'js/wp_editor.js', array( 'jquery' ), '2015041565' );
		wp_localize_script( 'cctor-wp-editor', 'ap_vars', $ap_vars );


		wp_enqueue_script( 'cctor-load-template-ajax', Cctor__Coupon__Main::instance()->resource_url . 'js/templates.js', array( 'jquery' ), '201504155' );
		wp_localize_script( 'cctor-load-template-ajax', 'cctor_templates', array(
			'ajaxurl'                     => admin_url( 'admin-ajax.php', ( is_ssl() ? 'https' : 'http' ) ),
			'nonce'                       => wp_create_nonce( 'download_click_counter_' . $post->ID ),
			'coupon_add_ons_resource_url' => Cctor__Coupon__Main::instance()->resource_url,
			'coupon_pro_resource_url'     => Cctor__Coupon__Pro__Main::instance()->resource_url,
			'pngx_resource_url'           => Pngx__Main::instance()->resource_url,
			'coupon_version'              => Cctor__Coupon__Main::VERSION_NUM,
			'post_id'                     => $post->ID
		) );

	}

}


?>
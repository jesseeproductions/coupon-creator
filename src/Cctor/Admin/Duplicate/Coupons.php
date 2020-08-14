<?php

namespace Cctor\Coupon\Admin\Duplicate;

use Pngx\Duplicate\Post_Types;

/**
 * Class Main
 *
 * @since   3.1
 *
 * @package Pngx\Duplicate
 */
class Coupons {

	/**
	 * Meetings constructor.
	 *
	 * @since 3.1
	 *
	 * @param Post_Types $duplicate An instance of the Post_Type duplicate class.
	 */
	public function __construct( Post_Types $duplicater ) {
		$this->duplicater = $duplicater;
		$this->post_type = \Cctor__Coupon__Main::POSTTYPE;
	}

	/**
	 * Setup hooks for class.
	 *
	 * @since TBD
	 */
	public function hooks() {

		add_action( 'admin_action_pngx_duplicate_coupon', [ $this, 'duplicate_coupon' ] );
		add_filter( 'post_row_actions', [ $this, 'duplicate_coupon_link' ], 10, 2 );
	}

	/**
	 * Duplicate Coupon
	 */
	public function duplicate_coupon() {

		if (
			! (
				isset( $_GET['post'] ) ||
				isset( $_POST['post'] ) ||
				(
					isset( $_REQUEST['action'] ) &&
					'pngx_duplicate_coupon' === $_REQUEST['action']
				)
			)
		) {
			wp_die( esc_html__( 'No coupon to duplicate has been supplied!', 'coupon-creator' ) );
		}

		if (
			! isset( $_GET['pngx_duplicate_nonce'] ) ||
			! wp_verify_nonce( $_GET['pngx_duplicate_nonce'], basename( __FILE__ ) )
		) {
			return;
		}

		$this->duplicater->duplicate( $this->post_type );
	}

	/**
	 * Add the create link to action list for post_row_actions
	 *
	 * @param $actions
	 * @param $post
	 *
	 * @return mixed
	 */
	public function duplicate_coupon_link( $actions, $post ) {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return $actions;
		}

		if ( $this->post_type !== $post->post_type ) {
			return $actions;
		}

		$actions['duplicate'] = '<a 
				href="' . wp_nonce_url( 'admin.php?action=pngx_duplicate_coupon&post=' . $post->ID, basename( __FILE__ ), 'pngx_duplicate_nonce' ) . '" 
				title="' . esc_html__( 'Duplicate Coupon', 'coupon-creator' ) . '" 
			>' .
                esc_html__( 'Duplicate', 'coupon-creator' ) .
			'</a>';

		return $actions;
	}
}
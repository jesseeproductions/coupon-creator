<?php


class Cctor__Coupon__Blocks__Coupon extends Pngx__Blocks__Abstract {

	/**
	 * Which is the name/slug of this block
	 *
	 * @since  3.2.0
	 *
	 * @return string
	 */
	public function slug() {
		return 'coupon';
	}

	/**
	 * Does the registration for PHP rendering for the Block
	 *
	 * @since  3.2.0
	 *
	 * @return void
	 */
	public function register() {
		$block_args = array(
			'attributes'      => array(
				'couponid'      => array(
					'type' => 'string',
				),
				'category'      => array(
					'type'  => 'array',
					'items' => array(
						'type' => 'string'
					)
				),
				'coupon_align'  => array(
					'type' => 'string',
				),
				'couponorderby' => array(
					'type' => 'string',
				),
			),
			'render_callback' => array( $this, 'render' ),
		);

		register_block_type( $this->name(), $block_args );

		add_action( 'wp_ajax_' . $this->get_ajax_action(), array( $this, 'ajax' ) );

		$this->assets();

	}

	/**
	 * Since we are dealing with a Dynamic type of Block we need a PHP method to render it
	 *
	 * @since  3.2.0
	 *
	 * @param  array $attributes
	 *
	 * @return string
	 */
	public function render( $attributes = array() ) {
		$args['attributes'] = $this->attributes( $attributes );

		if ( empty( $args['attributes']['couponid'] ) ) {
			return '<p class="pngx-message">' . __( 'Please choose a coupon to display from the block settings.', 'coupon-creator' ) . '</p>';
		}

		if (  class_exists( 'Cctor__Coupon__Pro__Core_Shortcode') ) {
			$coupon = pngx( 'cctor.pro.core.shortcode' )->core_shortcode( $attributes );
		} else {
			$coupon = pngx( 'cctor.shortcode' )->core_shortcode( $attributes );
		}

		if ( ! $coupon && is_numeric( $args['attributes']['couponid'] ) ) {
			$status = get_post_status( $args['attributes']['couponid'] );

			return '<p class="pngx-message pngx-notice">' . sprintf( '%1s %2s %3s', __( 'This coupon is set to', 'coupon-creator' ), $status, __( ' and will not show on the website.', 'coupon-creator' ) ) . '</p>';
		} elseif ( ! $coupon ) {
			return '<p class="pngx-message pngx-notice">' . __( 'No Coupons Found, Please make another selection.', 'coupon-creator' ) . '</p>';
		}

		return $coupon;

	}
}
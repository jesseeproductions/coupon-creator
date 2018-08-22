<?php


class Cctor__Coupon__Blocks__Coupon extends Pngx__Blocks__Abstract {

	/**
	 * Which is the name/slug of this block
	 *
	 * @since  TBD
	 *
	 * @return string
	 */
	public function slug() {
		return 'coupon';
	}

	/**
	 * Does the registration for PHP rendering for the Block
	 *
	 * @since  TBD
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
					'type' => 'array',
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
	 * @since  TBD
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

		$coupon = Cctor__Coupon__Shortcode::core_shortcode( $attributes );

		if ( ! $coupon && is_numeric( $args['attributes']['couponid'] ) ) {
			$status = get_post_status( $args['attributes']['couponid'] );

			return '<p class="pngx-message pngx-notice">' . sprintf( '%1s %2s %3s', __( 'This coupon is set to', 'coupon-creator' ), $status, __( ' and will not show on the website.', 'coupon-creator' ) ) . '</p>';
		} elseif ( ! $coupon ) {
			return '<p class="pngx-message pngx-notice">' . __( 'No Coupons Found, Please make another selection.', 'coupon-creator' ) . '</p>';
		}

		return $coupon;

	}
}
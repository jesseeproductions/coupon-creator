<?php
class Cctor__Coupon__Blocks__Coupon_Shortcode
extends Pngx__Blocks__Abstract {

	/**
	 * Which is the name/slug of this block
	 *
	 * @since  TBD
	 *
	 * @return string
	 */
	public function slug() {
		return 'coupon-shortcode-php';
	}

	/**
	 * Does the registration for PHP rendering for the Block, important due to been
	 * an dynamic Block
	 *
	 * @since  TBD
	 *
	 * @return void
	 */
	public function register() {
		$block_args = array(
			'attributes'      => array(
				'couponid' => array(
					'type' => 'string',
				),
				'category' => array(
					'type' => 'string',
				),
				'coupon_align' => array(
					'type' => 'string',
				),
				'couponorderby' => array(
					'type' => 'string',
				),
				'selectedTaxonomy' => array(
					'type' => 'number',
					'default' => 0,
				),
				'selectedPost' => array(
					'type' => 'number',
					'default' => 0,
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

		//log_me($args['attributes']);

		if ( empty ( $args['attributes']['couponid'] ) ) {
			return '<p>Coupon Block' . print_r( $attributes, true ) . '</p>';
		}

		return Cctor__Coupon__Shortcode::core_shortcode( $attributes );

		// Add the rendering attributes into global context
		//pngx( 'gutenberg.template' )->add_template_globals( $args );

		//return pngx( 'gutenberg.template' )->template( array( 'blocks', $this->slug() ), $args, false );
	}
}
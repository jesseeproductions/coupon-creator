<?php
class Cctor__Coupon__Blocks__Menu_Items
extends Pngx__Blocks__Abstract {

	/**
	 * Which is the name/slug of this block
	 *
	 * @since  TBD
	 *
	 * @return string
	 */
	public function slug() {
		return 'cctor';
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
		//$args['attributes'] = $attributes;

		// Add the rendering attributes into global context
		//pngx( 'wpemenu.template' )->add_template_globals( $args );

		//return pngx( 'wpemenu.template' )->template( array( 'blocks', $this->slug() ), $args, false );
	}
}
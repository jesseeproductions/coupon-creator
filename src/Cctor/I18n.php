<?php
/**
 * Include translations to Gutenberg Ext
 *
 * @since TBD
 */
class Cctor__Coupon__I18n {

	/**
	 * Hook into the required places to make it work
	 *
	 * @since  TBD
	 *
	 * @return void
	 */
	public function hook() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'include_inline_script' ), 11 );
	}

	/**
	 * Include the Inline Script with locale
	 *
	 * @since  TBD
	 *
	 * @return void
	 */
	public function include_inline_script( $value ) {
		// Prepare Jed locale data.
		$locale_data = gutenberg_get_jed_locale_data( 'coupon-creator' );
		wp_add_inline_script(
			'cctor-coupon-editor-blocks',
			'wp.i18n.setLocaleData( ' . json_encode( $locale_data ) . ', "coupon-creator" );',
			'before'
		);
	}
}
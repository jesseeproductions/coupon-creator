<?php
/**
 * Include translations to Gutenberg Ext
 *
 * @since 3.0
 */
class Cctor__Coupon__I18n {

	/**
	 * Hook into the required places to make it work
	 *
	 * @since  3.2.0
	 *
	 * @return void
	 */
	public function hook() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'include_inline_script' ), 11 );
	}

	/**
	 * Include the Inline Script with locale
	 *
	 * @since  3.2.0
	 *
	 * @return void
	 */
	public function include_inline_script( $value ) {

		$domain = 'coupon-creator';
		$translations = get_translations_for_domain( $domain );
		$locale = array(
			'' => (object) array(),
			'prevent-empty' => 'prevent-empty',
		);

		foreach ( $translations->entries as $msgid => $entry ) {
			$locale[ $msgid ] = $entry->translations;
		}

		// Prepare Jed locale data.
		wp_add_inline_script(
			'cctor-coupon-editor-blocks',
			'wp.i18n.setLocaleData( ' . json_encode( $locale ) . ', "coupon-creator" );',
			'before'
		);
	}
}
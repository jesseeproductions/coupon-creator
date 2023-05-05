<?php
/**
 * Handles the Meta and Options Fields.
 *
 * @since   0.1.0
 *
 * @package Cctor\Coupon\Test;
 */

namespace Cctor\Coupon\Test;

use Cctor\Coupon\Test\Fields\Test;
use Cctor\Coupon\Templates\Admin_Template;

/**
 * Class Fields for Meta and Options.
 *
 * @since   0.1.0
 *
 * @package Cctor\Coupon\Test;
 */
class Fields {

	/**
	 * An instance of the admin template handler.
	 *
	 * @since 0.1.0
	 *
	 * @var Admin_Template
	 */
	protected static $admin_template;

	/**
	 * Template_Modifications constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param Admin_Template $template An instance of the backend template handler.
	 */
	public function __construct( Admin_Template $admin_template ) {
		static::$admin_template = $admin_template;
	}

	/**
	 * Display Fields by Type.
	 *
	 * @since 0.1.0
	 *
	 * @param array               $field
	 *
	 * @param array<string|mixed> $field      The field options in an array.
	 * @param array<string|mixed> $options    The field options in an array.
	 * @param string              $options_id The Option ID for the field.
	 * @param string              $meta       The meta value for the field.
	 * @param string              $wp_version The current WP Version from the global.
	 *
	 * @return array|mixed
	 */
	public static function display_field( $field = [], $options = [], $options_id = null, $meta = null, $wp_version = null ) {
		//Create Different Name for Option Fields and Not Meta Fields
		if ( $options ) {
			$options_id = $options_id . '[' . $field['id'] . ']';
		}

		switch ( $field['type'] ) {
			case 'test_actions':
				pngx( Test::class )::display( $field, $options, $options_id, $meta,  static::$admin_template );

				break;
		}

		return $field;
	}
}

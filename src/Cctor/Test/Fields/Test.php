<?php
/**
 * Handles the Help Field on the Options Page.
 *
 * @since   0.1.0
 *
 * @package Cctor\Coupon\Test\Fields;
 */

namespace Cctor\Coupon\Test\Fields;

use Cctor\Coupon\Test\Url;

/**
 * Class Help
 *
 * @since                                    0.1.0
 *
 * @packageCctor\Coupon\Test\Fields;
 */
class Test {

	public static function display( $field = [], $option_values = [], $options_id = null, $meta = null, $template = null ) {

		$args = [
			'post_id' => get_the_ID(),
			'url'     => pngx( Url::class ),
		];

		$template->template( 'test/components/create-button', $args );
	}
}

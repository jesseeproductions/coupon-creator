<?php

namespace Helper;

use Codeception\Module\WPDb;

class PngxDB extends \Codeception\Module {

	/**
	 * @var WPDb
	 */
	protected $db;

	public function _initialize() {
		$this->db = $this->getModule( 'WPDb' );
	}

	/**
	 * Gets an option from the tribe option database row.
	 *
	 * @param string $key
	 * @param mixed  $default
	 *
	 * @return mixed
	 */
	public function getCouponOptionFromDatabase( $key, $default = '' ) {
		$options = $this->db->grabOptionFromDatabase( 'coupon_creator_options' );
		if ( empty( $options ) ) {
			return $default;
		}

		return isset( $options[ $key ] ) ? $options[ $key ] : $default;
	}

	/**
	 * Sets an option in the tribe option row.
	 *
	 * @param string       $key
	 * @param string|array $value
	 */
	public function setCouponOption( $key, $value ) {
		$option_name = 'coupon_creator_options';
		$options     = $this->db->grabOptionFromDatabase( $option_name );
		if ( empty( $options ) ) {
			$this->db->haveOptionInDatabase( $option_name, [ $key => $value ] );
		} else {
			$this->db->haveOptionInDatabase( $option_name, array_merge( $options, [ $key => $value ] ) );
		}
	}

	/**
	 * Inserts many coupons in the database.
	 *
	 * @param int   $count         The number of coupons to insert.
	 * @param array $overrides     An array of arguments to override the defaults (see `haveCouponInDatabase`)
	 *
	 * @return array An array of generated coupon post IDs.
	 */
	public function haveManyCouponsInDatabase( $count, array $overrides = [] ) {
		$ids  = [];
		for ( $n = 0; $n < $count; $n ++ ) {
			$coupon_overrides = $overrides;
			$ids[] = $this->haveCouponInDatabase( $coupon_overrides );
		}

		return $ids;
	}

	/**
	 * Inserts an coupon in the database.
	 *
	 * @param array $overrides An array of values to override the default arguments.
	 *                         Keep in mind `tax_input` and `meta_input` to bake terms and custom fields in.
	 *
	 * @return int The generated coupon post ID
	 */
	public function haveCouponInDatabase( array $overrides = [] ) {

		$prefix           = 'cctor_';
		$period           = rand( 1, 5 );
		$id               = uniqid( mt_rand( 1, 999 ), true );
		$expiration       = date( 'm/d/Y', strtotime( '+' . $period . ' months' ) );
		$expiration_mysql = date( 'Y-m-d h:i:s', strtotime( $expiration ) );

		$meta_input = [
			$prefix . 'coupon_type'       => 'default', //image
			$prefix . 'expiration_option' => 2, //1
			$prefix . 'date_format'       => 1, // 0
			$prefix . 'expiration'        => $expiration,
			$prefix . 'expiration_mysql'  => $expiration_mysql,
			$prefix . 'amount'            => "Coupon {$id}",
			$prefix . 'colorheader'       => '#' . substr( md5( mt_rand() ), 0, 6 ),
			$prefix . 'colordiscount'     => '#' . substr( md5( mt_rand() ), 0, 6 ),
			$prefix . 'description'       => "Coupon Terms {$id}",
			$prefix . 'bordercolor'       => '#' . substr( md5( mt_rand() ), 0, 6 ),
			//$prefix . 'image'     => 10, // id 10
		];

		$tax_input = [];
		if ( isset( $overrides['categories'] ) ) {
			$tax_input['cctor_coupon_category'] = (array) $overrides['categories'];
			unset( $overrides['categories'] );
		}
		if ( isset( $overrides['coupon-location'] ) ) {
			$tax_input['coupon-location'] = (array) $overrides['coupon-location'];
			unset( $overrides['coupon-location'] );
		}
		if ( isset( $overrides['coupon-vendor'] ) ) {
			$tax_input['coupon-vendor'] = (array) $overrides['coupon-vendor'];
			unset( $overrides['coupon-vendor'] );
		}

		if ( ! empty( $tax_input ) ) {
			if ( isset( $overrides['tax_input'] ) ) {
				$overrides['tax_input'] = array_merge( $overrides['tax_input'], $tax_input );
			} else {
				$overrides['tax_input'] = $tax_input;
			}
		}

		$defaults = [
			'post_type'  => 'cctor_coupon',
			'post_title' => "Coupon {$id}",
			'post_name'  => "coupon-{$id}",
			'meta_input' => isset( $overrides['meta_input'] ) ? array_merge( $meta_input, $overrides['meta_input'] ) : $meta_input,
		];

		unset( $overrides['meta_input'] );

		$post_id = $this->db->havePostInDatabase( array_merge( $defaults, $overrides ) );

		return $post_id;
	}

}
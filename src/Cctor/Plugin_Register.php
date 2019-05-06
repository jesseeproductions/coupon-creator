<?php


/**
 * Class Cctor__Coupon__Plugin_Register
 */
class Cctor__Coupon__Plugin_Register extends Pngx__Abstract_Plugin_Register {

	protected $main_class   = 'Cctor__Coupon__Main';
	protected $dependencies = array(
		'addon-dependencies' => array(
			'Cctor__Coupon__Pro__Main'    => '2.6',
			'Cctor__Coupon__Addons__Main' => '2.6',
		),
	);

	public function __construct() {
		$this->base_dir = COUPON_CREATOR_MAIN_PLUGIN_FILE;
		$this->version  = Cctor__Coupon__Main::VERSION_NUM;

		$this->register_plugin();
	}
}
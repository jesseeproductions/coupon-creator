<?php
namespace Cctor;

use Tribe\Events\Test\Testcases\Events_TestCase;
use Cctor__Coupon__Main as Coupons;
use Pngx__Main as Plugin_Engine;

/**
 * Test that Plugin Engine is being loaded correctly
 *
 * @group   core
 *
 * @package Cctor__Coupon__Main
 */
class PluginEngineTest extends Events_TestCase {
	/**
	 * @test
	 */
	public function it_is_loading_common() {

		$this->assertFalse(
			defined( Plugin_Engine::VERSION ),
			'Plugin Engine is not loading, you probably need to check that'
		);
	}

	/**
	 * @test
	*/
	public function it_is_loading_common_required_version() {

		$this->assertTrue(
			version_compare( Plugin_Engine::VERSION, Coupons::MIN_PNGX_VERSION, '>=' ),
			'Plugin Engine version should be at least ' . Coupons::MIN_PNGX_VERSION . ', currently on ' . Plugin_Engine::VERSION
		);
	}

	}

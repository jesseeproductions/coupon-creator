<?php
namespace Cctor\Admin;

use Codeception\TestCase\WPTestCase;

/**
 * @group meta
 * @group expiration
 */
class MetaTest extends WPTestCase {

	/**
	 * @var \Cctor__Coupon__Admin__Meta
	 */
	protected $meta;

	/**
	 * @var int
	 */
	protected $coupon_id;

	public function setUp() {
		parent::setUp();

		$reflection  = new \ReflectionClass( '\Cctor__Coupon__Admin__Meta' );
		$this->meta  = $reflection->newInstanceWithoutConstructor();
		$this->coupon_id = $this->factory()->post->create( [
			'post_type'   => 'cctor_coupon',
			'post_status' => 'publish',
		] );

		unset( $_POST['cctor_expiration_option'] );
	}

	public function tearDown() {
		unset( $_POST['cctor_expiration_option'] );
		parent::tearDown();
	}

	/**
	 * @test
	 */
	public function sync_sets_ignore_expiration_when_option_is_one() {
		$_POST['cctor_expiration_option'] = '1';

		$this->meta->sync_ignore_expiration( $this->coupon_id );

		$this->assertEquals( 'on', get_post_meta( $this->coupon_id, 'cctor_ignore_expiration', true ) );
	}

	/**
	 * @test
	 */
	public function sync_deletes_ignore_expiration_when_option_is_not_one() {
		update_post_meta( $this->coupon_id, 'cctor_ignore_expiration', 'on' );
		$_POST['cctor_expiration_option'] = '2';

		$this->meta->sync_ignore_expiration( $this->coupon_id );

		$this->assertEmpty( get_post_meta( $this->coupon_id, 'cctor_ignore_expiration', true ) );
	}

	/**
	 * @test
	 */
	public function sync_is_a_noop_when_expiration_option_is_not_in_post() {
		update_post_meta( $this->coupon_id, 'cctor_ignore_expiration', 'on' );

		$this->meta->sync_ignore_expiration( $this->coupon_id );

		$this->assertEquals( 'on', get_post_meta( $this->coupon_id, 'cctor_ignore_expiration', true ) );
	}
}

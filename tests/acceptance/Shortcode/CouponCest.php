<?php

namespace Pngx\Coupons\Test\Acceptance\Shortcode;

use AcceptanceTester;
use Pngx\Coupons\Test\Acceptance\BaseAcceptanceCest;

class CouponCest extends BaseAcceptanceCest {

	/**
	 * @test
	 * since 3.2.0
	 */
	public function should_add_html_comment_when_no_coupons_in_loop( AcceptanceTester $I ) {
		$I->havePageInDatabase( [
			'post_title'   => 'Coupons',
			'post_name'    => 'coupons',
			'post_content' => '[coupon couponid="loop" coupon_align="cctor_aligncenter" name="Coupon Loop"]',
		] );

		$I->amOnPage( '/coupons/' );
		$I->wait( 5 );
		$I->dontSeeInPageSource( 'Coupon shortcode is not showing with the following attributes: totalcoupons:-1,couponid:loop,coupon_align:cctor_alignnone,couponorderby:date,category:,bordertheme:,filterid:' );
	}

	/**
	 * @test
	 * since 3.2.0
	 */
	public function should_add_html_comment_when_no_coupon_by_id( AcceptanceTester $I ) {
		$I->havePageInDatabase( [
			'post_title'   => 'Coupons',
			'post_name'    => 'coupons',
			'post_content' => '[coupon couponid="896" coupon_align="cctor_aligncenter" name="Coupon ID"]',
		] );

		$I->amOnPage( '/coupons/' );
		$I->wait( 5 );
		$I->dontSeeInPageSource( 'Coupon shortcode is not showing with the following attributes: totalcoupons:-1,couponid:4,coupon_align:cctor_alignnone,couponorderby:date,category:,bordertheme:,filterid:' );
	}

}

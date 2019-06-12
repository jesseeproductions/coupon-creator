<?php

namespace Pngx\Coupons\Test\Acceptance\Coupons;

use AcceptanceTester;
use Pngx\Coupons\Test\Acceptance\BaseAcceptanceCest;

class CouponsCest extends BaseAcceptanceCest {

	/**
	 * @test
	 * since TBD
	 */
	public function should_create_default_coupon_ignore_expiration_have_black_inside_border_and_have_one_category( AcceptanceTester $I ) {
		$deal     = 'Automated Deal 01';
		$terms    = 'Automated Terms 01';
		$category = 'Food';

		$I->loginAsAdmin();

		// start check of defaults on add new coupon
		$I->amOnAdminPage( '/post-new.php?post_type=cctor_coupon' );
		$I->wait( 2 );

		// maximize window to see tabs
		$I->maximizeWindow();
		$I->dontSeeInPageSource( 'Place this coupon in your posts, pages, custom post types, or widgets by using the shortcode below:' );
		$I->selectOption( '#cctor_coupon_type', 'Default' );
		$I->waitForJqueryAjax();
		$I->fillField( '#title', $deal );
		$I->fillField( '#cctor_amount', $deal );
		$I->fillField( '#cctor_description', $terms );

		$I->click( '#ui-id-2' );
		$I->executeJS( '$(".wp-picker-input-wrap").show();' );
		$I->fillField( '#cctor_bordercolor', '#000000' );

		$I->click( '#ui-id-3' );
		$I->selectOption( '#cctor_expiration_option', 'Ignore Expiration' );

		$I->scrollTo( '#cctor_coupon_categorydiv' );
		$I->click( '#cctor_coupon_category-add-toggle' );
		$I->fillField( '#newcctor_coupon_category', $category );
		$I->click( '#cctor_coupon_category-add-submit' );
		$I->wait( 3 );

		$I->scrollTo( '#submitdiv' );
		$I->click( '#publish' );
		$I->waitForElementVisible( '#message', 10 );
		$I->see( 'Coupon published.', '#message' );
		$I->see( 'This Coupon is Showing.' );
		$I->see( 'Ignore Coupon Expiration is On' );
		$I->SeeInPageSource( 'Place this coupon in your posts, pages, custom post types, or widgets by using the shortcode below:' );
		$I->wait( 3 );

		$I->click( 'View Coupon' );
		$I->waitForElementVisible( '.cctor-deal', 10 );
		$I->see( $deal, '.cctor-deal' );
		$I->see( $terms, '.cctor-terms' );
		$I->seeInPageSource( '<div class="cctor_coupon_content cctor-coupon-content" style="border-color:#000000">' );

		$I->havePageInDatabase( [
			'post_title'   => 'Coupons',
			'post_name'    => 'coupons',
			'post_content' => '[coupon couponid="loop" category="' . $category . '" coupon_align="cctor_aligncenter" name="' . $deal . '"]',
		] );

		$I->amOnPage( '/coupons/' );
		$I->waitForElementVisible( '.cctor-deal', 10 );
		$I->see( $deal, '.cctor-deal' );
		$I->see( $terms, '.cctor-terms' );
		$I->seeInPageSource( '<div class="cctor_coupon_content cctor-coupon-content" style="border-color:#000000">' );

	}

}

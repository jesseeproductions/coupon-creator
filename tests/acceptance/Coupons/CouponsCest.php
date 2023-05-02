<?php

namespace Pngx\Coupons\Test\Acceptance\Coupons;

use AcceptanceTester;
use Pngx\Coupons\Test\Acceptance\BaseAcceptanceCest;

class CouponsCest extends BaseAcceptanceCest {

	/**
	 * @test
	 * since 3.2.0
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
		$I->makeScreenshot( 'black-inside-border-print' );

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
		$I->makeScreenshot( 'black-inside-border-shortcode' );
	}

	/**
	 * @test
	 * since 3.2.0
	 */
	public function should_create_image_coupon_ignore_expiration_and_have_two_categories( AcceptanceTester $I ) {
		$deal          = 'Automated Deal 02';
		$category      = 'Food';
		$category_2    = 'Clothing';
		$file          = wp_normalize_path( codecept_data_dir( 'images/coupon-image-01.jpg' ) );
		$coupon_img_id = $I->haveAttachmentInDatabase( $file );
		$couponimage   = wp_get_attachment_image_src( $coupon_img_id, 'medium' );
		$couponimage   = isset( $couponimage[0] ) ? $couponimage[0] : '';
		$couponimage   = wp_normalize_path( $couponimage );

		$I->loginAsAdmin();

		// start check of defaults on add new coupon
		$I->amOnAdminPage( '/post-new.php?post_type=cctor_coupon' );
		$I->wait( 2 );

		// maximize window to see tabs
		$I->maximizeWindow();
		$I->selectOption( '#cctor_coupon_type', 'Image' );
		$I->waitForJqueryAjax();
		$I->fillField( '#title', $deal );
		$I->executeJS( '$("#cctor_image.pngx-default-image").hide();' );
		$I->executeJS( '$("#cctor_image.pngx-image").show();' );
		$I->executeJS( '$("#cctor_image.pngx-image").attr( "src", "' . esc_url( $couponimage ) . '" );' );
		$I->executeJS( '$("input#cctor_image").val(' . $coupon_img_id . ');' );
		$I->makeScreenshot( 'image-coupon-admin' );

		$I->click( '#ui-id-3' );
		$I->selectOption( '#cctor_expiration_option', 'Ignore Expiration' );

		$I->scrollTo( '#cctor_coupon_categorydiv' );
		$I->click( '#cctor_coupon_category-add-toggle' );
		$I->fillField( '#newcctor_coupon_category', $category );
		$I->click( '#cctor_coupon_category-add-submit' );
		$I->wait( 3 );

		$I->fillField( '#newcctor_coupon_category', $category_2 );
		$I->click( '#cctor_coupon_category-add-submit' );
		$I->wait( 3 );

		$I->scrollTo( '#submitdiv' );
		$I->click( '#publish' );
		$I->waitForElementVisible( '#message', 10 );
		$I->see( 'Coupon published.', '#message' );
		$I->see( 'This Coupon is Showing.' );
		$I->see( 'Ignore Coupon Expiration is On' );
		$I->wait( 3 );

		$I->click( 'View Coupon' );
		$I->waitForElementVisible( '.type-cctor_coupon', 10 );
		$I->makeScreenshot( 'image-coupon-print' );

		$I->havePageInDatabase( [
			'post_title'   => 'Coupons',
			'post_name'    => 'coupons-category-' . strtolower( $category ),
			'post_content' => '[coupon couponid="loop" category="' . $category . '" coupon_align="cctor_aligncenter" name="' . $deal . '"]',
		] );

		$I->amOnPage( '/coupons-category-' . strtolower( $category ) . '/' );
		$I->waitForElementVisible( '.type-cctor_coupon', 10 );
		$I->makeScreenshot( 'image-coupon-shortcode-' . $category );

		$I->havePageInDatabase( [
			'post_title'   => 'Coupon Category',
			'post_name'    => 'coupons-category-' . strtolower( $category_2 ),
			'post_content' => '[coupon couponid="loop" category="' . $category_2 . '" coupon_align="cctor_aligncenter" name="' . $deal . '"]',
		] );

		$I->amOnPage( '/coupons-category-' . strtolower( $category_2 ) . '/' );
		$I->waitForElementVisible( '.type-cctor_coupon', 10 );
		$I->makeScreenshot( 'image-coupon-shortcode-' . $category_2 );

	}

	/**
	 * @test
	 * since 3.2.0
	 */
	public function should_create_coupon_with_expiration_in_a_month_and_day_first_format( AcceptanceTester $I ) {
		$deal                 = 'Automated Deal 03';
		$terms                = 'Automated Terms 03';
		$category             = 'Food';
		$expiration           = date( 'm/d/Y', strtotime( '+1 months' ) );
		$expiration_formatted = date( 'd/m/Y', strtotime( '+1 months' ) );

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

		$I->click( '#ui-id-3' );
		$I->selectOption( '#cctor_expiration_option', 'Expiration Date' );
		$I->waitForJqueryAjax();
		$I->selectOption( '#cctor_date_format', 'Day First - DD/MM/YYYY' );
		$I->scrollTo( '#cctor_date_format' );
		//$I->executeJS( '$("#cctor_date_format").val( ' . $expiration . ');' );
		$I->fillField( '#cctor_expiration', $expiration );

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
		$I->see( 'This Coupon Expires On ' . $expiration_formatted );
		$I->SeeInPageSource( 'Place this coupon in your posts, pages, custom post types, or widgets by using the shortcode below:' );
		$I->wait( 3 );

		$I->click( 'View Coupon' );
		$I->waitForElementVisible( '.cctor-deal', 10 );
		$I->see( $deal, '.cctor-deal' );
		$I->see( $terms, '.cctor-terms' );
		$I->see( $expiration_formatted, '.cctor_expiration' );
		$I->makeScreenshot( 'expiration-day-first-print' );

		$I->havePageInDatabase( [
			'post_title'   => 'Coupons',
			'post_name'    => 'coupons',
			'post_content' => '[coupon couponid="loop" category="' . $category . '" coupon_align="cctor_aligncenter" name="' . $deal . '"]',
		] );

		$I->amOnPage( '/coupons/' );
		$I->waitForElementVisible( '.cctor-deal', 10 );
		$I->see( $deal, '.cctor-deal' );
		$I->see( $terms, '.cctor-terms' );
		$I->see( $expiration_formatted, '.cctor_expiration' );
		$I->makeScreenshot( 'expiration-day-first-shortcode' );
	}

	/**
	 * @test
	 * since 3.2.0
	 */
	public function should_create_coupon_that_is_expired_and_does_not_show( AcceptanceTester $I ) {
		$deal       = 'Automated Deal 04';
		$terms      = 'Automated Terms 04';
		$category   = 'Expired';
		$expiration = date( 'm/d/Y', strtotime( '-1 months' ) );

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

		$I->click( '#ui-id-3' );
		$I->selectOption( '#cctor_expiration_option', 'Expiration Date' );
		$I->waitForJqueryAjax();
		$I->scrollTo( '#cctor_date_format' );
		$I->fillField( '#cctor_expiration', $expiration );

		$I->scrollTo( '#cctor_coupon_categorydiv' );
		$I->click( '#cctor_coupon_category-add-toggle' );
		$I->fillField( '#newcctor_coupon_category', $category );
		$I->click( '#cctor_coupon_category-add-submit' );
		$I->wait( 3 );

		$I->scrollTo( '#submitdiv' );
		$I->click( '#publish' );
		$I->waitForElementVisible( '#message', 10 );
		$I->see( 'Coupon published.', '#message' );
		$I->see( 'This Coupon is not Showing.' );
		$I->see( 'This Coupon Expired On ' . $expiration );
		$I->wait( 3 );

		$I->click( 'View Coupon' );
		$I->SeeInPageSource( 'Coupon ' . $deal . ' expired on ' . $expiration );
		$I->makeScreenshot( 'expired-coupon-print' );

		$I->havePageInDatabase( [
			'post_title'   => 'Coupons',
			'post_name'    => 'coupons',
			'post_content' => '[coupon couponid="loop" category="' . $category . '" coupon_align="cctor_aligncenter" name="' . $deal . '"]',
		] );

		$I->amOnPage( '/coupons/' );
		$I->SeeInPageSource( 'Coupon ' . $deal . ' expired on ' . $expiration );
		$I->makeScreenshot( 'expired-coupon-shortcode' );
	}

	/**
	 * @test
	 * since 3.2.0
	 */
	public function should_and_no_link_in_terms_with_changed_deal_font_and_background_color( AcceptanceTester $I ) {
		$deal     = 'Automated Deal 05';
		$terms    = 'Automated Terms 05 <a href="https://couponcreatorplugin.com">Coupon Creator</a>';
		$category = 'Software';
		$deal_color = '#ffffff';
		$deal_bg = '#eeee23';

		$I->loginAsAdmin();

		// start check of defaults on add new coupon
		$I->amOnAdminPage( '/post-new.php?post_type=cctor_coupon' );
		$I->wait( 2 );

		// maximize window to see tabs
		$I->maximizeWindow();
		$I->selectOption( '#cctor_coupon_type', 'Default' );
		$I->waitForJqueryAjax();
		$I->fillField( '#title', $deal );
		$I->fillField( '#cctor_amount', $deal );
		$I->fillField( '#cctor_description', $terms );
		$I->executeJS( '$(".wp-picker-input-wrap").show();' );
		$I->fillField( '#cctor_colorheader', $deal_color );
		$I->fillField( '#cctor_colordiscount', $deal_bg );

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

		$I->click( 'View Coupon' );
		$I->waitForElementVisible( '.cctor-deal', 10 );
		$I->see( $deal, '.cctor-deal' );
		$I->dontSee( $terms, '.cctor-terms' );
		$I->seeInPageSource( '<h3 class="cctor-deal" style="background-color:#eeee23; color:#ffffff;">Automated Deal 05</h3>' );
		$I->seeInPageSource( '<div class="cctor-terms">Automated Terms 05 Coupon Creator</div>' );
		$I->dontSeeInPageSource( '<a href="https://couponcreatorplugin.com">Coupon Creator</a>' );
		$I->makeScreenshot( 'custom-deal-color-print' );

		$I->havePageInDatabase( [
			'post_title'   => 'Coupons',
			'post_name'    => 'coupons',
			'post_content' => '[coupon couponid="loop" category="' . $category . '" coupon_align="cctor_aligncenter" name="' . $deal . '"]',
		] );

		$I->amOnPage( '/coupons/' );
		$I->waitForElementVisible( '.cctor-deal', 10 );
		$I->see( $deal, '.cctor-deal' );
		$I->dontSee( $terms, '.cctor-terms' );
		$I->seeInPageSource( '<h3 class="cctor-deal" style="background-color:#eeee23; color:#ffffff;">Automated Deal 05</h3>' );
		$I->seeInPageSource( '<div class="cctor-terms">Automated Terms 05 Coupon Creator</div>' );
		$I->dontSeeInPageSource( '<a href="https://couponcreatorplugin.com">Coupon Creator</a>' );
		$I->makeScreenshot( 'custom-deal-color-shortcode' );
	}
}

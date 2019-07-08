<?php

namespace Pngx\Coupons\Test\Acceptance\Options;

use AcceptanceTester;
use Pngx\Coupons\Test\Acceptance\BaseAcceptanceCest;

class OptionsCest extends BaseAcceptanceCest {

	/**
	 * @test
	 * since TBD
	 */
	public function should_have_option_fields_and_updated_message( AcceptanceTester $I ) {
		$version = pngx( 'cctor' )::VERSION_NUM;
		$I->loginAsAdmin();

		$I->amOnAdminPage( '/edit.php?post_type=cctor_coupon&page=coupon-options' );
		$I->waitForElementVisible( '#ui-id-1', 10 );
		$I->see( 'Coupon Creator: ' . $version );
		$I->seeElement( '#cctor_default_template' );
		$I->seeElement( '#cctor_expiration_option' );
		$I->click( '.submit .button-primary' );
		$I->seeInSource( '<div class="updated fade"><p>Coupon Creator Options updated.</p></div>' );

	}

	/**
	 * @test
	 * since TBD
	 */
	public function should_have_default_options_in_new_coupon( AcceptanceTester $I ) {

		$I->loginAsAdmin();
		$I->amOnAdminPage( '/edit.php?post_type=cctor_coupon&page=coupon-options' );

		$I->waitForElementVisible( '#ui-id-1', 10 );
		$I->selectOption( '#cctor_expiration_option', 'Expiration Date' );
		$I->selectOption( '#cctor_default_date_format', 'Day First - DD/MM/YYYY' );

		// show the colorpicker input fields
		$I->executeJS( '$(".wp-picker-input-wrap").show();' );
		$I->fillField( '#cctor_border_color', '#b02f30' );
		$I->fillField( '#cctor_discount_bg_color', '#2a5491' );
		$I->fillField( '#cctor_discount_text_color', '#2dad6b' );
		$I->seeElement( '#pro_feature_defaults' );
		$I->click( '.submit .button-primary' );
		$I->seeInSource( '<div class="updated fade"><p>Coupon Creator Options updated.</p></div>' );

		// start check of defaults on add new coupon
		$I->amOnAdminPage( '/post-new.php?post_type=cctor_coupon' );
		$I->wait( 2 );

		// maximize window to see tabs
		$I->executeJS( '$(".wp-picker-input-wrap").show();' );

		$I->seeInField( '#cctor_colordiscount', '#2a5491' );
		$I->seeInField( '#cctor_colorheader', '#2dad6b' );
		$I->seeElement( '#cctor_pro_content_features' );

		$I->click( '#ui-id-2' );
		$I->seeInField( '#cctor_bordercolor', '#b02f30' );
		$I->seeElement( '#cctor_pro_content_style' );

		$I->click( '#ui-id-3' );
		$I->seeOptionIsSelected( '#cctor_expiration_option', 'Expiration Date' );
		$I->seeOptionIsSelected( '#cctor_date_format', 'Day First - DD/MM/YYYY' );
		$I->seeElement( '#cctor_pro_expiration_style' );

		$I->click( '#ui-id-4' );
		$I->seeElement( '#cctor_pro_links_style' );

		$I->click( '#ui-id-5' );
		$I->seeElement( '.pngx-tab-heading-help' );
	}

	/**
	 * @test
	 * since TBD
	 */
	public function should_change_nofollow_and_noindex_in_print_view( AcceptanceTester $I ) {
		$name      = 'coupon-links-01';
		$coupon_id = $I->haveCouponInDatabase( [
			'post_title' => 'Coupon Links 01',
			'post_name'  => $name,
		] );
		$I->amOnPage( '/cctor_coupon/' . $name . '/' );
		$I->canSeeInPageSource( 'noindex,nofollow' );
		$I->haveOptionInDatabase( $this->option_name, [ 'cctor_nofollow_print_template' => false ] );
		$I->reloadPage();
		$I->dontSeeInPageSource( 'noindex,nofollow' );
	}

	/**
	 * @test
	 * since TBD
	 */
	public function should_change_coupon_permalink( AcceptanceTester $I ) {
		$I->haveOptionInDatabase( $this->option_name, [ 'cctor_coupon_base' => 'coupon' ] );
		$name      = 'coupon-links-02';
		$coupon_id = $I->haveCouponInDatabase( [
			'post_title' => 'Coupon Links 02',
			'post_name'  => $name,
		] );

		$I->loginAsAdmin();
		$I->amOnAdminPage( '/edit.php?post_type=cctor_coupon&page=coupon-options' );
		$I->waitForElementVisible( '#ui-id-2', 10 );

		// this runs twice as it does not flush the first time in the acceptance test
		// it does flush the first time in manually tests
		$I->click( '#ui-id-2' );
		$I->fillField( '#cctor_coupon_base', 'coupon' );
		$I->click( '.submit .button-primary' );
		$I->seeInSource( '<div class="updated fade"><p>Coupon Creator Options updated.</p></div>' );
		$I->wait( 3 );
		$I->reloadPage();
		$I->click( '#ui-id-2' );
		$I->fillField( '#cctor_coupon_base', 'coupon-test' );
		$I->click( '.submit .button-primary' );
		$I->seeInSource( '<div class="updated fade"><p>Coupon Creator Options updated.</p></div>' );
		$I->wait( 3 );
		$I->reloadPage();

		$deal = get_post_meta( $coupon_id, 'cctor_amount', true );
		$I->amOnPage( '/coupon/' . $name . '/' );
		$I->see( $deal );

	}

	/**
	 * @test
	 * since TBD
	 */
	public function should_change_nofollow_in_shortcode_view( AcceptanceTester $I ) {
		$name      = 'coupon-links-03';
		$coupon_id = $I->haveCouponInDatabase( [
			'post_title' => 'Coupon Links 03',
			'post_name'  => $name,
		] );

		// add shortcode to a page
		$coupons_page = $I->havePageInDatabase( [
			'post_title'   => 'Coupons',
			'post_name'    => 'coupons',
			'post_content' => '[coupon couponid="' . $coupon_id . '" coupon_align="cctor_alignnone" name="' . $name . '"]',
		] );
		$I->amOnPage( '/coupons/' );
		$I->canSeeInPageSource( 'class="print-link" rel="nofollow"' );
		$I->haveOptionInDatabase( $this->option_name, [ 'cctor_nofollow_print_link' => false ] );
		$I->reloadPage();
		$I->dontSeeInPageSource( 'class="print-link" rel="nofollow"' );

	}

	/**
	 * @test
	 * since TBD
	 */
	public function should_change_disable_print_view( AcceptanceTester $I ) {
		$name      = 'coupon-links-04';
		$coupon_id = $I->haveCouponInDatabase( [
			'post_title' => 'Coupon Links 04',
			'post_name'  => $name,
		] );

		// add shortcode to a page
		$coupons_page = $I->havePageInDatabase( [
			'post_title'   => 'Coupons',
			'post_name'    => 'coupons',
			'post_content' => '[coupon couponid="' . $coupon_id . '" coupon_align="cctor_alignnone" name="' . $name . '"]',
		] );
		$I->amOnPage( '/coupons/' );
		$I->SeeElement( '.print-link' );
		$I->haveOptionInDatabase( $this->option_name, [ 'cctor_hide_print_link' => true ] );
		$I->reloadPage();
		$I->dontSeeElement( '.print-link' );
	}

	/**
	 * @test
	 * since TBD
	 */
	public function should_have_custom_styles( AcceptanceTester $I ) {
		$name      = 'coupon-links-05';
		$coupon_id = $I->haveCouponInDatabase( [
			'post_title' => 'Coupon Links 05',
			'post_name'  => $name,
		] );
		// add shortcode to a page
		$coupons_page = $I->havePageInDatabase( [
			'post_title'   => 'Coupons',
			'post_name'    => 'coupons',
			'post_content' => '[coupon couponid="' . $coupon_id . '" coupon_align="cctor_alignnone" name="' . $name . '"]',
		] );

		$I->loginAsAdmin();
		$I->amOnAdminPage( '/edit.php?post_type=cctor_coupon&page=coupon-options' );
		$I->waitForElementVisible( '#ui-id-3', 10 );
		$I->click( '#ui-id-3' );
		$I->fillField( '#cctor_custom_css', '.cctor_coupon_container .cctor-deal {background-color: pink !important;}' );
		$I->click( '.submit .button-primary' );
		$I->seeInSource( '<div class="updated fade"><p>Coupon Creator Options updated.</p></div>' );
		$I->wait( 3 );

		$I->amOnPage( '/coupons/' );
		$I->waitForElementVisible( '.cctor-deal', 10 );
		$I->canSeeInPageSource( 'background-color: pink !important;' );
		$I->amOnPage( '/cctor_coupon/' . $name . '/' );
		$I->waitForElementVisible( '.cctor-deal', 10 );
		$I->canSeeInPageSource( 'background-color: pink !important;' );
	}

	/**
	 * @test
	 * since TBD
	 */
	public function should_disable_wpautop_filter( AcceptanceTester $I ) {
		$name      = 'coupon-links-06';
		$coupon_id = $I->haveCouponInDatabase( [
			'post_title'   => 'Coupon Links 06',
			'post_name'    => $name,
			'post_content' => 'WPAutoP Filter Test',
			'meta_input'   => [
				'cctor_description' => 'WPAutoP Filter Test',
			],
		] );

		$I->loginAsAdmin();

		// test no autop
		$I->amOnPage( '/cctor_coupon/' . $name . '/' );
		$I->waitForElementVisible( '.cctor-deal', 10 );
		$I->seeInSource( '<div class="cctor-terms">WPAutoP Filter Test</div>' );

		$I->amOnAdminPage( '/edit.php?post_type=cctor_coupon&page=coupon-options' );
		$I->waitForElementVisible( '#ui-id-3', 10 );
		$I->click( '#ui-id-3' );
		$I->uncheckOption( '#cctor_wpautop' );
		$I->click( '.submit .button-primary' );
		$I->seeInSource( '<div class="updated fade"><p>Coupon Creator Options updated.</p></div>' );
		$I->wait( 3 );

		$I->amOnPage( '/cctor_coupon/' . $name . '/' );
		$I->waitForElementVisible( '.cctor-deal', 10 );
		$I->seeInSource( '<p>WPAutoP Filter Test</p>' );

	}

	/**
	 * @test
	 * since TBD
	 */
	public function should_disable_print_view_base_css( AcceptanceTester $I ) {
		$name      = 'coupon-links-07';
		$coupon_id = $I->haveCouponInDatabase( [
			'post_title' => 'Coupon Links 07',
			'post_name'  => $name,
		] );

		$I->loginAsAdmin();

		// test no autop
		$I->amOnPage( '/cctor_coupon/' . $name . '/' );
		$I->waitForElementVisible( '.cctor-deal', 10 );
		$I->seeInSource( '<!--Default Styling -->' );

		$I->amOnAdminPage( '/edit.php?post_type=cctor_coupon&page=coupon-options' );
		$I->waitForElementVisible( '#ui-id-3', 10 );
		$I->click( '#ui-id-3' );
		$I->checkOption( '#cctor_print_base_css' );
		$I->click( '.submit .button-primary' );
		$I->seeInSource( '<div class="updated fade"><p>Coupon Creator Options updated.</p></div>' );
		$I->wait( 3 );

		$I->amOnPage( '/cctor_coupon/' . $name . '/' );
		$I->waitForElementVisible( '.cctor-deal', 10 );
		$I->dontSeeInPageSource( '<!--Default Styling -->' );

	}

	/**
	 * @test
	 * since TBD
	 */
	public function should_add_coupons_back_to_search( AcceptanceTester $I ) {
		$name      = 'coupon-links-08';
		$coupon_id = $I->haveCouponInDatabase( [
			'post_title' => 'Coupon Links 08',
			'post_name'  => $name,
		] );

		$I->loginAsAdmin();

		// test no autop
		$I->amOnPage( '/?s=Coupon+Links+08' );
		$I->waitForElementVisible( '.page-title', 10 );
		$I->seeInSource( 'Nothing Found' );
		$I->dontSeeInPageSource( get_the_permalink( $coupon_id ) );

		$I->amOnAdminPage( '/edit.php?post_type=cctor_coupon&page=coupon-options' );
		$I->waitForElementVisible( '#ui-id-3', 10 );
		$I->click( '#ui-id-3' );
		$I->checkOption( '#coupon-search' );
		$I->click( '.submit .button-primary' );
		$I->seeInSource( '<div class="updated fade"><p>Coupon Creator Options updated.</p></div>' );
		$I->wait( 3 );

		$I->amOnPage( '/?s=Coupon+Links+08' );
		$I->waitForElementVisible( '.page-title', 10 );
		$I->seeInPageSource( get_the_permalink( $coupon_id ) );

	}

	/**
	 * @test
	 * since TBD
	 */
	public function should_see_help_links( AcceptanceTester $I ) {

		$I->loginAsAdmin();
		$I->amOnAdminPage( '/edit.php?post_type=cctor_coupon&page=coupon-options' );
		$I->waitForElementVisible( '#ui-id-5', 10 );
		$I->click( '#ui-id-4' );
		$I->seeElement( '#pro_feature_templating' );
		$I->click( '#ui-id-5' );
		$I->see( 'Standard and Default Templates' );
		$I->see( 'An Overview of Coupon Creator Options' );
		$I->see( 'Using the Dimension OptionsPro' );
		$I->see( 'Troubleshooting Conflicts' );
		$I->seeElement( '.pngx-support' );

	}

	/**
	 * @test
	 * since TBD
	 */
	public function should_see_system_info_and_copy_button( AcceptanceTester $I ) {

		$I->loginAsAdmin();
		$I->amOnAdminPage( '/edit.php?post_type=cctor_coupon&page=coupon-options' );

		$I->waitForElementVisible( '#ui-id-1', 10 );
		$I->click( '#ui-id-6' );
		$I->seeElement( '.pngx-system-info-copy-btn' );
		$I->seeElement( '.pngx-support-stats' );
		$I->see( 'System Info' );

	}

	/**
	 * @test
	 * since TBD
	 */
	public function should_reset__options( AcceptanceTester $I ) {

		$I->loginAsAdmin();
		$I->amOnAdminPage( '/edit.php?post_type=cctor_coupon&page=coupon-options' );


		// set initial values
		$I->waitForElementVisible( '#ui-id-1', 10 );
		$I->selectOption( '#cctor_expiration_option', 'Expiration Date' );
		$I->executeJS( '$(".wp-picker-input-wrap").show();' );
		$I->fillField( '#cctor_border_color', '#b02f30' );
		$I->scrollTo( '.pngx-wrapper' );
		$I->click( '#ui-id-2' );
		$I->fillField( '#cctor_coupon_base', 'coupon' );
		$I->click( '#ui-id-3' );
		$I->uncheckOption( '#cctor_wpautop' );
		$I->click( '.submit .button-primary' );
		$I->seeInSource( '<div class="updated fade"><p>Coupon Creator Options updated.</p></div>' );

		// check new values
		$I->amOnAdminPage( '/' );
		$I->wait( 5 );
		$I->see( 'Dashboard' );
		$I->amOnAdminPage( '/edit.php?post_type=cctor_coupon&page=coupon-options' );
		$I->waitForElementVisible( '#ui-id-1', 10 );
		$I->seeOptionIsSelected( '#cctor_expiration_option', 'Expiration Date' );
		$I->executeJS( '$(".wp-picker-input-wrap").show();' );
		$I->seeInField( '#cctor_border_color', '#b02f30' );
		$I->scrollTo( '.pngx-wrapper' );
		$I->click( '#ui-id-2' );
		$I->seeInField( '#cctor_coupon_base', 'coupon' );
		$I->click( '#ui-id-3' );
		$I->dontSeeCheckboxIsChecked( '#cctor_wpautop' );

		// reset
		$I->click( '#ui-id-7' );
		$I->checkOption( '#reset_theme' );
		$I->click( '.submit .button-primary' );
		$I->seeInSource( '<div class="updated fade"><p>Coupon Creator Options updated.</p></div>' );

		// back to default values
		$I->amOnAdminPage( '/' );
		$I->wait( 5 );
		$I->see( 'Dashboard' );
		$I->amOnAdminPage( '/edit.php?post_type=cctor_coupon&page=coupon-options' );
		$I->waitForElementVisible( '#ui-id-1', 10 );
		$I->seeOptionIsSelected( '#cctor_expiration_option', 'Ignore Expiration' );
		$I->executeJS( '$(".wp-picker-input-wrap").show();' );
		$I->seeInField( '#cctor_border_color', '#81d742' );
		$I->scrollTo( '.pngx-wrapper' );
		$I->click( '#ui-id-2' );
		$I->seeInField( '#cctor_coupon_base', '' );
		$I->click( '#ui-id-3' );
		$I->SeeCheckboxIsChecked( '#cctor_wpautop' );

	}
}

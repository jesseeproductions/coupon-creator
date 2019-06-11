<?php

namespace Pngx\Coupons\Test\Acceptance\Shortcode;

use AcceptanceTester;
use Pngx\Coupons\Test\Acceptance\BaseAcceptanceCest;

class BlocksCest extends BaseAcceptanceCest {

	/**
	 * @test
	 * since TBD
	 */
	public function should_see_block_editor_title_fields_and_see_coupon_block( AcceptanceTester $I ) {

		$I->amOnAdminPage( '/post-new.php?post_type=page' );
		$I->maximizeWindow();
		$I->waitForElementVisible( '.block-editor', 10 );
		$I->maximizeWindow();

		//disable tooltips that are set by cookies
		$I->executeJS( 'wp.data.dispatch("core/nux").disableTips();' );
		$I->seeElement( '.editor-post-title' );
		$I->seeElement( '.editor-post-title__input' );
		$I->fillField( '.editor-post-title__input', 'Coupons' );
		$I->seeElement( '.editor-inserter__toggle' );
		$I->click( '.editor-inserter__toggle' );
		$I->seeElement( '.editor-inserter__search' );
		$I->fillField( '.editor-inserter__search', 'coupon' );
		$I->seeElement( '.editor-block-list-item-pngx-coupon' );

	}

	/**
	 * @test
	 * since TBD
	 */
	public function should_add_coupon_block_with_no_errors_when_no_coupons_published( AcceptanceTester $I ) {

		$I->amOnAdminPage( '/post-new.php?post_type=page' );
		$I->maximizeWindow();
		$I->waitForElementVisible( '.block-editor', 10 );
		$I->maximizeWindow();

		//disable tooltips that are set by cookies
		$I->executeJS( 'wp.data.dispatch("core/nux").disableTips();' );
		$I->fillField( '.editor-post-title__input', 'Coupons' );
		$I->click( '.editor-inserter__toggle' );
		$I->seeElement( '.editor-inserter__search' );
		$I->fillField( '.editor-inserter__search', 'coupon' );
		$I->seeElement( '.editor-block-list-item-pngx-coupon' );
		$I->click( '.editor-block-list-item-pngx-coupon' );
		$I->dontSeeInPageSource( 'This block has encountered an error and cannot be previewed.' );
		$I->seeInPageSource( 'Display a single or group of coupons.' );
	}

	/**
	 * @test
	 * since TBD
	 */
	public function should_add_coupon_block_and_display_single_coupon( AcceptanceTester $I ) {
		$name      = 'coupon-links-01';
		$title     = 'Coupon Links 01';
		$coupon_id = $I->haveCouponInDatabase( [
			'post_title' => $title,
			'post_name'  => $name,
		] );

		$I->amOnAdminPage( '/post-new.php?post_type=page' );
		$I->maximizeWindow();
		$I->waitForElementVisible( '.block-editor', 10 );
		$I->maximizeWindow();

		//disable tooltips that are set by cookies
		$I->executeJS( 'wp.data.dispatch("core/nux").disableTips();' );
		$I->fillField( '.editor-post-title__input', 'Coupons' );
		$I->click( '.editor-inserter__toggle' );
		$I->seeElement( '.editor-inserter__search' );
		$I->fillField( '.editor-inserter__search', 'coupon' );
		$I->seeElement( '.editor-block-list-item-pngx-coupon' );
		$I->click( '.editor-block-list-item-pngx-coupon' );
		$I->seeInPageSource( 'Display a single or group of coupons.' );
		$I->selectOption( '.coupon-select .components-select-control__input', $title );
		$I->wait( 5 );
		$terms = get_post_meta( $coupon_id, 'cctor_description', true );
		$I->see( $terms, '.cctor-terms' );
		$I->click( '.editor-post-publish-panel__toggle' );
		$I->wait( 2 );
		$I->click( '.editor-post-publish-button' );
		$I->wait( 2 );
		$I->click( '.components-notice__action.is-link' );
		$I->waitForElementVisible( '.cctor-deal', 10 );
		$I->see( $terms, '.cctor-terms' );

	}

	/**
	 * @test
	 * since TBD
	 */
	public function should_add_coupon_block_with_single_coupon_and_align_right( AcceptanceTester $I ) {
		$name      = 'coupon-links-02';
		$title     = 'Coupon Links 02';
		$coupon_id = $I->haveCouponInDatabase( [
			'post_title' => $title,
			'post_name'  => $name,
		] );

		$I->amOnAdminPage( '/post-new.php?post_type=page' );
		$I->maximizeWindow();
		$I->waitForElementVisible( '.block-editor', 10 );
		$I->maximizeWindow();

		//disable tooltips that are set by cookies
		$I->executeJS( 'wp.data.dispatch("core/nux").disableTips();' );
		$I->fillField( '.editor-post-title__input', 'Coupons' );
		$I->click( '.editor-inserter__toggle' );
		$I->seeElement( '.editor-inserter__search' );
		$I->fillField( '.editor-inserter__search', 'coupon' );
		$I->seeElement( '.editor-block-list-item-pngx-coupon' );
		$I->click( '.editor-block-list-item-pngx-coupon' );
		$I->seeInPageSource( 'Display a single or group of coupons.' );
		$I->selectOption( '.coupon-select .components-select-control__input', $title );
		$I->wait( 5 );

		// disable term check for now as it was getting the first coupon terms made in this class
		//$terms = get_post_meta( $coupon_id, 'cctor_description', true );
		//$I->see( $terms, '.cctor-terms' );
		$I->selectOption( '.coupon-align-select .components-select-control__input', 'Align Right' );
		$I->wait( 5 );
		$I->seeElement( '.cctor_coupon_container.cctor_alignright' );
		$I->click( '.editor-post-publish-panel__toggle' );
		$I->wait( 2 );
		$I->click( '.editor-post-publish-button' );
		$I->wait( 2 );
		$I->click( '.components-notice__action.is-link' );
		$I->waitForElementVisible( '.cctor-deal', 10 );
		$I->seeElement( '.cctor_coupon_container.cctor_alignright' );
		//$I->see( $terms, '.cctor-terms' );

	}

	/**
	 * @test
	 * since TBD
	 */
	public function should_add_coupon_block_with_single_coupon_and_align_center( AcceptanceTester $I ) {
		$name      = 'coupon-links-03';
		$title     = 'Coupon Links 03';
		$coupon_id = $I->haveCouponInDatabase( [
			'post_title' => $title,
			'post_name'  => $name,
		] );

		$I->amOnAdminPage( '/post-new.php?post_type=page' );
		$I->maximizeWindow();
		$I->waitForElementVisible( '.block-editor', 10 );
		$I->maximizeWindow();

		//disable tooltips that are set by cookies
		$I->executeJS( 'wp.data.dispatch("core/nux").disableTips();' );
		$I->fillField( '.editor-post-title__input', 'Coupons' );
		$I->click( '.editor-inserter__toggle' );
		$I->seeElement( '.editor-inserter__search' );
		$I->fillField( '.editor-inserter__search', 'coupon' );
		$I->seeElement( '.editor-block-list-item-pngx-coupon' );
		$I->click( '.editor-block-list-item-pngx-coupon' );
		$I->seeInPageSource( 'Display a single or group of coupons.' );
		$I->selectOption( '.coupon-select .components-select-control__input', $title );
		$I->wait( 5 );

		// disable term check for now as it was getting the first coupon terms made in this class
		//$terms = get_post_meta( $coupon_id, 'cctor_description', true );
		//$I->see( $terms, '.cctor-terms' );
		$I->selectOption( '.coupon-align-select .components-select-control__input', 'Align Center' );
		$I->wait( 5 );
		$I->seeElement( '.cctor_coupon_container.cctor_aligncenter' );
		$I->click( '.editor-post-publish-panel__toggle' );
		$I->wait( 2 );
		$I->click( '.editor-post-publish-button' );
		$I->wait( 2 );
		$I->click( '.components-notice__action.is-link' );
		$I->waitForElementVisible( '.cctor-deal', 10 );
		$I->seeElement( '.cctor_coupon_container.cctor_aligncenter' );
		//$I->see( $terms, '.cctor-terms' );

	}

	/**
	 * @test
	 * since TBD
	 */
	public function should_have_custom_styles_in_block_editor( AcceptanceTester $I ) {
		$name      = 'coupon-links-04';
		$title     = 'Coupon Links 04';
		$coupon_id = $I->haveCouponInDatabase( [
			'post_title' => $title,
			'post_name'  => $name,
		] );

		$I->amOnAdminPage( '/edit.php?post_type=cctor_coupon&page=coupon-options' );
		$I->maximizeWindow();
		$I->waitForElementVisible( '#ui-id-3', 10 );
		$I->click( '#ui-id-3' );
		$I->fillField( '#cctor_custom_css', '.cctor_coupon_container .cctor-deal {background-color: pink !important;}' );
		$I->click( '.submit .button-primary' );
		$I->seeInSource( '<div class="updated fade"><p>Coupon Creator Options updated.</p></div>' );
		$I->wait( 3 );

		$I->maximizeWindow();
		$I->amOnAdminPage( '/post-new.php?post_type=page' );
		$I->maximizeWindow();
		$I->waitForElementVisible( '.block-editor', 10 );
		$I->maximizeWindow();

		//disable tooltips that are set by cookies
		$I->executeJS( 'wp.data.dispatch("core/nux").disableTips();' );
		$I->fillField( '.editor-post-title__input', 'Coupons' );
		$I->click( '.editor-inserter__toggle' );
		$I->seeElement( '.editor-inserter__search' );
		$I->fillField( '.editor-inserter__search', 'coupon' );
		$I->seeElement( '.editor-block-list-item-pngx-coupon' );
		$I->click( '.editor-block-list-item-pngx-coupon' );
		$I->seeInPageSource( 'Display a single or group of coupons.' );
		$I->selectOption( '.coupon-select .components-select-control__input', 'All Coupons' );
		$I->wait( 5 );
		$I->seeElement( '.type-cctor_coupon' );
		$I->canSeeInPageSource( 'background-color: pink !important;' );
		$I->click( '.editor-post-publish-panel__toggle' );
		$I->wait( 2 );
		$I->click( '.editor-post-publish-button' );
		$I->wait( 2 );
		$I->click( '.components-notice__action.is-link' );
		$I->waitForElementVisible( '.cctor-deal', 10 );
		$I->seeElement( '.type-cctor_coupon' );
		$I->canSeeInPageSource( 'background-color: pink !important;' );

	}

	/**
	 * @test
	 * since TBD
	 */
	public function should_have_all_coupons_and_align_right( AcceptanceTester $I ) {

		$I->haveManyCouponsInDatabase( 5, [
			'categories' => ['Food'],
		] );

		$I->amOnAdminPage( '/post-new.php?post_type=page' );
		$I->maximizeWindow();
		$I->waitForElementVisible( '.block-editor', 10 );
		$I->maximizeWindow();

		//disable tooltips that are set by cookies
		$I->executeJS( 'wp.data.dispatch("core/nux").disableTips();' );
		$I->fillField( '.editor-post-title__input', 'Coupons' );
		$I->click( '.editor-inserter__toggle' );
		$I->seeElement( '.editor-inserter__search' );
		$I->fillField( '.editor-inserter__search', 'coupon' );
		$I->seeElement( '.editor-block-list-item-pngx-coupon' );
		$I->click( '.editor-block-list-item-pngx-coupon' );
		$I->seeInPageSource( 'Display a single or group of coupons.' );
		$I->selectOption( '.coupon-select .components-select-control__input', 'All Coupons' );
		$I->wait( 5 );
		$I->selectOption( '.coupon-align-select .components-select-control__input', 'Align Right' );
		$I->wait( 5 );
		$I->seeElement( '.cctor_coupon_container.cctor_alignright' );
		$I->click( '.editor-post-publish-panel__toggle' );
		$I->wait( 2 );
		$I->click( '.editor-post-publish-button' );
		$I->wait( 2 );
		$I->click( '.components-notice__action.is-link' );
		$I->waitForElementVisible( '.cctor-deal', 10 );
		$I->seeElement( '.cctor_coupon_container.cctor_alignright' );

	}

	/**
	 * @test
	 * since TBD
	 */
	public function should_have_food_category_coupons_and_align_center( AcceptanceTester $I ) {

		$I->haveManyCouponsInDatabase( 5, [
			'categories' => ['Food'],
		] );
		$I->haveManyCouponsInDatabase( 3, [
			'categories' => ['Clothing'],
		] );

		$I->amOnAdminPage( '/post-new.php?post_type=page' );
		$I->maximizeWindow();
		$I->waitForElementVisible( '.block-editor', 10 );
		$I->maximizeWindow();

		//disable tooltips that are set by cookies
		$I->executeJS( 'wp.data.dispatch("core/nux").disableTips();' );
		$I->fillField( '.editor-post-title__input', 'Coupons' );
		$I->click( '.editor-inserter__toggle' );
		$I->seeElement( '.editor-inserter__search' );
		$I->fillField( '.editor-inserter__search', 'coupon' );
		$I->seeElement( '.editor-block-list-item-pngx-coupon' );
		$I->click( '.editor-block-list-item-pngx-coupon' );
		$I->seeInPageSource( 'Display a single or group of coupons.' );
		$I->selectOption( '.coupon-select .components-select-control__input', 'All Coupons' );
		$I->wait( 5 );
		$I->seeNumberOfElements('.type-cctor_coupon', 8);
		$I->selectOption( '.coupon-category-select .components-select-control__input', 'Food' );
		$I->wait( 5 );
		$I->seeNumberOfElements('.type-cctor_coupon', 5);
		$I->selectOption( '.coupon-align-select .components-select-control__input', 'Align Center' );
		$I->wait( 5 );
		$I->seeElement( '.cctor_coupon_container.cctor_aligncenter' );
		$I->click( '.editor-post-publish-panel__toggle' );
		$I->wait( 2 );
		$I->click( '.editor-post-publish-button' );
		$I->wait( 2 );
		$I->click( '.components-notice__action.is-link' );
		$I->waitForElementVisible( '.cctor-deal', 10 );
		$I->seeElement( '.cctor_coupon_container.cctor_aligncenter' );

	}

	/**
	 * @test
	 * since TBD
	 */
	public function should_have_clothing_category_coupons_and_align_left( AcceptanceTester $I ) {

		$I->haveManyCouponsInDatabase( 5, [
			'categories' => ['Food'],
		] );
		$I->haveManyCouponsInDatabase( 3, [
			'categories' => ['Clothing'],
		] );

		$I->amOnAdminPage( '/post-new.php?post_type=page' );
		$I->maximizeWindow();
		$I->waitForElementVisible( '.block-editor', 10 );
		$I->maximizeWindow();

		//disable tooltips that are set by cookies
		$I->executeJS( 'wp.data.dispatch("core/nux").disableTips();' );
		$I->fillField( '.editor-post-title__input', 'Coupons' );
		$I->click( '.editor-inserter__toggle' );
		$I->seeElement( '.editor-inserter__search' );
		$I->fillField( '.editor-inserter__search', 'coupon' );
		$I->seeElement( '.editor-block-list-item-pngx-coupon' );
		$I->click( '.editor-block-list-item-pngx-coupon' );
		$I->seeInPageSource( 'Display a single or group of coupons.' );
		$I->selectOption( '.coupon-select .components-select-control__input', 'All Coupons' );
		$I->wait( 5 );
		$I->seeNumberOfElements('.type-cctor_coupon', 8);
		$I->selectOption( '.coupon-category-select .components-select-control__input', 'Clothing' );
		$I->wait( 5 );
		$I->seeNumberOfElements('.type-cctor_coupon', 3);
		$I->selectOption( '.coupon-align-select .components-select-control__input', 'Align Left' );
		$I->wait( 5 );
		$I->seeElement( '.cctor_coupon_container.cctor_alignleft' );
		$I->click( '.editor-post-publish-panel__toggle' );
		$I->wait( 2 );
		$I->click( '.editor-post-publish-button' );
		$I->wait( 2 );
		$I->click( '.components-notice__action.is-link' );
		$I->waitForElementVisible( '.cctor-deal', 10 );
		$I->seeElement( '.cctor_coupon_container.cctor_alignleft' );

	}
}

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

		$I->loginAsAdmin();
		$I->maximizeWindow();
		$I->amOnAdminPage( '/post-new.php?post_type=page' );
		$I->maximizeWindow();
		$I->waitForElementVisible( '.block-editor', 10 );
		$I->maximizeWindow();
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

		$I->loginAsAdmin();
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

		$terms = get_post_meta( $coupon_id, 'cctor_description', true );

		$I->loginAsAdmin();
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
		$I->selectOption( '.wp-block-pngx-coupon .coupon-chooser .components-select-control__input', $title );
		$I->wait( 5 );
		$I->see( $terms );
		$I->click( '.editor-post-publish-panel__toggle' );
		$I->wait( 2 );
		$I->click( '.editor-post-publish-button' );
		$I->wait( 2 );
		$I->click( '.components-notice__action.is-link' );
		$I->waitForElementVisible( '.cctor-deal', 10 );
		$I->see( $terms );

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

		$terms = get_post_meta( $coupon_id, 'cctor_description', true );

		$I->loginAsAdmin();
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
		$I->selectOption( '.coupon-chooser .components-select-control__input', $title );
		$I->wait( 5 );
		$I->see( $terms );
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
		$I->see( $terms );

	}

	/**
	 * @test
	 * since TBD
	 */
	public function should_add_coupon_block_with_single_coupon_and_align_center( AcceptanceTester $I ) {
		$name      = 'coupon-links-02';
		$title     = 'Coupon Links 02';
		$coupon_id = $I->haveCouponInDatabase( [
			'post_title' => $title,
			'post_name'  => $name,
		] );

		$terms = get_post_meta( $coupon_id, 'cctor_description', true );

		$I->loginAsAdmin();
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
		$I->selectOption( '.coupon-chooser .components-select-control__input', $title );
		$I->wait( 5 );
		$I->see( $terms );
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
		$I->see( $terms );

	}

}

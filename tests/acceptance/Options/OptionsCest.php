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

		$I->loginAsAdmin();

		$I->amOnAdminPage( '/edit.php?post_type=cctor_coupon&page=coupon-options' );
		$I->waitForElementVisible( '#ui-id-1', 10 );
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
		// maximize window to see tabs
		$I->maximizeWindow();
		$I->waitForElementVisible( '#ui-id-1', 10 );
		$I->selectOption( '#cctor_expiration_option', 'Expiration Date' );
		$I->selectOption( '#cctor_default_date_format', 'Day First - DD/MM/YYYY' );

		// show the colorpicker input fields
		$I->executeJS('$(".wp-picker-input-wrap").show();');
		$I->fillField( '#cctor_border_color', '#b02f30' );
		$I->fillField( '#cctor_discount_bg_color', '#2a5491' );
		$I->fillField( '#cctor_discount_text_color', '#2dad6b' );
		$I->seeElement( '#pro_feature_defaults' );
		$I->click( '.submit .button-primary' );
		$I->seeInSource( '<div class="updated fade"><p>Coupon Creator Options updated.</p></div>' );

		// start check of defaults on add new coupon
		$I->amOnAdminPage( '/post-new.php?post_type=cctor_coupon' );
		$I->wait(2);

		// maximize window to see tabs
		$I->maximizeWindow();
		$I->executeJS('$(".wp-picker-input-wrap").show();');

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
}

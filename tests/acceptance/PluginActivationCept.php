<?php
$I = new AcceptanceTester( $scenario );
$I->wantTo( 'activate Coupon Creator on a fresh WordPress installation and deactivate it' );

// set the `active_plugins` in the database to an empty array to make sure no plugin is active
// by default the database dump has The Events Calender active
$I->haveOptionInDatabase( 'active_plugins', [] );

$I->loginAsAdmin();
$I->amOnPluginsPage();
$I->seePluginDeactivated( 'coupon-creator' );

$I->activatePlugin( [ 'coupon-creator' ] );

// to get back to the plugins page if redirected after the plugin activation
$I->amOnPluginsPage();

$I->seePluginActivated( 'coupon-creator' );

$I->deactivatePlugin( 'coupon-creator' );

// to get back to the plugins page if redirected after the plugin activation
$I->amOnPluginsPage();

$I->seePluginDeactivated( 'coupon-creator' );

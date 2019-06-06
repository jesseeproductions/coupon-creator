<?php

namespace Pngx\Coupons\Test\Acceptance;

use AcceptanceTester;

class BaseAcceptanceCest {

	/**
	 * @var string
	 */
	protected $option_name = 'coupon_creator_options';

	/**
	 * @var \tad\WPBrowser\Module\WPLoader\FactoryStore
	 */
	protected $factory;

	public function _before( AcceptanceTester $I ) {

		$this->factory = $I->factory();

	}

}
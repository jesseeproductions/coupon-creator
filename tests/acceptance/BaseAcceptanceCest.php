<?php

namespace Tribe\Community\Test\Acceptance;

use AcceptanceTester;

class BaseAcceptanceCest {

	/**
	 * @var \tad\WPBrowser\Module\WPLoader\FactoryStore
	 */
	protected $factory;

	public function _before( AcceptanceTester $I ) {

		$this->factory = $I->factory();


	}

}
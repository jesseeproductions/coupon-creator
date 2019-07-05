<?php

namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Acceptance extends \Codeception\Module {

	/**
	 * Wait for Ajax to complete
	 * http://phptest.club/t/how-to-wait-for-page-totaly-load/235/8
	 *
	 * @since 3.0
	 *
	 * @param AcceptanceTester $I
	 * @param int $timeout
	 */
	public function waitForAjax( \AcceptanceTester $I, $timeout = 60)
	{
	    $I->waitForJS(
	        'return !!window.jQuery && window.jQuery.active == 0;',
	        $timeout
	    );
	}


	/**
	 * Wait for Page to Load
	 * http://phptest.club/t/how-to-wait-for-page-totaly-load/235/8
	 *
	 * @since 3.0
	 *
	 * @param AcceptanceTester $I
	 * @param int $timeout
	 */
	public function waitForPageLoad( \AcceptanceTester $I, $timeout = 60)
	{
	    $I->waitForJS(
	        'return document.readyState == "complete"',
	        $timeout
	    );
	    $this->waitForAjax($I, $timeout);
	}

}

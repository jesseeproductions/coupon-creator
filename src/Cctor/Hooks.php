<?php
/**
 * Handles hooking all the actions and filters used by the module.
 *
 * @since 3.4.0
 *
 * @package Cctor\Coupon;
 */

namespace Cctor\Coupon;

use Cctor\Coupon\Templates\Admin_Template;
use Pngx__Admin__Fields;
use tad_DI52_ServiceProvider;
use Pngx\Template;

/**
 * Class Hooks.
 *
 * @since   3.4.0
 *
 * @package Cctor\Coupon;
 */
class Hooks extends tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 *
	 * @since 3.4.0
	 */
	public function register() {
		$this->container->singleton( static::class, $this );
		$this->container->singleton( Admin_Template::class, Admin_Template::class );
		$this->container->singleton( Pngx__Admin__Fields::class, Pngx__Admin__Fields::class );

		$this->add_filters();
	}

	/**
	 * Hooks the filters.
	 *
	 * @since 3.4.0
	 */
	public function add_filters() {
		add_filter( 'pngx_template_path_list', [ $this, 'filter_template_path_list' ], 15, 2 );
	}

	/**
	 * Adds plugin-engine/src/admin-views to the list of folders it will look up to find templates.
	 *
	 * @since 3.4.0
	 *
	 * @param array<string> $folders  The current list of folders that will be searched template files.
	 * @param Template      $template Which template instance we are dealing with.
	 *
	 * @return array The filtered list of folders that will be searched for the templates.
	 */
	public function filter_template_path_list( array $folders, Template $template ) {
		return pngx( Admin_Template::class )->filter_template_path_list( $folders, $template );
	}
}

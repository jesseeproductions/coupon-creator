<?php
/**
 * Admin Template.
 *
 * @since 0.1.0
 *
 * @package Pngx\Volt_Vectors\Templates
 */

namespace Cctor\Coupon\Templates;

use Cctor__Coupon__Main as Coupon_Main;
use Pngx\Template;
use Pngx__Main;

/**
 * Class Admin_Template
 *
 * @since 0.1.0
 *
 * @package Pngx\Volt_Vectors\Templates
 */
class Admin_Template extends Template {

	/**
	 * Template constructor.
	 *
	 * Sets the correct paths for templates for event status.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
		$this->set_template_origin( Coupon_Main::instance() );
		$this->set_template_folder( 'Coupon_Creator/admin-views' );

		// We specifically don't want to look up template files here.
		$this->set_template_folder_lookup( false );

		// Configures this templating class extract variables.
		$this->set_template_context_extract( true );
	}

	/**
	 * Filters the list of folders TEC will look up to find templates to add the ones defined by PRO.
	 *
	 * @since 0.1.0
	 *
	 * @param array    $folders  The current list of folders that will be searched template files.
	 * @param Template $template Which template instance we are dealing with.
	 *
	 * @return array The filtered list of folders that will be searched for the templates.
	 */
	public function filter_template_path_list( array $folders, Template $template ) {
		$path   = (array) rtrim( Pngx__Main::instance()->plugin_path, '/' );

		// Pick up if the folder needs to be added to the public template path.
		$folder = [ 'src/admin-views' ];

		if ( ! empty( $folder ) ) {
			$path = array_merge( $path, $folder );
		}

		$folders[ Coupon_Main::SLUG ] = [
			'id'        => Pngx__Main::instance()::SLUG,
			'namespace' => Pngx__Main::instance()::SLUG,
			'priority'  => 20,
			'path'      => implode( DIRECTORY_SEPARATOR, $path ),
		];

		return $folders;
	}
}

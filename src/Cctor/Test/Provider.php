<?php
/**
 * Handles hooking all the actions and filters used for Test.
 *
 * @since 0.1.0
 *
 * @package Cctor\Coupon\Test;
 */

namespace Cctor\Coupon\Test;

use tad_DI52_ServiceProvider;
use Pngx\Traits\With_Nonce_Routes;
use WP_Post;

/**
 * Class Provider
 *
 * @since 0.1.0
 *
 * @package Cctor\Coupon\Test;
 */
class Provider extends tad_DI52_ServiceProvider {

	use With_Nonce_Routes;

	/**
	 * Binds and sets up implementations.
	 *
	 * @since 0.1.0
	 */
	public function register() {
		$this->container->singleton( static::class, $this );
		$this->container->singleton( Meta::class, Meta::class );
		$this->container->singleton( Options::class, Options::class );

		$this->add_actions();
		$this->add_filters();

		/**
		 * Allows filtering of the capability required to use the integration ajax features.
		 *
		 * @since 0.1.0
		 *
		 * @param string $ajax_capability The capability required to use the ajax features, default manage_options.
		 */
		$ajax_capability = apply_filters( 'test_admin_ajax_capability', 'edit_posts' );

		$this->route_admin_by_nonce( $this->admin_routes(), $ajax_capability );
	}

	/**
	 * Hooks the action required for the Integration.
	 *
	 * @since 0.1.0
	 */
	protected function add_actions() {
		// Meta.
		add_filter( 'cctor_filter_meta_fields', [ $this, 'add_meta' ], 20, 1 );
		add_action( 'save_post', [ $this, 'save_meta' ], 10, 2 );
		add_filter( 'cctor_filter_meta_tabs', [ $this, 'add_tab' ] );
		add_filter( 'pngx_meta_fields', [ $this, 'get_fields' ], 5 );

		// Meta and Option Fields Display.
		add_action( 'pngx_field_types', [ $this, 'display_field' ], 5, 5 );
	}

	/**
	 * Save Meta Fields.
	 *
	 * @param int     $post_id The post ID.
	 * @param WP_Post $post    An instance of the post object.
	 */
	public function save_meta( $post_id, $post ) {
		pngx( Meta::class )->save_meta( $post_id, $post );
	}

	/**
	 * Meta Setup.
	 *
	 * @since 0.1.0
	 */
	public function add_meta( $fields = [] ) {
		return pngx( Meta::class )->add_meta( $fields );
	}

	/**
	 * Add Meta Boxes.
	 *
	 * @since 0.1.0
	 */
	public function add_tab( $tabs ) {
		return pngx( Meta::class )->add_tab( $tabs );
	}

	/**
	 * Display Fields by Type.
	 *
	 * @since 0.1.0
	 *
	 * @param array               $field
	 *
	 * @param array<string|mixed> $field      The field options in an array.
	 * @param array<string|mixed> $options    The field options in an array.
	 * @param string              $options_id The Option ID for the field.
	 * @param string              $meta       The meta value for the field.
	 * @param string              $wp_version The current WP Version from the global.
	 *
	 * @return array|mixed
	 */
	public function display_field( $field = [], $options = [], $options_id = null, $meta = null, $wp_version = null ) {
		return pngx( Fields::class )->display_field( $field, $options, $options_id, $meta, $wp_version );
	}

	/**
	 * Get fields.
	 *
	 * @since 0.1.0
	 */
	public function get_fields( array $fields = [] ) {
		return pngx( Meta_Fields::class )->get_fields( $fields );
	}

	/**
	 * Hooks the filters required for the Integration.
	 *
	 * @since 0.1.0
	 */
	protected function add_filters() {
		add_filter( 'cctor_option_sections', [ $this, 'filter_sections' ], 10 );
		add_filter( 'cctor_option_filter', [ $this, 'filter_integrations_fields' ], 10 );
	}

	/**
	 * Filter Options Tabs Headings.
	 *
	 * @since 0.1.0
	 *
	 * @param array<string> $sections The current sections.
	 */
	public function filter_sections( $sections ) {
		return pngx( Options::class )->filter_sections( $sections );
	}

	/**
	 * Filters the fields for the Integration.
	 *
	 * @since 0.1.0
	 *
	 * @param array<string|mixed> An array of option fields to display.
	 * @param Options The instance of the main options handler.
	 *
	 * @return array<string,array> The fields, as updated by the settings.
	 */
	public function filter_integrations_fields( $fields ) {
		return pngx( Options::class )->add_fields( $fields );
	}

	/**
	 * Provides the routes that should be used to handle Integration requests.
	 *
	 * The map returned by this method will be used by the `Cctor\Coupon\Traits\With_Nonce_Routes` trait.
	 *
	 * @since 0.1.0
	 *
	 * @return array<string,callable> A map from the nonce actions to the corresponding handlers.
	 */
	public function admin_routes() {
		$actions = pngx( Actions::class );

		return [
			$actions::$add_profile_action       => $this->container->callback( Api::class, 'ajax_add_profile' ),
			$actions::$save_action              => $this->container->callback( Api::class, 'ajax_save' ),
			$actions::$update_action            => $this->container->callback( Api::class, 'ajax_update' ),
			$actions::$delete_action            => $this->container->callback( Api::class, 'ajax_delete' ),
			$actions::$create_test_action => $this->container->callback( Api::class, 'ajax_create_test' ),
		];
	}
}

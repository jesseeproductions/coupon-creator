<?php


class Cctor__Coupon__Provider extends tad_DI52_ServiceProvider {

	/**
	 * Binds and sets up implementations.
	 *
	 * @since  TBD
	 *
	 */
	public function register() {

		$this->container->singleton( 'cctor', Cctor__Coupon__Main::instance() );

		// Setup to check if gutenberg is active
		$this->container->singleton( 'cctor.editor', 'Pngx__Editor' );

		if ( is_admin() ) {
			$this->admin_hook();
		}

		$this->container->singleton( 'cctor.post_menu', new Cctor__Coupon__Post_Type_Coupon( pngx( 'cctor' )::POSTTYPE, pngx( 'cctor' )::TAXONOMY, pngx( 'cctor' )::TEXT_DOMAIN ) );

		$this->container->singleton( 'cctor.meta', 'Cctor__Coupon__Meta__Fields' );

		$this->container->singleton( 'cctor.i18n', 'Cctor__Coupon__I18n', array( 'hook' ) );
		$this->container->singleton( 'cctor.assets', 'Cctor__Coupon__Assets' );

		$this->container->singleton( 'cctor.search', 'Cctor__Coupon__Search' );
		$this->container->singleton( 'cctor.shortcode', 'Cctor__Coupon__Shortcode' );
		$this->container->singleton( 'cctor.images', 'Cctor__Coupon__Images' );
		$this->container->singleton( 'cctor.print', 'Cctor__Coupon__Print' );

		//$this->container->singleton( 'cctor.template', 'Cctor__Coupon__Templates', array( 'init' ) );
		//$this->container->singleton( 'gutenberg.template.overwrite', 'Tribe__Events_Gutenberg__Template__Overwrite', array( 'hook' ) );

		// Blocks
		$this->container->singleton( 'cctor.blocks.coupon', 'Cctor__Coupon__Blocks__Menu_Items' );

		$this->hook();

		/**
		 * Call all the Singletons that need to be setup/hooked
		 */
		pngx( 'cctor.i18n' );
		pngx( 'cctor.assets' );
		//pngx( 'cctor.template' );

	}

	/**
	 * Any hooking any class needs happen here.
	 *
	 * In place of delegating the hooking responsibility to the single classes they are all hooked here.
	 *
	 * @since TBD
	 *
	 */
	protected function hook() {

		//new
		add_action( 'init', pngx_callback( 'cctor', 'init' ) );

		add_action( 'init', pngx_callback( 'cctor.post_menu', 'register' ), 5 );

		add_action( 'init', array( 'Pngx__Cron_20', 'filter_cron_schedules' ) );
		add_action( 'pre_get_posts', pngx_callback( 'cctor.search', 'remove_coupon_from_search' ) );

		// Add Editor and Rest API Support for Coupon Creator
		if ( pngx( 'cctor.editor' )->is_gutenberg_active() || pngx( 'cctor.editor' )->is_blocks_editor_active() ) {
			//add_filter( 'pngx_register_wpe_menu_type_args', pngx_callback( 'cctor.editor', 'add_support' ) );
			add_filter( 'pngx_register_cctor_coupon_type_args', pngx_callback( 'cctor.editor', 'add_rest_support' ) );

			add_filter( 'pngx_register_cctro_coupon_type_args', pngx_callback( 'cctor.editor', 'add_template_blocks' ) );

			// Setup the Meta registration
			add_action( 'init', pngx_callback( 'cctor.meta', 'register' ), 25 );

			// Setup the registration of Blocks
			add_action( 'init', pngx_callback( 'cctor.editor', 'register_blocks' ), 20 );

			// Register blocks to own own action
			add_action( 'pngx_editor_register_blocks', pngx_callback( 'cctor.blocks.coupon', 'register' ) );
		}

		//Front End
		add_shortcode( 'coupon', pngx_callback( 'cctor.shortcode', 'core_shortcode' ) );
		add_action( 'cctor_before_coupon', 'cctor_shortcode_functions', 10 );
		add_action( 'init', pngx_callback( 'cctor.images', 'add_image_sizes' ) );
		add_filter( 'cctor_filter_terms_tags', array( 'Pngx__Allowed_Tags', 'content_no_link' ), 10, 1 );
		if ( cctor_options( 'cctor_wpautop' ) == 1 ) {
			add_filter( 'the_content', pngx_callback( 'cctor', 'remove_autop_for_coupons' ), 0 );
		}

		//Print Template
		add_action( 'cctor_action_print_template', 'cctor_print_template', 10 );
		add_filter( 'template_include', pngx_callback( 'cctor.print', 'get_coupon_post_type_template' ) );
		add_action( 'coupon_print_head', pngx_callback( 'cctor.print', 'print_css' ), 20 );

		// Query
		add_action( 'parse_query', pngx_callback( 'cctor', 'parse_query' ), 50 );

		// Filter content and determine if we are going to use wpautop
		add_filter( 'pngx_filter_content', pngx_callback( 'cctor', 'filter_coupon_content' ) );



	}

	/**
	 * Any hooking any class needs happen here.
	 *
	 * In place of delegating the hooking responsibility to the single classes they are all hooked here.
	 *
	 * @since TBD
	 *
	 */
	protected function admin_hook() {

		$this->container->singleton( 'cctor.admin', 'Cctor__Coupon__Admin__Main' );
		$this->container->singleton( 'cctor.admin.updates', 'Cctor__Coupon__Admin__Updates' );
		$this->container->singleton( 'cctor.admin.assets', 'Cctor__Coupon__Admin__Assets' );
		$this->container->singleton( 'cctor.admin.options', 'Cctor__Coupon__Admin__Options' );
		$this->container->singleton( 'cctor.admin.meta', 'Cctor__Coupon__Admin__Meta' );
		$this->container->singleton( 'cctor.admin.meta.fields', 'Cctor__Coupon__Fields' );
		$this->container->singleton( 'cctor.admin.columns', 'Cctor__Coupon__Admin__Columns' );

		//Update Version Number
		add_action( 'admin_init', pngx_callback( 'cctor.admin.updates', 'admin_upgrade_version' ) );

		//Load Admin Assets
		add_action( 'admin_enqueue_scripts', pngx_callback( 'cctor.admin.assets', 'load_assets' ) );

		//Options
		add_action( 'plugin_action_links', pngx_callback( 'cctor.admin', 'plugin_setting_link' ), 10, 2 );
		add_action( 'admin_menu', pngx_callback( 'cctor.admin.options', 'options_page' ) );
		add_action( 'admin_init', pngx_callback( 'cctor.admin.options', 'admin_init' ), 0 );

		//Meta
		pngx( 'cctor.admin.columns' );
		add_action( 'admin_init', pngx_callback( 'cctor.admin.meta', 'setup' ) );
		add_action( 'pngx_front_field_types', pngx_callback( 'cctor.admin.meta.fields', 'display_field' ), 10, 5 );

	}

}

<?php
/**
 * Coupon Creator Class
 *
 * This is the initial class with mostly generic methods to start a plugin
 */

use Cctor\Coupon\Hooks;

class Cctor__Coupon__Main {

	const TAXONOMY                 = 'cctor_coupon_category';
	const POSTTYPE                 = 'cctor_coupon';
	const PLUGIN_NAME              = 'Coupon Creator';
	const CAPABILITIESPLURAL       = 'cctor_coupons';
	const TEXT_DOMAIN              = 'coupon-creator';

	public $TAXONOMY                 = 'cctor_coupon_category';
	public $POSTTYPE                 = 'cctor_coupon';
	public $PLUGIN_NAME              = 'Coupon Creator';
	public $CAPABILITIESPLURAL       = 'cctor_coupons';
	public $TEXT_DOMAIN              = 'coupon-creator';

	/**
	 * Stores the base slug for the plugin.
	 *
	 * @since 3.4.0
	 *
	 * @var string
	 */
	const SLUG = 'coupon-creator';

	/**
	* Min Version of WordPress
	*
	* @since 4.10
	*/
	protected $min_wordpress = '5.8';
	/**
	* Min Version of PHP
	*
	* @since 4.10
	*/
	protected $min_php = '7.4';

	const VERSION_KEY              = 'cctor_coupon_version';
	const VERSION_NUM              = '3.4.1';
	const MIN_PNGX_VERSION         = '4.0.0';
	const WP_PLUGIN_URL            = 'https://wordpress.org/plugins/coupon-creator/';
	const COUPON_CREATOR_STORE_URL = 'https://couponcreatorplugin.com/edd-api/';
	const OPTIONS_ID               = 'coupon_creator_options';

	public $VERSION_KEY              = 'cctor_coupon_version';
	public $VERSION_NUM              = '3.4.1';
	public $MIN_PNGX_VERSION         = '4.0.0';
	public $WP_PLUGIN_URL            = 'https://wordpress.org/plugins/coupon-creator/';
	public $COUPON_CREATOR_STORE_URL = 'https://couponcreatorplugin.com/edd-api/';
	public $OPTIONS_ID               = 'coupon_creator_options';

	public $cctorUrl = 'https://couponcreatorplugin.com/';

	/**
	 * @var bool Prevent autoload initialization
	 */
	private $should_prevent_autoload_init = false;
 	/**
	 * @var string plugin-engine VERSION regex
	 */
	private $pngx_version_regex = "/const\s+VERSION\s*=\s*'([^']+)'/m";

	/**
	 * Static Singleton Holder
	 *
	 * @var self
	 */
	protected static $instance;

	public $plugins_path;
	public $plugin_path;
	public $plugin_dir;
	public $plugin_url;
	public $resource_path;
	public $resource_url;
	public $vendor_path;
	public $vendor_url;
	public $plugin_name;

	/**
	 * Get (and instantiate, if necessary) the instance of the class
	 *
	 * @return self
	 */
	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Initializes plugin variables and sets up WordPress hooks/actions.
	 */
	protected function __construct() {
		$this->plugins_path  = trailingslashit( dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) );
		$this->plugin_path   = trailingslashit( dirname( dirname( dirname( __FILE__ ) ) ) );
		$this->plugin_dir    = trailingslashit( basename( $this->plugin_path ) );
		$this->plugin_url    = plugins_url( $this->plugin_dir );
		$this->resource_path = $this->plugin_path . 'src/resources/';
		$this->resource_url  = $this->plugin_url . 'src/resources/';
		$this->vendor_path   = $this->plugin_path . 'vendor/';
		$this->vendor_url    = $this->plugin_url . 'vendor/';

		// Set plugin engine lib information, needs to happen file load
		$this->maybe_set_pngx_lib_info();

		add_action( 'plugins_loaded', array( $this, 'maybe_bail_if_invalid_wp_or_php' ), -1 );
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 0 );
	}

	/*
	* Activate
	*/
	public static function activate() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		// Setup Auto Loader to Prevent Fatal on Activate
		self::instance()->init_autoloading();

		// Safety check: if Plugin Engine is not at a certain minimum version, bail out
		if ( version_compare( Pngx__Main::VERSION, self::MIN_PNGX_VERSION, '<' ) ) {
				return;
		}

		// Setup Capabilities for CPT
		if ( ! get_option( self::POSTTYPE . '_capabilities_register' ) ) {
			$capabilities = new Pngx__Add_Capabilities();
			$capabilities->add_capabilities( self::POSTTYPE );
		}

		// Use Instance to call method to setup cpt
		new Cctor__Coupon__Post_Type_Coupon( self::POSTTYPE, self::TAXONOMY, self::TEXT_DOMAIN );

		/**
		 * Fires on Activation of Coupon Creator
		 *
		 * Avaiable when Pro is decativated users who have
		 * activate_plugins capability
		 *
		 */
		do_action( 'cctor_activate' );

		// set option to flush permalinks on next load
		if ( ! is_network_admin()  ) {
			update_option( 'pngx_permalink_change', true );
		}
	}

	/*
	* Deactivate
	*/
	public static function deactivate() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		/**
		 * Fires on deactivation of Coupon Creator
		 *
		 * Avaiable when Pro is decativated users who have
		 * activate_plugins capability
		 *
		 */
		do_action( 'cctor_deactivate' );

		flush_rewrite_rules();
	}

	/**
	 * Load Plugin.
	 *
	 * @since 3.0.0
	 */
	public function plugins_loaded() {
		if ( $this->should_prevent_autoload_init ) {

			/**
			 * Fires if Coupon Creat cannot load due to compatibility or other problems.
			 */
			do_action( 'coupon_creator_plugin_failed_to_load' );

			return;

		}

		// include the autoloader class for Cctor classes
		$this->init_autoloading();

		// Start Up Plugin Engine
		Pngx__Main::instance();

		add_action( 'pngx_engine_loaded', array( $this, 'bootstrap' ), 0 );

		//Disable Older Versions of Pro to prevent fatal errors
		if ( function_exists( 'Coupon_Pro_Load' ) ) {
			remove_action( 'plugins_loaded', 'Coupon_Pro_Load', 1 );
			add_action( 'admin_notices', array( $this, 'pre_dependency_msg_pro' ), 50 );

			if ( ! function_exists( 'pngx_register_coupon_creator_pro' ) ) {
				// @formatter:off
				//setup license and update system for Pro on older versions
				new Cctor__Coupon__Admin__License_Setup(
					'Coupon Creator Pro',
					'cctor_pro_license',
					'cctor_coupon_pro_version',
					'2.5.6',
					Cctor__Coupon__Main::instance()->plugins_path . 'coupon-creator-pro/coupon-creator-pro.php',
					'coupon-creator-pro/coupon-creator-pro.php',
					'cctor_pro_license_status'
				);
			// @formatter:on
			}
		}


		//Disable Older Versions of Addons to prevent fatal errors
		if ( function_exists( 'Coupon_Add_ons_Load' ) ) {
			remove_action( 'plugins_loaded', 'Coupon_Add_ons_Load', 2 );
			add_action( 'admin_notices', array( $this, 'pre_dependency_msg_addons' ), 50 );

			if ( ! function_exists( 'pngx_register_coupon_creator_addons' ) ) {
				// @formatter:off
				//setup license and update system for Add-ons
				new Cctor__Coupon__Admin__License_Setup(
					'Coupon Creator Add-ons',
					'cctor_addons_license',
					'cctor_coupon_addons_version',
					'2.5.5',
					Cctor__Coupon__Main::instance()->plugins_path . 'coupon-creator-add-ons/coupon-creator-add-ons.php',
					'coupon-creator-add-ons/coupon-creator-add-ons.php',
					'cctor_addon_license_status'
				);
				// @formatter:on
			}
		}
	}

	/**
	 * Bootstrap Plugin
	 *
	 * @since 3.0
	 */
	public function bootstrap() {
		/**
		 * We need Plugin Engine to be able to load text domains correctly.
		 * With that in mind we initialize Plugin Engine passing the plugin Main class as the context
		 */
		add_action('init', function()
		{
			Pngx__Main::instance()->load_text_domain( self::TEXT_DOMAIN , $this->plugin_dir . 'lang/' );
		});

		pngx_register_provider( 'Cctor__Coupon__Provider' );
		pngx_register_provider( Hooks::class );

		$this->loadLibraries();

		$this->register_active_plugin();

		/**
		 * Fires once Coupon Creator has completed basic setup.
		 */
		do_action( 'coupon_creator_plugin_loaded' );
	}

	/**
	 * Registers this plugin as being active for other pngx plugins and extensions
	 */
	protected function register_active_plugin() {
		$this->registered = new Cctor__Coupon__Plugin_Register();
	}

	/**
	 * Auto Loader from Plugin Engine
	 */
	protected function init_autoloading() {
		$autoloader = $this->get_autoloader_instance();
		$this->register_plugin_autoload_paths();

		// deprecated classes are registered in a class to path fashion
		foreach ( glob( $this->plugin_path . 'src/deprecated/*.php' ) as $file ) {
			$class_name = str_replace( '.php', '', basename( $file ) );
			$autoloader->register_class( $class_name, $file );
		}
		$autoloader->register_autoloader();
	}

	/**
	 * Returns the autoloader singleton instance to use in a context-aware manner.
	 *
	 * @since 3.0
	 *
	 * @return \Pngx__Autoloader The singleton plugin engine Autoloader instance.
	 *
	 */
	public function get_autoloader_instance() {
		if ( ! class_exists( 'Pngx__Autoloader' ) ) {
			require_once $GLOBALS['plugin-engine-info']['dir'] . '/Autoloader.php';
			Pngx__Autoloader::instance()->register_prefixes( [
				'Pngx__' => $GLOBALS['plugin-engine-info']['dir'],
			] );
		}

		return Pngx__Autoloader::instance();
	}

	/**
	 * Registers the plugin autoload paths in the Plugin Engine Autoloader instance.
	 *
	 * @since 3.0
	 */
	public function register_plugin_autoload_paths() {
		$prefixes = array(
			'Cctor__Coupon__' => $this->plugin_path . 'src/Cctor',
		);
		$this->get_autoloader_instance()->register_prefixes( $prefixes );
	}

	/**
	 * Maybe set plugin engine info
	 */
	public function maybe_set_pngx_lib_info() {
		$pngx_version = file_get_contents( $this->plugin_path . 'plugin-engine/src/Pngx/Main.php' );

		// if there isn't a plugin-engine version, bail
		if ( ! preg_match( $this->pngx_version_regex, $pngx_version, $matches ) ) {
			add_action( 'admin_head', array( $this, 'missing_common_libs' ) );

			return;
		}
		$pngx_version = $matches[1];
		if ( empty( $GLOBALS['plugin-engine-info'] ) ) {
			$GLOBALS['plugin-engine-info'] = array(
				'dir'     => "{$this->plugin_path}plugin-engine/src/Pngx",
				'version' => $pngx_version,
			);
		} elseif ( 1 == version_compare( $GLOBALS['plugin-engine-info']['version'], $pngx_version, '<' ) ) {
			$GLOBALS['plugin-engine-info'] = array(
				'dir'     => "{$this->plugin_path}plugin-engine/src/Pngx",
				'version' => $pngx_version,
			);
		}

	}

	/**
	 * Prevents bootstrapping and autoloading if the version of WP or PHP are too old
	 *
	 * @since 3.0.0
	 */
	public function maybe_bail_if_invalid_wp_or_php() {
		if ( self::supported_version( 'wordpress' ) && self::supported_version( 'php' ) ) {
			return;
		}
		add_action( 'admin_notices', array( $this, 'not_supported_error' ) );
		add_action( 'network_admin_notices', array( $this, 'not_supported_error' ) );

		$this->should_prevent_autoload_init = true;
	}

	/**
	 * Display a missing plugin-engine library error
	 */
	public function missing_common_libs() {
		?>
		<div class="error">
			<p>
				<?php
				echo esc_html__( 'It appears as if the Plugin Engine libraries cannot be found! The directory should be in the "plugin-engine/" directory in the Coupon Creator plugin.', 'coupon-creator' );
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Test whether the current version of PHP or WordPress is supported.
	 *
	 * @since 3.0
	 *
	 * @param string $system Which system to test the version of such as 'php' or 'wordpress'.
	 *
	 * @return boolean Whether the current version of PHP or WordPress is supported.
	 */
	public function supported_version( $system ) {
		if ( $supported = wp_cache_get( $system, 'pngx_version_test' ) ) {
			return $supported;
		}
		switch ( strtolower( $system ) ) {
			case 'wordpress' :
				$supported = version_compare( get_bloginfo( 'version' ), $this->min_wordpress, '>=' );
				break;
			case 'php' :
				$supported = version_compare( phpversion(), $this->min_php, '>=' );
				break;
		}
		/**
		 * Filter whether the current version of PHP or WordPress is supported.
		 *
		 * @since 3.0
		 *
		 *@param string  $system    Which system to test the version of such as 'php' or 'wordpress'.
		 *
		 * @param boolean $supported Whether the current version of PHP or WordPress is supported.
		 */
		$supported = apply_filters( 'coupon_creator_supported_system_version', $supported, $system );

		wp_cache_set( $system, $supported, 'pngx_version_test' );

		return $supported;
	}

	/**
	 * Display a WordPress or PHP incompatibility error.
	 *
	 * @since 3.0
	 */
	public function not_supported_error() {
		if ( ! self::supported_version( 'wordpress' ) ) {
			echo '<div class="error"><p>' . esc_html( sprintf( __( 'Sorry, Coupon Creator requires WordPress %s or higher. Please upgrade your WordPress install.', 'coupon-creator' ), $this->min_wordpress ) ) . '</p></div>';
		}
		if ( ! self::supported_version( 'php' ) ) {
			echo '<div class="error"><p>' . esc_html( sprintf( __( 'Sorry, Coupon Creator requires PHP %s or higher. Talk to your Web host about moving you to a newer version of PHP.', 'coupon-creator' ), $this->min_php ) ) . '</p></div>';
		}
	}

	/**
	 * Load all the required library files.
	 */
	protected function loadLibraries() {
		// initialize the common libraries
		$this->common();

		//Core Functions
		require_once $this->plugin_path . 'src/functions/template-tags/general.php';

		//Deprecated Functions
		require_once $this->plugin_path . 'src/deprecated/deprecated.php';

		//Load Template Functions
		require_once $this->plugin_path . 'src/functions/template-functions/cctor-function-meta.php';
		require_once $this->plugin_path . 'src/functions/template-functions/cctor-function-expiration.php';
		require_once $this->plugin_path . 'src/functions/template-functions/cctor-function-wraps.php';
		require_once $this->plugin_path . 'src/functions/template-functions/cctor-function-image.php';
		require_once $this->plugin_path . 'src/functions/template-functions/cctor-function-deal.php';
		require_once $this->plugin_path . 'src/functions/template-functions/cctor-function-terms.php';
		require_once $this->plugin_path . 'src/functions/template-functions/cctor-function-links.php';

		//Shortcode and Print Build
		require_once $this->plugin_path . 'src/functions/template-build/cctor-shortcode-build.php';
		require_once $this->plugin_path . 'src/functions/template-build/cctor-print-build.php';
	}

	/**
	 * Common library object accessor method
	 */
	public function common() {
		static $common;
		if ( ! $common ) {
			$common = new Pngx__Main( $this );
		}

		return $common;
	}

	/*
	* Remove wpautop in Terms Field
	* based of coding from http://www.wpcustoms.net/snippets/remove-wpautop-custom-post-types/
	*/
	public function remove_autop_for_coupons( $content ) {
		'cctor_coupon' === get_post_type() && remove_filter( 'the_content', 'wpautop' );

		return $content;
	}

	/**
	 * Check whether a post is an coupon.
	 *
	 * @param int|WP_Post The coupon/post id or object.
	 *
	 * @return bool Is it an coupon?
	 */
	public function is_coupon( $coupon ) {
		if ( $coupon === null || ( ! is_numeric( $coupon ) && ! is_object( $coupon ) ) ) {
			global $post;
			if ( is_object( $post ) && isset( $post->ID ) ) {
				$coupon = $post->ID;
			}
		}
		if ( is_numeric( $coupon ) ) {
			if ( get_post_type( $coupon ) == 'cctor_coupon' ) {
				return true;
			}
		} elseif ( is_object( $coupon ) ) {
			if ( get_post_type( $coupon ) == 'cctor_coupon' ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Set any query flags
	 *
	 * @param WP_Query $query
	 **/
	public function parse_query( $query ) {
		// @formatter:off
		$types = ( ! empty( $query->query_vars['post_type'] ) ? (array) $query->query_vars['post_type'] : array() );

		// check if a coupon query by post_type
		$query->cctor_is_coupon = ( in_array( 'cctor_coupon', $types ) && count( $types ) < 2 )
			? true // it is a coupon
			: false;

		$query->cctor_is_coupon_category = ! empty ( $query->query_vars[ 'cctor_coupon_category' ] )
			? true // it was an coupon category
			: false;

		$query->cctor_is_coupon_query = ( $query->cctor_is_coupon || $query->cctor_is_coupon_category )
			? true // a coupon query of some type
			: false;
		// @formatter:on

		/**
		 * Parse Coupon Query Action
		 *
		 * @parm  object $query
		 */
		do_action( 'cctor_coupon_parse_query', $query );
	}


	/**
	 * Return Shop URL for Plugin
	 *
	 * @return string
	 */
	public function get_shop_url() {
		return defined( 'COUPON_CREATOR_STORE_URL' ) ? COUPON_CREATOR_STORE_URL : self::COUPON_CREATOR_STORE_URL;
	}

	/**
	 * Filter Coupon Content and use wpautop if enabled in options
	 *
	 * @param $meta
	 *
	 * @return string
	 */
	public function filter_coupon_content( $meta ) {
		//WPAutop
		if ( 1 != cctor_options( 'cctor_wpautop', true, 1 ) ) {
			$meta = wpautop( $meta );
		}

		return $meta;
	}

	/**
	 * Add an Admin Message for Pro when disabled by new versions of Coupon Creator
	 *
	 * @since 3.0
	 *
	 */
	public function pre_dependency_msg_pro() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$title = esc_html__( 'Coupon Creator Pro', 'coupon-creator' );
		$link_text = esc_html__( 'the latest version', 'coupon-creator' );

		echo '<div class="error"><p>';

		printf(
			esc_html__( 'Your version of Coupon Creator Pro is incompatible with Coupon Creator 3.0 and later. To continue using Coupon Creator Pro, please install and activate %1$s%2$s%3$s or downgrade to Coupon Creator 2.5.6 or earlier.', 'coupon-creator' ),
			'<a href="http://cctor.link/sMsY2" title="' . $title . '" target="_blank">',
			$link_text,
			'</a>'
		);

		echo '</p></div>';

	}

	/**
	 * Add an Admin Message for Addons when disabled by new versions of Coupon Creator
	 *
	 * @since 3.0
	 *
	 */
	public function pre_dependency_msg_addons() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$url = add_query_arg( array(
			'tab'       => 'plugin-information',
			'plugin'    => 'coupon-creator',
			'TB_iframe' => 'true',
		), admin_url( 'plugin-install.php' ) );

		$title = esc_html__( 'Coupon Creator', 'coupon-creator' );
		$link_text = esc_html__( 'the latest version', 'coupon-creator' );

		$pro_title = esc_html__( 'Coupon Creator Pro', 'coupon-creator' );

		echo '<div class="error"><p>';

		printf(
			esc_html__( 'Your version of Coupon Creator Add-ons is incompatible with Coupon Creator 3.0 and later. To continue using Coupon Creator Add-ons, please install and activate %1$s%2$s%3$s or downgrade to Coupon Creator 2.5.6 or earlier.', 'coupon-creator-add-ons' ),
			'<a href="http://cctor.link/sMsY2" title="' . $title . '" target="_blank">',
			$link_text,
			'</a>'
		);

		echo '</p></div>';
	}
}
<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


/**
 * Coupon Creator Class
 *
 * This is the initial class with mostly generic methods to start a plugin
 *
 */
class Cctor__Coupon__Main {

	const TAXONOMY                 = 'cctor_coupon_category';
	const POSTTYPE                 = 'cctor_coupon';
	const PLUGIN_NAME              = 'Coupon Creator';
	const CAPABILITIESPLURAL       = 'cctor_coupons';
	const TEXT_DOMAIN              = 'coupon-creator';
	const MIN_PHP_VERSION          = '5.2';
	const MIN_WP_VERSION           = '4.0';
	const VERSION_KEY              = 'cctor_coupon_version';
	const VERSION_NUM              = '2.6';
	const MIN_PNGX_VERSION         = '2.6';
	const WP_PLUGIN_URL            = 'https://wordpress.org/plugins/coupon-creator/';
	const COUPON_CREATOR_STORE_URL = 'https://couponcreatorplugin.com/edd-sl-api/';
	const OPTIONS_ID               = 'coupon_creator_options';

	public static $cctorUrl = 'https://couponcreatorplugin.com/';

	protected static $instance;
	public           $plugin_dir;
	public           $plugin_path;
	public           $plugin_url;
	public           $resource_path;
	public           $resource_url;
	public           $vendor_path;
	public           $vendor_url;
	public           $plugin_name;

	/**
	 * Static Singleton Factory Method
	 *
	 * @return Cctor__Coupon__Main
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			$className      = __CLASS__;
			self::$instance = new $className;
		}

		return self::$instance;
	}

	/**
	 * Initializes plugin variables and sets up WordPress hooks/actions.
	 */
	protected function __construct() {
		$this->plugin_path   = trailingslashit( dirname( dirname( dirname( __FILE__ ) ) ) );
		$this->plugin_dir    = trailingslashit( basename( $this->plugin_path ) );
		$this->plugin_url    = plugins_url( $this->plugin_dir );
		$this->resource_path = $this->plugin_path . 'src/resources/';
		$this->resource_url  = $this->plugin_url . 'src/resources/';
		$this->vendor_path   = $this->plugin_path . 'vendor/';
		$this->vendor_url    = $this->plugin_url . 'vendor/';

		$this->maybe_set_common_lib_info();

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
		Cctor__Coupon__Main::instance()->init_autoloading();

		// Safety check: if Plugin Engine is not at a certain minimum version, bail out
		if ( version_compare( Pngx__Main::VERSION, self::MIN_PNGX_VERSION, '<' ) ) {
				return;
		}

		// Setup Capabilities for CPT
		if ( ! get_option( self::POSTTYPE . '_capabilities_register' ) ) {
			new Pngx__Add_Capabilities( self::POSTTYPE );
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

		flush_rewrite_rules();

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

	public function plugins_loaded() {

		// include the autoloader class
		$this->init_autoloading();

		// Safety check: if Plugin Engine is not at a certain minimum version, bail out
		if ( version_compare( Pngx__Main::VERSION, self::MIN_PNGX_VERSION, '<' ) ) {
			return;
		}

		if ( ! self::supportedVersion( 'wordpress' ) ||  ! self::supportedVersion( 'php' ) ) {

			add_action( 'admin_head', array( $this, 'notSupportedError' ) );

			return;

		}

		// Start Up Common
		Pngx__Main::instance();
		add_action( 'pngx_common_loaded', array( $this, 'bootstrap' ), 0 );

	}

	/**
	 * Load Text Domain on tribe_common_loaded as it requires common
	 *
	 * @since 4.10
	 *
	 */
	public function bootstrap() {

		/**
		 * We need Common to be able to load text domains correctly.
		 * With that in mind we initialize Common passing the plugin Main class as the context
		 */
		Pngx__Main::instance()->load_text_domain( self::TEXT_DOMAIN , $this->plugin_dir . 'lang/' );

		pngx_register_provider( 'Cctor__Coupon__Provider' );
		$this->loadLibraries();

		//$this->hooks();

		$this->register_active_plugin();

		/*$this->bind_implementations();
		$this->user_event_confirmation_list_shortcode();
		$this->activation_page();*/


		/**
		 * Fires once Coupon Creator has completed basic setup.
		 */
		do_action( 'coupon_creator_plugin_loaded' );

	}

	/**
	 * Registers this plugin as being active for other tribe plugins and extensions
	 */
	protected function register_active_plugin() {
		$this->registered = new Cctor__Coupon__Plugin_Register();
	}

	/**
	 * Test PHP and WordPress versions for compatibility
	 *
	 * @param string $system - system to be tested such as 'php' or 'wordpress'
	 *
	 * @return boolean - is the existing version of the system supported?
	 */
	public function supportedVersion( $system ) {
		if ( $supported = wp_cache_get( $system, 'tribe_version_test' ) ) {
			return $supported;
		} else {
			switch ( strtolower( $system ) ) {
				case 'wordpress' :
					$supported = version_compare( get_bloginfo( 'version' ), self::MIN_WP_VERSION, '>=' );
					break;
				case 'php' :
					$supported = version_compare( phpversion(), self::MIN_PHP_VERSION, '>=' );
					break;
			}
			$supported = apply_filters( 'cctor_supported_version', $supported, $system );
			wp_cache_set( $system, $supported, 'cctor_version_test' );

			return $supported;
		}
	}

	/**
	 * Display a WordPress or PHP incompatibility error
	 */
	public function notSupportedError() {
		if ( ! self::supportedVersion( 'wordpress' ) ) {
			echo '<div class="error"><p>' . sprintf( esc_html__( 'Coupon Creator Requires WordPress version: %s or higher. You currently have WordPress version: %s', 'coupon-creator' ), self::MIN_WP_VERSION, get_bloginfo( 'version' ) ) . '</p></div>';
		}
		if ( ! self::supportedVersion( 'php' ) ) {
			echo '<div class="error"><p>' . sprintf( esc_html__( 'Coupon Creator Requires PHP version: %s or higher. You currently have PHP version: %s', 'coupon-creator' ), self::MIN_PHP_VERSION, phpversion() ) . '</p></div>';
		}
	}

	/**
	 * Auto Loader from Plugin Engine
	 */
	protected function init_autoloading() {
		$prefixes = array(
			'Cctor__Coupon__' => $this->plugin_path . 'src/Cctor',
		);

		if ( ! class_exists( 'Pngx__Autoloader' ) ) {
			require_once $GLOBALS['plugin-engine-info']['dir'] . '/Autoloader.php';
			$prefixes['Pngx__'] = $GLOBALS['plugin-engine-info']['dir'];
		}
		$autoloader = Pngx__Autoloader::instance();

		$autoloader->register_prefixes( $prefixes );
		// deprecated classes are registered in a class to path fashion
		foreach ( array_merge( glob( $this->plugin_path . 'plugin-engine/src/deprecated/*.php' ), glob( $this->plugin_path . 'src/deprecated/*.php' ) ) as $file ) {
			$class_name = str_replace( '.php', '', basename( $file ) );
			$autoloader->register_class( $class_name, $file );
		}

		$autoloader->register_autoloader();

	}

	/**
	 * Maybe set plugin engine info
	 */
	public function maybe_set_common_lib_info() {
		$common_version = file_get_contents( $this->plugin_path . 'plugin-engine/src/Pngx/Main.php' );

		// if there isn't a plugin-engine version, bail
		if ( ! preg_match( "/const\s+VERSION\s*=\s*'([^']+)'/m", $common_version, $matches ) ) {
			add_action( 'admin_head', array( $this, 'missing_common_libs' ) );

			return;
		}
		$common_version = $matches[1];
		if ( empty( $GLOBALS['plugin-engine-info'] ) ) {
			$GLOBALS['plugin-engine-info'] = array(
				'dir'     => "{$this->plugin_path}plugin-engine/src/Pngx",
				'version' => $common_version,
			);
		} elseif ( 1 == version_compare( $GLOBALS['plugin-engine-info']['version'], $common_version, '<' ) ) {
			$GLOBALS['plugin-engine-info'] = array(
				'dir'     => "{$this->plugin_path}plugin-engine/src/Pngx",
				'version' => $common_version,
			);
		}
	}

	/**
	 * Display a missing plugin-engine library error
	 */
	public function missing_common_libs() {
		?>
		<div class="error">
			<p>
				<?php
				echo esc_html__( 'It appears as if the plugin-engine libraries cannot be found! The directory should be in the "plugin-engine/" directory in the Coupon Creator plugin.', 'coupon-creator' );
				?>
			</p>
		</div>
		<?php
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
		require_once $this->plugin_path . 'src/deprecated/Coupon_Creator.php';
		require_once $this->plugin_path . 'src/deprecated/Coupon_Creator_Plugin_Admin_Options.php';
		require_once $this->plugin_path . 'src/deprecated/Coupon_Creator_Meta_Box.php';
		require_once $this->plugin_path . 'src/deprecated/Coupon_Creator_Plugin_Sanitize.php';
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

	/**
	 * Run on applied action init
	 */
	public function init() {

		require_once $this->plugin_path . 'src/deprecated/Coupon_Pro_Deprecated.php';

	}

	/*
	* Remove wpautop in Terms Field
	* based of coding from http://www.wpcustoms.net/snippets/remove-wpautop-custom-post-types/
	*/
	public static function remove_autop_for_coupons( $content ) {

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
	public static function parse_query( $query ) {

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
	 * Load the text domain.
	 *
	 * @deprecated 2.6
	 *
	 */
	public function i18n() {

		Pngx__Main::instance()->load_text_domain( self::TEXT_DOMAIN, $this->plugin_dir . 'languages/' );

	}


}

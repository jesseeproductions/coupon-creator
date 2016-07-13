<?php
/**
 * Main Coupon Creator class.
 */
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
if ( ! class_exists( 'Cctor__Coupon__Main' ) ) {

	/**
	 * The Events Calendar Class
	 *
	 * This is where all the magic happens, the unicorns run wild and the leprechauns use WordPress to schedule events.
	 */
	class Cctor__Coupon__Main {

		//const CCTOR_PATH   =  plugin_dir_path( __FILE__ );
		//const CCTOR_MIN_PHP_VERSION      = plugin_dir_url( __FILE__ ));
		const TAXONOMY                 = 'cctor_coupon_category';
		const POSTTYPE                 = 'cctor_coupon';
		const COUPON_TEXT_DOMAIN       = 'coupon-creator';
		const CCTOR_MIN_PHP_VERSION    = '5.2';
		const CCTOR_MIN_WP_VERSION     = '4.0';
		const CCTOR_VERSION_KEY        = 'cctor_coupon_version';
		const CCTOR_VERSION_NUM        = '2.3';
		const WP_PLUGIN_URL            = 'https://wordpress.org/plugins/coupon-creator/';
		const COUPON_CREATOR_STORE_URL = 'https://couponcreatorplugin.com/edd-sl-api/';

		public static $cctorUrl = 'https://couponcreatorplugin.com/';

		protected static $instance;
		public           $plugin_dir;
		public           $plugin_path;
		public           $plugin_url;
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
			$this->pluginPath = $this->plugin_path = trailingslashit( dirname( dirname( dirname( __FILE__ ) ) ) );
			$this->pluginDir  = $this->plugin_dir = trailingslashit( basename( $this->plugin_path ) );
			$this->pluginUrl  = $this->plugin_url = plugins_url( $this->plugin_dir );
			$this->maybe_set_common_lib_info();
			// let's initialize tec silly-early to avoid fatals with upgrades from 3.x to 4.x
			add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 0 );
		}


		/*
		* Activate
		*/
		public static function activate() {

			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}

			//Setup Capabilities, but only on initial activation
			if ( ! get_option( 'coupon_creator_capabilties_register' ) ) {
				Coupon_Creator_Plugin::instance()->cctor_add_capabilities();
			}

			self::cctor_register_post_types();
			flush_rewrite_rules();

		}

		/*
		* Deactivate
		*/
		public static function deactivate() {

			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}

			flush_rewrite_rules();

		}

		public function plugins_loaded() {
			// include the autoloader class
			$this->init_autoloading();
			add_action( 'init', array( $this, 'load_text_domain' ), 1 );
			if ( self::supportedVersion( 'wordpress' ) && self::supportedVersion( 'php' ) ) {
				$this->addHooks();
				$this->loadLibraries();
			} else {
				// Either PHP or WordPress version is inadequate so we simply return an error.
				add_action( 'admin_head', array( $this, 'notSupportedError' ) );
			}
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
						$supported = version_compare( get_bloginfo( 'version' ), self::CCTOR_MIN_WP_VERSION, '>=' );
						break;
					case 'php' :
						$supported = version_compare( phpversion(), self::CCTOR_MIN_PHP_VERSION, '>=' );
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
				echo '<div class="error"><p>' . sprintf( esc_html__( 'Coupon Creator Requires WordPress version: %s or higher. You currently have WordPress version: %s', 'coupon-creator' ), self::CCTOR_MIN_WP_VERSION, get_bloginfo( 'version' ) ) . '</p></div>';
			}
			if ( ! self::supportedVersion( 'php' ) ) {
				echo '<div class="error"><p>' . sprintf( esc_html__( 'Coupon Creator Requires PHP version: %s or higher. You currently have PHP version: %s', 'coupon-creator' ), self::CCTOR_MIN_PHP_VERSION, phpversion() ) . '</p></div>';
			}
		}

		/**
		 * Auto Loader from Plugin Engine
		 */
		protected function init_autoloading() {
			$prefixes = array(
				'Cctor__' => $this->plugin_path . 'src/Cctor',
				'Pngx__'  => $this->plugin_path . 'plugin-engine/src/Pngx',
				//'ForceUTF8__'     => $this->plugin_path . 'vendor/ForceUTF8',
			);
			if ( ! class_exists( 'Pngx__Autoloader' ) ) {
				require_once $GLOBALS['plugin-engine-info']['dir'] . '/Autoloader.php';
				$prefixes['Cctor__'] = $GLOBALS['plugin-engine-info']['dir'];
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
		 * Load the text domain.
		 *
		 */
		public function load_text_domain() {
			Pngx__Main::instance()->load_text_domain( self::COUPON_TEXT_DOMAIN, $this->plugin_dir . 'lang/' );
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

			// Tribe common resources
			//require_once $this->plugin_path . 'vendor/tribe-common-libraries/tribe-common-libraries.class.php';
			// Load CSV importer
			//require_once $this->plugin_path . 'src/io/csv/ecp-events-importer.php';
			// Load Template Tags
			//require_once $this->plugin_path . 'src/functions/template-tags/query.php';

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
		 * Add filters and actions
		 */
		protected function addHooks() {
			add_action( 'init', array( $this, 'init' ), 10 );

		}

		/**
		 * Run on applied action init
		 */
		public function init() {
			$this->register_post_type();
		}


		/**
		 * Register the post types.
		 */
		public function register_post_type() {
			//Todo reduce this coding down, maybe use a filter like events for the naming?
			$cap_plural     = 'cctor_coupons';
			$name           = 'Coupon';
			$plural_name    = 'Coupons';
			$lc_name        = 'coupon';
			$lc_plural_name = 'coupons';
			$slug           = cctor_options( 'cctor_coupon_base', false, _x( self::POSTTYPE, 'slug', self::COUPON_TEXT_DOMAIN ) );
			$text_domain    = self::COUPON_TEXT_DOMAIN;
			$updates        = array(
				'supports'            => array( 'title', 'coupon_creator_meta_box' ),
				'menu_icon'           => CCTOR_URL . 'admin/images/coupon_creator.png',
			);
			//Register Post Type and customize labels
			$labels = Pngx__Register_Post_Type::generate_post_type_labels( $name, $plural_name, $lc_name, $lc_plural_name, $text_domain );
			Pngx__Register_Post_Type::register_post_types( self::POSTTYPE, $cap_plural, $name, $labels, $slug, $text_domain, $updates );
			new Pngx__Register_Post_Type(self::POSTTYPE, __( 'Enter Coupon Admin Title', self::COUPON_TEXT_DOMAIN ) );
		}

		/**
		 * Get the event taxonomy
		 *
		 * @return string
		 */
		public function get_event_taxonomy() {
			return self::TAXONOMY;
		}

		/**
		 * Returns the string to be used as the taxonomy slug.
		 *
		 * @return string
		 */
		public function get_category_slug() {
			/**
			 * Provides an opportunity to modify the category slug.
			 *
			 * @var string
			 */
			return apply_filters( 'cctor_category_slug', sanitize_title( __( 'category', 'coupon-creator' ) ) );
		}

		/**
		 * Get the post types that are associated with TEC.
		 *
		 * @return array The post types associated with this plugin
		 */
		public static function getPostTypes() {
			return apply_filters( 'tribe_events_post_types', Cctor__Coupon__Main::get_post_types() );
		}


		/**
		 * Adds post types to the post_types array used to determine if on a post type screen
		 *
		 * @param array $post_types Collection of post types
		 *
		 * @return array
		 */
		public function is_post_type_screen_post_types( $post_types ) {
			foreach ( self::getPostTypes() as $post_type ) {
				$post_types[] = $post_type;
			}

			return $post_types;
		}

	}

}
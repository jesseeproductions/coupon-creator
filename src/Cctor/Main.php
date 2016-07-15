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
	 * Coupon Creator Class
	 *
	 * This is the initial class with mostly generic methods to start a plugin
	 *
	 */
	class Cctor__Coupon__Main {

		const TAXONOMY                 = 'cctor_coupon_category';
		const POSTTYPE                 = 'cctor_coupon';
		const CAPABILITIESPLURAL       = 'cctor_coupons';
		const TEXT_DOMAIN              = 'coupon-creator';
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

		public $singular_coupon_label;
		public $plural_coupon_label;
		public $singular_coupon_label_lowercase;
		public $plural_coupon_label_lowercase;
		public $singular_category_label;
		public $singular_category_label_lowercase;
		public $plural_category_label;
		public $plural_category_label_lowercase;

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

			add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 0 );
		}


		/*
		* Activate
		*/
		public static function activate() {

			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}

			//Setup Capabilities for CPT
			if ( ! get_option( self::POSTTYPE . '_capabilities_register' ) ) {
				new Pngx__Add_Capabilities( self::POSTTYPE, self::CAPABILITIESPLURAL );
			}

			self::register_post_types();

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
			Pngx__Main::instance()->load_text_domain( self::TEXT_DOMAIN, $this->plugin_dir . 'lang/' );
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

			$this->singular_coupon_label           = $this->get_coupon_label_singular();
			$this->singular_coupon_label_lowercase = $this->get_coupon_label_singular_lowercase();
			$this->plural_coupon_label             = $this->get_coupon_label_plural();
			$this->plural_coupon_label_lowercase   = $this->get_coupon_label_plural_lowercase();

			$this->singular_category_label           = $this->get_coupon_category_label_singular();
			$this->singular_category_label_lowercase = $this->get_coupon_category_label_singular_lowercase();
			$this->plural_category_label             = $this->get_coupon_category_label_plural();
			$this->plural_category_label_lowercase   = $this->get_coupon_category_label_plural_lowercase();

			$this->register_post_types();
			$this->register_taxonomies();
		}


		/**
		 * Allow users to specify their own singular label for Coupons
		 *
		 * @return string
		 */
		public function get_coupon_label_singular() {
			return apply_filters( 'cctor_coupon_label_singular', esc_html__( 'Coupon', self::TEXT_DOMAIN ) );
		}

		/**
		 * Get Coupon Label Singular lowercase
		 *
		 * Returns the singular version of the Coupon Label
		 *
		 * @return string
		 */
		function get_coupon_label_singular_lowercase() {
			return apply_filters( 'cctor_coupon_label_singular_lowercase', esc_html__( 'coupon', self::TEXT_DOMAIN ) );
		}

		/**
		 * Allow users to specify their own plural label for Coupons
		 *
		 * @return string
		 */
		public function get_coupon_label_plural() {
			return apply_filters( 'cctor_coupon_label_plural', esc_html__( 'Coupons', self::TEXT_DOMAIN ) );
		}

		/**
		 * Get Coupon Label Plural lowercase
		 *
		 * Returns the plural version of the Coupon Label
		 *
		 * @return string
		 */
		function get_coupon_label_plural_lowercase() {
			return apply_filters( 'cctor_coupon_label_plural_lowercase', esc_html__( 'coupons', self::TEXT_DOMAIN ) );
		}

		/**
		 * Allow users to specify their own singular label for Coupon Category
		 *
		 * @return string
		 */
		public function get_coupon_category_label_singular() {
			return apply_filters( 'cctor_coupon_category_label_singular', esc_html__( 'Coupon Category', self::TEXT_DOMAIN ) );
		}

		/**
		 * Allow users to specify their own lowercase singular label for Coupon Category
		 *
		 * @return string
		 */
		public function get_coupon_category_label_singular_lowercase() {
			return apply_filters( 'cctor_coupon_category_label_singular_lowercase', esc_html__( 'coupon category', self::TEXT_DOMAIN ) );
		}

		/**
		 * Allow users to specify their own plural label for Coupon Categories
		 *
		 * @return string
		 */
		public function get_coupon_category_label_plural() {
			return apply_filters( 'cctor_coupon_category_plural', esc_html__( 'Coupon Categories', self::TEXT_DOMAIN ) );
		}

		/**
		 * Allow users to specify their own plural label for coupon categories
		 *
		 * @return string
		 */
		public function get_coupon_category_label_plural_lowercase() {
			return apply_filters( 'cctor_coupon_category_plural_lowercase', esc_html__( 'coupon categories', self::TEXT_DOMAIN ) );
		}

		/**
		 * Register the post types.
		 */
		public function register_post_types() {

			//Register Coupon Post Type and customize labels
			// @formatter:off
			$labels = Pngx__Register_Post_Type::generate_post_type_labels(
				$this->singular_coupon_label,
				$this->plural_coupon_label,
				$this->singular_coupon_label_lowercase,
				$this->plural_coupon_label_lowercase,
				self::TEXT_DOMAIN
			);

			Pngx__Register_Post_Type::register_post_types(
				self::POSTTYPE,
				self::CAPABILITIESPLURAL,
				$this->singular_coupon_label,
				$labels,
				$this->get_coupon_slug(),
				self::TEXT_DOMAIN,
				array(
					'supports'  => array( 'title', 'coupon_creator_meta_box' ),
					'menu_icon' => $this->pluginUrl . 'admin/images/coupon_creator.png',
				)
			);

			new Pngx__Register_Post_Type(
				self::POSTTYPE,
				__( 'Enter Coupon Admin Title', self::TEXT_DOMAIN )
			);
			// @formatter:on
		}

		/**
		 * Get the coupon post type
		 *
		 * @return string
		 */
		public function get_coupon_post_type() {
			return self::POSTTYPE;
		}

		/**
		 * Returns the string to be used as the taxonomy slug.
		 *
		 * @return string
		 */
		public function get_coupon_slug() {

			/**
			 * Provides an opportunity to modify the coupon slug.
			 *
			 * @var string
			 */
			return apply_filters( 'cctor_coupon_slug', sanitize_title( cctor_options( 'cctor_coupon_base', false, __( self::POSTTYPE, 'slug', self::TEXT_DOMAIN ) ) ) );
		}

		/**
		 * Register the taxonomies.
		 */
		public function register_taxonomies() {

			//Register Coupon Taxonomu
			// @formatter:off
			$labels = Pngx__Register_Taxonomy::generate_taxonomy_labels(
				$this->singular_category_label,
				$this->singular_category_label_lowercase,
				$this->plural_category_label,
				$this->plural_category_label_lowercase,
				self::TEXT_DOMAIN
			);

			Pngx__Register_Taxonomy::register_taxonomy(
				self::TAXONOMY,
				self::POSTTYPE,
				$labels,
				$this->get_category_slug(),
				false
			);
			// @formatter:on
		}

		/**
		 * Get the coupon taxonomy
		 *
		 * @return string
		 */
		public function get_coupon_taxonomy() {
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
			return apply_filters( 'cctor_category_slug', sanitize_title( cctor_options( 'cctor_coupon_category_base', false, __( 'coupon-category', 'slug', self::TEXT_DOMAIN ) ) ) );
		}

		/**
		 * Get the post types that are associated with TEC.
		 *
		 * @return array The post types associated with this plugin
		 */
		public static function getPostTypes() {
			return apply_filters( 'cctor_get_coupon_post_types', Cctor__Coupon__Main::get_post_types() );
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
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
	const VERSION_NUM              = '2.5.5';
	const MIN_PNGX_VERSION         = '2.5.5';
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
		/*		if ( version_compare( Pngx__Main::VERSION, self::MIN_PNGX_VERSION, '<' ) ) {
					return;
				}*/

		// Setup Capabilities for CPT
		if ( ! get_option( self::POSTTYPE . '_capabilities_register' ) ) {
			new Pngx__Add_Capabilities( self::POSTTYPE );
		}

		// Use Instance to call method to setup cpt
		Cctor__Coupon__Main::instance()->register_post_types();

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

		add_action( 'plugins_loaded', array( $this, 'i18n' ), 1 );

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
	 * Load the text domain.
	 *
	 */
	public function i18n() {

		Pngx__Main::instance()->load_text_domain( self::TEXT_DOMAIN, $this->plugin_dir . 'languages/' );

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
	 * Add filters and actions
	 */
	protected function addHooks() {
		add_action( 'init', array( $this, 'init' ), 10 );

		add_action( 'init', array( 'Pngx__Cron_20', 'filter_cron_schedules' ) );
		add_action( 'pre_get_posts', array( 'Cctor__Coupon__Search', 'remove_coupon_from_search' ) );

		//Front End Assets
		add_action( 'wp_enqueue_scripts', array( 'Cctor__Coupon__Assets', 'register_assets' ) );
		add_action( 'wp_enqueue_scripts', array( 'Cctor__Coupon__Assets', 'inline_style' ), 100 );

		//Front End
		add_shortcode( 'coupon', array( 'Cctor__Coupon__Shortcode', 'core_shortcode' ) );
		add_action( 'cctor_before_coupon', 'cctor_shortcode_functions', 10 );
		add_action( 'init', array( 'Cctor__Coupon__Images', 'add_image_sizes' ) );
		add_filter( 'cctor_filter_terms_tags', array( 'Pngx__Allowed_Tags', 'content_no_link' ), 10, 1 );
		if ( cctor_options( 'cctor_wpautop' ) == 1 ) {
			add_filter( 'the_content', array( __CLASS__, 'remove_autop_for_coupons' ), 0 );
		}

		//Print Template
		add_action( 'cctor_action_print_template', 'cctor_print_template', 10 );
		add_filter( 'template_include', array( 'Cctor__Coupon__Print', 'get_coupon_post_type_template' ) );
		add_action( 'coupon_print_head', array( 'Cctor__Coupon__Print', 'print_css' ), 20 );

		// Query
		add_action( 'parse_query', array( __CLASS__, 'parse_query' ), 50 );

		new Cctor__Coupon__Meta__Fields();

		// Load Admin Class if in Admin Section
		if ( is_admin() ) {
			new Cctor__Coupon__Admin__Main();
		}

		// Filter content and determine if we are going to use wpautop
		add_filter( 'pngx_filter_content', array( $this, 'filter_coupon_content' ) );
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

		require_once $this->plugin_path . 'src/deprecated/Coupon_Pro_Deprecated.php';

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

		// Register Coupon Post Type and customize labels
		//  @formatter:off
			$labels = Pngx__Register_Post_Type::generate_post_type_labels(
				$this->singular_coupon_label,
				$this->plural_coupon_label,
				$this->singular_coupon_label_lowercase,
				$this->plural_coupon_label_lowercase,
				self::TEXT_DOMAIN
			);

			Pngx__Register_Post_Type::register_post_types(
				self::POSTTYPE,
				self::POSTTYPE,
				$this->singular_coupon_label,
				$labels,
				$this->get_coupon_slug(),
				self::TEXT_DOMAIN,
				array(
					'supports'  => array( 'title', 'coupon_creator_meta_box' ),
					'menu_icon' => $this->resource_url . 'images/coupon_creator.png',
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

		// Register Coupon Taxonomu
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

}

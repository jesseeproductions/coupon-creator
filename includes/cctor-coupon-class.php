<?php
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

	/*
	* Coupon Creator Class
	* @version 1.70
	*/
	class Coupon_Creator_Plugin {


	private static $instance;

	/**
     * Main Instance
     *
     * Insures that only one instance of Coupon_Creator_Plugin
     *
     * @since 1.90
     * @static
     * @staticvar array $instance
     * @return Coupon_Creator_Plugin
     */
    public static function instance() {

        if ( !isset( self::$instance ) && !( self::$instance instanceof Coupon_Creator_Plugin ) ) {

            self::$instance = new Coupon_Creator_Plugin;
        }

        return self::$instance;

    }

	/***************************************************************************/

		/*
		* Construct
		* @version 1.90
		*/
		public function __construct() {

			//Register Post Type
			add_action( 'init', array( __CLASS__, 'cctor_register_post_types' ) ,5 );

			//Register Custom Taxonomy
			self::include_file( 'includes/cctor-taxonomy-class.php' );
			add_action( 'init', array( 'Coupon_Creator_Taxonomy_Class', 'cctor_create_taxonomies' ), 10 );

			//Remove Coupon From Search
			add_action( 'pre_get_posts', array( __CLASS__, 'remove_coupon_from_search' ) );

			add_action( 'init', array( __CLASS__, 'init' ) );

			//Localization
			add_action( 'plugins_loaded', array( __CLASS__, 'i18n' ) );

			//Setup Coupon Image Sizes
			add_action( 'init', array( __CLASS__, 'cctor_add_image_sizes' ) );

			//Cron Schedule
			add_action( 'init', array( $this, 'filter_cron_schedules' ) );

			//Load Template Functions
			$this->cctor_Load_Template_Functions();

			//Load Sanitize Functions
			self::include_file( 'admin/cctor-sanitize-class.php' );

			//Load Admin Class if in Admin Section
			if ( is_admin() ) {
				new Coupon_Creator_Plugin_Admin();
			}

		}

	/***************************************************************************/

		/*
		* Initialize Coupon Creator
		* @version 1.70
		*/
		public static function init() {

			//Register Coupon Style
			add_action('wp_enqueue_scripts',  array( __CLASS__, 'cctor_register_style' ));

			//Add Inline Style from Options
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'cctor_inline_style' ), 100);

			//Setup Coupon Image Sizes
			add_action( 'init',  array( __CLASS__, 'cctor_add_image_sizes' ) );

			//Register Coupon Shortcode
			self::include_file( 'includes/cctor-coupon-shortcode-class.php' );
			add_shortcode( 'coupon', array(  'Coupon_Creator_Shortcode', 'cctor_allcoupons_shortcode' ) );

			//Build Shortcode
			self::include_file( 'public/template-build/cctor-shortcode-build.php' );
			add_action( 'cctor_before_coupon', 'cctor_shortcode_functions', 10);

			//Load Single Coupon Template
			add_filter( 'template_include', array(  __CLASS__, 'cctor_get_coupon_post_type_template') );

			//Include Print Template Hook Build
			self::include_file( 'public/template-build/cctor-print-build.php' );

			//Add Print Template Functions
			add_action( 'cctor_action_print_template', 'cctor_print_template', 10);

			//Print Template Inline Custom CSS from Option
			add_action('coupon_print_head', array( __CLASS__, 'cctor_print_css' ), 20);

			//Load Pro Meta Box Cases
			add_filter( 'cctor_filter_terms_tags', array( __CLASS__, 'cctor_terms_allowed_tags' ) , 10 , 1 );

			//Remove wpautop filter on terms fields
			if ( cctor_options('cctor_wpautop') == 1 ) {
				add_filter( 'the_content', array( __CLASS__, 'cctor_remove_autop_for_coupons' ), 0 );
			}

			add_action( 'parse_query', array( __CLASS__, 'parse_query' ), 50 );
		}

	/***************************************************************************/
		/**
		 * Load all the required library files.
		 */
		protected function cctor_Load_Template_Functions() {
			//Load Template Functions
			self::include_file( 'public/template-functions/cctor-function-meta.php' );
			self::include_file( 'public/template-functions/cctor-function-expiration.php' );
			self::include_file( 'public/template-functions/cctor-function-wraps.php' );
			self::include_file( 'public/template-functions/cctor-function-image.php' );
			self::include_file( 'public/template-functions/cctor-function-deal.php' );
			self::include_file( 'public/template-functions/cctor-function-terms.php' );
			self::include_file( 'public/template-functions/cctor-function-links.php' );
		}

	/***************************************************************************/

		public static function i18n() {

	   $cctor_local_path = CCTOR_URL . '/languages/';
       load_plugin_textdomain('coupon-creator', false, $cctor_local_path );

	}
	/***************************************************************************/

		public static function cctor_register_post_types() {

			// if no custom slug use this base slug
			$slug = cctor_options( 'cctor_coupon_base', false, _x( 'cctor_coupon', 'slug', 'coupon-creator' ) );

			$labels = array(
				'name'               => _x( 'Coupons', 'coupon-creator' ),
				'singular_name'      => _x( 'Coupon', 'coupon-creator' ),
				'add_new'            => __( 'Add New', 'coupon-creator' ),
				'add_new_item'       => __( 'Add New Coupon', 'coupon-creator' ),
				'edit_item'          => __( 'Edit Coupon', 'coupon-creator' ),
				'new_item'           => __( 'New Coupon', 'coupon-creator' ),
				'view_item'          => __( 'View Coupon', 'coupon-creator' ),
				'search_items'       => __( 'Search Coupons', 'coupon-creator' ),
				'not_found'          => __( 'No coupons found', 'coupon-creator' ),
				'not_found_in_trash' => __( 'No coupons found in Trash', 'coupon-creator' ),
				'parent_item_colon'  => __( 'Parent Coupon:', 'coupon-creator' ),
				'menu_name'          => __( 'Coupons', 'coupon-creator' ),
				'name_admin_bar'     => __( 'Coupons', 'coupon-creator' ),
				'all_items'          => __( 'All Coupons', 'coupon-creator' ),
				'update_item'        => __( 'Update Coupon', 'coupon-creator' ),
			);
			$args   = array(
				'label'               => __( 'Coupon', 'coupon-creator' ),
				'description'         => __( 'Creates a Coupon as a Custom Post Type', 'coupon-creator' ),
				'labels'              => $labels,
				'supports'            => array( 'title', 'coupon_creator_meta_box' ),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_icon'           => CCTOR_URL . 'admin/images/coupon_creator.png',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => false,
				'can_export'          => true,
				'has_archive'         => false,
				'map_meta_cap'        => true,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'capability_type'     => array( 'cctor_coupon', 'cctor_coupons' ),
				'capabilities' => array(
					'publish_posts'          => 'publish_cctor_coupons',
					'edit_post'              => 'edit_cctor_coupon',
					'edit_posts'             => 'edit_cctor_coupons',
					'edit_others_posts'      => 'edit_others_cctor_coupons',
					'edit_private_posts'     => 'edit_private_cctor_coupons',
					'edit_published_posts'   => 'edit_published_cctor_coupons',
					'read_post'              => 'read_cctor_coupon',
					'read_private_posts'     => 'read_private_cctor_coupons',
					'delete_post'            => 'delete_cctor_coupon',
					'delete_posts'           => 'delete_cctor_coupons',
					'delete_private_posts'   => 'delete_private_cctor_coupons',
					'delete_published_posts' => 'delete_published_cctor_coupons',
					'delete_others_posts'    => 'delete_others_cctor_coupons',
				),
				'rewrite'             => array( 'slug' => $slug ),
			);
			register_post_type( 'cctor_coupon', $args );
		}

	/***************************************************************************/
		/*
		* Activate
		* @version 1.80
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
		* @version 1.80
		*/
		public static function deactivate() {

			if ( ! current_user_can( 'activate_plugins' ) ) { return; }

			flush_rewrite_rules();

		}

	/***************************************************************************/
		/*
		* Remove Coupon Creator Post Type From Search
		* @version 2.2
		*/
		public static function remove_coupon_from_search( $query ) {

			$search = cctor_options( 'coupon-search' );

			//if ( ! cctor_is_coupon_category() && ! $search && $query->is_search && ! is_admin() ) {
			if ( ! $search && $query->is_search && ! is_admin() ) {

				$post_types = get_post_types(array('public' => true, 'exclude_from_search' => false), 'objects');
				$searchable_cpt = array();
				// Add available post types, but remove coupons
				if ( $post_types ) {
					foreach ( $post_types as $type ) {
						if ( $type->name != 'cctor_coupon' ) {
							$searchable_cpt[] = $type->name;
						}
					}
				}

				$query->set( 'post_type', $searchable_cpt );

			}

			return $query;
		}

	/***************************************************************************/

		/*
		* Setup Capabilities
		* @version 1.00
		*/
		public function cctor_add_capabilities() {

			//Administrator
			$caps['administrator'] = array(
				'read_cctor_coupon',
				'read_private_cctor_coupons',
				'edit_cctor_coupon',
				'edit_cctor_coupons',
				'edit_private_cctor_coupons',
				'edit_published_cctor_coupons',
				'edit_others_cctor_coupons',
				'publish_cctor_coupons',
				'delete_cctor_coupon',
				'delete_cctor_coupons',
				'delete_private_cctor_coupons',
				'delete_published_cctor_coupons',
				'delete_others_cctor_coupons',
			);
			//Administrator
			$caps['editor'] = array(
				'read_cctor_coupon',
				'read_private_cctor_coupons',
				'edit_cctor_coupon',
				'edit_cctor_coupons',
				'edit_private_cctor_coupons',
				'edit_published_cctor_coupons',
				'edit_others_cctor_coupons',
				'publish_cctor_coupons',
				'delete_cctor_coupon',
				'delete_cctor_coupons',
				'delete_private_cctor_coupons',
				'delete_published_cctor_coupons',
				'delete_others_cctor_coupons',
			);
			//Author
			$caps['author'] = array(
				'edit_cctor_coupon',
				'read_cctor_coupon',
				'delete_cctor_coupon',
				'delete_cctor_coupons',
				'edit_cctor_coupons',
				'publish_cctor_coupons',
				'edit_published_cctor_coupons',
				'delete_published_cctor_coupons',
			);
			//Contributor
			$caps['contributor'] = array(
				'edit_cctor_coupon',
				'read_cctor_coupon',
				'delete_cctor_coupon',
				'delete_cctor_coupons',
				'edit_cctor_coupons',

			);
			//Subscriber
			$caps['subscriber'] = array(
				'read_cctor_coupon',
			);


			//Filter Capabilities
			if(has_filter('cctor_caps_filter')) {
				$caps = apply_filters('cctor_caps_filter', $caps);
			}

			$roles = array(
				get_role( 'administrator' ),
				get_role( 'editor' ),
				get_role( 'author' ),
				get_role( 'contributor' ),
				get_role( 'subscriber' ),
			);

			foreach ($roles as $role) {

				$role_check = '';
				if ( is_object( $role ) ) {
					$role_check = get_role( $role->name );
				}

				if ( ! empty( $role_check ) ) {
					foreach ( $caps[ $role->name ] as $cap ) {
						$role->add_cap( $cap );
					}
				}
			}

			update_option( 'coupon_creator_capabilties_register', date('l jS \of F Y h:i:s A') );

		}
	/***************************************************************************/
		/*
		* Register Coupon Creator CSS
		* @version 1.00
		*/
		public static function cctor_register_style() {
			if (!is_admin()) {
				$cctor_style = CCTOR_PATH.'css/cctor_coupon.css';
				wp_register_style('coupon_creator_css',  CCTOR_URL . 'css/cctor_coupon.css', false, filemtime($cctor_style));

			}
		}
		/*
		* Add Inline Style From Coupon Options
		* @version 1.80
		*/
		public static function cctor_inline_style() {

			$cctor_option_css = "";
			/*
			*  Filter to Add More Custom CSS
			*/
			if(has_filter('cctor_filter_inline_css')) {
				$coupon_css = "";

				$cctor_option_css = apply_filters('cctor_filter_inline_css', $coupon_css);
			}
			//Add Custom CSS from Options
			if (cctor_options('cctor_custom_css')) {

				$cctor_option_css .= cctor_options('cctor_custom_css');
			}

			wp_add_inline_style( 'coupon_creator_css', wp_kses_post($cctor_option_css) );
		}
		/*
		* Register Coupon Creator Image Sizes
		* @version 1.00
		*/
		public static function cctor_add_image_sizes() {

			$cctor_img_size = array();
			$cctor_img_size['single'] = 300;
			$cctor_img_size['print']  = 390;

			if(has_filter('cctor_img_size')) {
				$cctor_img_size = apply_filters('cctor_img_size', $cctor_img_size);
			}

			add_image_size('single_coupon', $cctor_img_size['single'] );
			add_image_size('print_coupon', $cctor_img_size['print'] );
		}

	/***************************************************************************/
		/*
		* Use Single Coupon Template from Plugin when creating the print version
		* @version 1.00
		*/
		public static function cctor_get_coupon_post_type_template( $print_template ) {
			 global $post;
			 if ( ! is_search() && is_object( $post ) && $post->post_type == 'cctor_coupon') {
				  $print_template = CCTOR_PATH. 'public/templates/print-coupon.php';
			 }
			 return $print_template;
		}

	/***************************************************************************/

		/*
		* Hook Custom CSS into Print Template
		* @version 1.80
		*
		*/
		public static function cctor_print_css(  ) {

			$cctor_option_css = "";
			/*
			*  Filter to Add More Custom CSS
			*/
			if(has_filter('cctor_filter_inline_css')) {
				$coupon_css = "";

				$cctor_option_css = apply_filters('cctor_filter_inline_css', $coupon_css);
			}
			//Add Custom CSS from Options
			if (cctor_options('cctor_custom_css')) {

				$cctor_option_css .= cctor_options('cctor_custom_css');
			}

			if ($cctor_option_css) {
				ob_start(); ?>
				<!--  Coupon Style from the Options Page and Filter -->
					<style type='text/css'>
						<?php echo wp_kses_post($cctor_option_css); ?>
					</style>
				<?php echo ob_get_clean();
			}
		}
	/***************************************************************************/

		/*
		* Allowed Tags for Terms Field
		* @version 2.0
		*/
		public static function cctor_terms_allowed_tags( $cctor_terms_tags ) {

		    $cctor_terms_tags = '<h1><h2><h3><h4><h5><h6><p><blockquote><div><pre><code><span><br><b><strong><em><img><del><ins><sub><sup><ul><ol><li><hr>';

			return $cctor_terms_tags;

		}

	/***************************************************************************/
		/*
		* Remove wpautop in Terms Field
		* @version 2.0
		* based of coding from http://www.wpcustoms.net/snippets/remove-wpautop-custom-post-types/
		*/
		public static function cctor_remove_autop_for_coupons( $content )  {

			'cctor_coupon' === get_post_type() && remove_filter( 'the_content', 'wpautop' );

			return $content;

		}

	/***************************************************************************/

		/**
		 * Add filters to register custom cron schedules
		 *
		 *
		 * @return void
		 */
		public function filter_cron_schedules() {
			add_filter( 'cron_schedules', array( $this, 'register_20min_interval' ) );
		}

		/**
		 * Add a new scheduled task interval (of 20mins).
		 *
		 * @param  array $schedules
		 * @return array
		 */
		public function register_20min_interval( $schedules ) {
			$schedules['every_20mins'] = array(
				'interval' => 20 * MINUTE_IN_SECONDS,
				'display'  => __( 'Once Every 20 Mins', 'cctor_coupon' ),
			);

			return $schedules;
		}

	/***************************************************************************/
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

	/***************************************************************************/

		/**
				 * Set any query flags
				 *
				 * @param WP_Query $query
				 **/
				public static function parse_query( $query ) {

					$types = ( ! empty( $query->query_vars['post_type'] ) ? (array) $query->query_vars['post_type'] : array() );
					// check if a coupon query by post_type
					$query->cctor_is_coupon = ( in_array( 'cctor_coupon', $types ) && count( $types ) < 2 )
						? true // it is a coupon
						: false;

					$query->cctor_is_coupon_category = ! empty ( $query->query_vars[ 'cctor_coupon_category' ] )
						? true // it was an coupon category
						: false;

					$query->cctor_is_coupon_query = ( $query->cctor_is_coupon
					                                 || $query->cctor_is_coupon_category )
						? true // a coupon query of some type
						: false;

						do_action( 'cctor_coupon_parse_query', $query );
				}

	/***************************************************************************/

		/*
		* Include Admin File
		* @version 1.70
		* @param string $file
		*/
		public static function include_file( $file ) {
			include CCTOR_PATH . $file;
		}

	/***************************************************************************/

} //end Coupon_Creator_Plugin Class
<?php
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );
	
	/*
	* Coupon Creator Class
	* @version 1.70
	*/
	class Coupon_Creator_Plugin {

	/**
	 * @var Coupon Creator Pro
	 * @since 1.90
	 */
	private static $instance;

	/**
     * Main Instance
     *
     * Insures that only one instance of Coupon_Creator_Plugin
     *
     * @since 1.90
     * @static
     * @staticvar array $instance
     * @return GEO_Job_Manager
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
			add_action( 'init', array( __CLASS__, 'register_post_types' ) );
			
			//Setup Capabilities
			if ( is_admin() ) {
				$this->add_cctor_capabilities();
			}
			
			add_action( 'init',   array( __CLASS__, 'init' ) );

			//Localization
			add_action('plugins_loaded', array( __CLASS__, 'i18n' ));
			
			//Setup Coupon Image Sizes
			add_action( 'init',  array( __CLASS__, 'cctor_add_image_sizes' ) );
			
			//Load Template Functions
			$this->loadTemplateFunctions();
						
			//Load Admin Class if in Admin Section
			if ( is_admin() )
			new Coupon_Creator_Plugin_Admin();
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
			//add_action( 'init',  array( __CLASS__, 'cctor_add_image_sizes' ) );
			
			//Register Coupon Shortcode
			Coupon_Creator_Plugin::include_file( 'classes/cctor-coupon-shortcode.php' );
			add_shortcode( 'coupon', array(  'Coupon_Creator_Shortcode', 'cctor_allcoupons_shortcode' ) );
			
			//Add Shortcode Functions
			add_action( 'cctor_before_coupon', array( 'Coupon_Creator_Shortcode', 'coupon_shortcode_functions' ), 100);	
			
			//Load Single Coupon Template
			add_filter( 'template_include', array(  __CLASS__, 'get_coupon_post_type_template') );
			
			//Add Print Template Functions
			add_action( 'coupon_print_template', array( __CLASS__, 'coupon_print_template' ), 100);			
			
			//Print Template Inline Custom CSS from Option
			add_action('coupon_print_head', array( __CLASS__, 'print_css' ), 100);				
		}

	/***************************************************************************/		
		/**
		 * Load all the required library files.
		 */
		protected function loadTemplateFunctions() {
			//Load Template Functions
			Coupon_Creator_Plugin::include_file( 'public/template-functions/cctor-template-meta.php' );
			Coupon_Creator_Plugin::include_file( 'public/template-functions/cctor-template-expiration.php' );
			Coupon_Creator_Plugin::include_file( 'public/template-functions/cctor-template-wraps.php' );
			Coupon_Creator_Plugin::include_file( 'public/template-functions/cctor-template-image.php' );
			Coupon_Creator_Plugin::include_file( 'public/template-functions/cctor-template-title.php' );
			Coupon_Creator_Plugin::include_file( 'public/template-functions/cctor-template-deal.php' );
			Coupon_Creator_Plugin::include_file( 'public/template-functions/cctor-template-links.php' );
		}
		
	/***************************************************************************/

	public static function i18n() {

	   $cctor_local_path = CCTOR_URL . '/languages/';
       load_plugin_textdomain('coupon_creator', false, $cctor_local_path );

	}
	/***************************************************************************/
	
	public static function register_post_types() {

		//Load Files
			require_once CCTOR_PATH. 'inc/taxonomy.php';

			// if no custom slug use this base slug
			$slug = coupon_options('cctor_coupon_base');
			$slug = empty( $slug ) ? _x( 'cctor_coupon', 'slug', 'coupon_creator' ) : $slug;

			//Coupon Creator Custom Post Type
			register_post_type( 'cctor_coupon', array(
				'labels'             => array(
					'name'               => _x( 'Coupons', 'coupon_creator' ),
					'singular_name'      => _x( 'Coupon', 'coupon_creator' ),
					'add_new'            => _x( 'Add New', 'coupon_creator' ),
					'add_new_item'       => __( 'Add New Coupon', 'coupon_creator' ),
					'edit_item'          => __( 'Edit Coupon', 'coupon_creator' ),
					'new_item'           => __( 'New Coupon', 'coupon_creator' ),
					'view_item'          => __( 'View Coupon', 'coupon_creator' ),
					'search_items'       => __( 'Search Coupons', 'coupon_creator' ),
					'not_found'          => __( 'No coupons found', 'coupon_creator' ),
					'not_found_in_trash' => __( 'No coupons found in Trash', 'coupon_creator' ),
					'parent_item_colon'  => __( 'Parent Coupon:', 'coupon_creator' ),
					'menu_name'          => __( 'Coupons', 'coupon_creator' ),
				),
				'hierarchical'		 => false,
				'description' 		 => 'Creates a Coupon as a Custom Post Type',
				'public'             => true,
				'publicly_queryable' => true,
				'exclude_from_search' => true,
				'show_ui'            => true,
				'show_in_nav_menus'  => false,
				'show_in_menu'       => true,
				'query_var'          => true,
				'can_export'		 => true,
				'capability_type'	 => array("cctor_coupon", "cctor_coupons"),
				'has_archive'        => false,
				'rewrite'            => array( 'slug' => $slug ),
				'menu_icon'          => CCTOR_URL . 'admin/images/coupon_creator.png',
				//Supported Meta Boxes
				'supports'           => array( 'title', 'coupon_creator_meta_box','custom-fields' ),
			) );
						
			//Load Coupon Creator Custom Taxonomy
			coupon_creator_create_taxonomies();
			
	}	
	
	/***************************************************************************/
		/*
		* Activate
		* @version 1.80
		*/
		public static function activate() {	
			// Flush rewrite rules so that users can access custom post types on the
			self::register_post_types();
			flush_rewrite_rules();
		}

		/*
		* Deactivate
		* @version 1.80
		*/
		public static function deactivate() {
			flush_rewrite_rules();
		}

	/***************************************************************************/

		/*
		* Setup Capabilities
		* @version 1.00
		*/
		public function add_cctor_capabilities() {
		
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
				foreach ($caps[$role->name] as $cap) {
					$role->add_cap( $cap );
				}
			}
					
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
			
			//$cctor_option_css = "";
			/* 
			*  Filter the Dimensions and Min Height
			*/
			if(has_filter('cctor_filter_inline_css')) {
				$coupon_css = "";
				
				$cctor_option_css = apply_filters('cctor_filter_inline_css', $coupon_css);
			} 
			//Add Custom CSS from Options				
			if (coupon_options('cctor_custom_css')) {
					
				$cctor_option_css .= coupon_options('cctor_custom_css');				
			}
			
			wp_add_inline_style( 'coupon_creator_css', $cctor_option_css );
		}
		/*
		* Register Coupon Creator Image Sizes
		* @version 1.00
		*/
		public static function cctor_add_image_sizes() {
		
			$cctor_img_size = array();
			$cctor_img_size['single'] = 300;
			$cctor_img_size['print']  = 400;

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
		public static function get_coupon_post_type_template($print_template) {
			 global $post;
			 if ($post->post_type == 'cctor_coupon') {
				  $print_template = CCTOR_PATH. 'public/templates/single-coupon.php';
			 }
			 return $print_template;
		}
	/***************************************************************************/
		/*
		* Add Content to Print Template
		* @version 1.90
		*/
		public static function coupon_print_template() {	 
		
			add_action('coupon_print_meta', 'cctor_print_head_and_meta', 5, 1 ); 

			add_filter('cctor_print_expiration_check', 'cctor_expiration_and_current_date', 10 , 1);

			add_filter('cctor_print_image_url', 'cctor_show_print_img_url', 10 , 1);

			add_filter('cctor_print_outer_content_wrap', 'cctor_return_outer_coupon_wrap', 10 , 1);

			add_action('cctor_print_image_coupon', 'cctor_show_print_img', 10, 1 ); 

			add_filter('cctor_print_inner_content_wrap', 'cctor_return_print_inner_coupon_wrap', 10 , 1);

			add_action('cctor_print_title_coupon', 'cctor_show_title', 10, 1 ); 

			add_action('cctor_print_deal_coupon', 'cctor_show_deal', 10, 1 ); 

			add_action('cctor_print_expiration_coupon', 'cctor_show_expiration', 10, 1 ); 

			add_action('cctor_click_to_print_coupon', 'cctor_show_print_click', 10, 1 ); 

			add_action('cctor_print_no_show_coupon', 'cctor_show_no_coupon_comment', 10, 1 ); 		

			do_action( 'cctor_print_template_functions' );
		}		  
	/***************************************************************************/

		/*
		* Hook Custom CSS into Print Template
		* @version 1.80
		* 
		*/
		public static function print_css(  ) {
		
			if (coupon_options('cctor_custom_css')) {
				ob_start(); ?>
				<!-- User Coupon Style from the options Page -->
					<style type='text/css'>
						<?php echo coupon_options('cctor_custom_css'); ?>
					</style>
				<?php echo ob_get_clean();
			}
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
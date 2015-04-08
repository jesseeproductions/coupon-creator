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
			add_action( 'init', array( __CLASS__, 'cctor_register_post_types' ) );
			
			//Register Custom Taxonomy
			Coupon_Creator_Plugin::include_file( 'classes/cctor-taxonomy-class.php' );
			new Coupon_Creator_Taxonomy_Class();
			
			//Setup Capabilities
			if ( is_admin() ) {
				$this->cctor_add_capabilities();
			}
			
			add_action( 'init',   array( __CLASS__, 'init' ) );

			//Localization
			add_action('plugins_loaded', array( __CLASS__, 'i18n' ));
			
			//Setup Coupon Image Sizes
			add_action( 'init',  array( __CLASS__, 'cctor_add_image_sizes' ) );
			
			//Load Template Functions
			$this->cctor_Load_Template_Functions();
						
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
			
			//Load Sanitize Functions
			Coupon_Creator_Plugin::include_file( 'admin/cctor-sanitize.php' );
			
			//Register Coupon Style
			add_action('wp_enqueue_scripts',  array( __CLASS__, 'cctor_register_style' ));
			
			//Add Inline Style from Options
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'cctor_inline_style' ), 100);
			
			//Setup Coupon Image Sizes
			add_action( 'init',  array( __CLASS__, 'cctor_add_image_sizes' ) );
			
			//Register Coupon Shortcode
			Coupon_Creator_Plugin::include_file( 'classes/cctor-coupon-shortcode-class.php' );
			add_shortcode( 'coupon', array(  'Coupon_Creator_Shortcode', 'cctor_allcoupons_shortcode' ) );
				
			//Build Shortcode
			Coupon_Creator_Plugin::include_file( 'public/template-build/cctor-shortcode-build.php' );
			add_action( 'cctor_before_coupon', 'cctor_shortcode_functions', 100);	
			
			//Load Single Coupon Template
			add_filter( 'template_include', array(  __CLASS__, 'cctor_get_coupon_post_type_template') );
			
			//Include Print Template Hook Build
			Coupon_Creator_Plugin::include_file( 'public/template-build/cctor-print-build.php' );	
			
			//Add Print Template Functions
			add_action( 'cctor_action_print_template', 'cctor_print_template', 100);			
			
			//Print Template Inline Custom CSS from Option
			add_action('coupon_print_head', array( __CLASS__, 'cctor_print_css' ), 100);		

			//Load Pro Meta Box Cases
			add_filter( 'cctor_filter_terms_tags', array( __CLASS__, 'cctor_terms_allowed_tags' ) , 10 , 1 );		
			
			//Remove wpautop filter on terms fields
			if ( cctor_options('cctor_wpautop') == 1 ) {
				add_filter( 'the_content', array( __CLASS__, 'cctor_remove_autop_for_coupons' ), 0 );  	
			}
		}

	/***************************************************************************/		
		/**
		 * Load all the required library files.
		 */
		protected function cctor_Load_Template_Functions() {
			//Load Template Functions
			Coupon_Creator_Plugin::include_file( 'public/template-functions/cctor-function-meta.php' );
			Coupon_Creator_Plugin::include_file( 'public/template-functions/cctor-function-expiration.php' );
			Coupon_Creator_Plugin::include_file( 'public/template-functions/cctor-function-wraps.php' );
			Coupon_Creator_Plugin::include_file( 'public/template-functions/cctor-function-image.php' );
			Coupon_Creator_Plugin::include_file( 'public/template-functions/cctor-function-deal.php' );
			Coupon_Creator_Plugin::include_file( 'public/template-functions/cctor-function-terms.php' );
			Coupon_Creator_Plugin::include_file( 'public/template-functions/cctor-function-links.php' );
		}
		
	/***************************************************************************/

	public static function i18n() {

	   $cctor_local_path = CCTOR_URL . '/languages/';
       load_plugin_textdomain('coupon_creator', false, $cctor_local_path );

	}
	/***************************************************************************/
	
	public static function cctor_register_post_types() {

			// if no custom slug use this base slug
			$slug = cctor_options('cctor_coupon_base');
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
				'map_meta_cap'		 => true,
				'has_archive'        => false,
				'rewrite'            => array( 'slug' => $slug ),
				'menu_icon'          => CCTOR_URL . 'admin/images/coupon_creator.png',
				//Supported Meta Boxes
				'supports'           => array( 'title', 'coupon_creator_meta_box' ),
			) );
						
	}	
	
	/***************************************************************************/
		/*
		* Activate
		* @version 1.80
		*/
		public static function activate() {	
			// Flush rewrite rules so that users can access custom post types on the
			self::cctor_register_post_types();
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
		public static function cctor_get_coupon_post_type_template($print_template) {
			 global $post;
			 if ($post->post_type == 'cctor_coupon') {
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
<?php
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );
	
	/*
	* Coupon Creator Class
	* @version 1.70
	*/
	class Coupon_Creator_Plugin {
		/*
		* Plugin file
		* @var string
		* @version 1.70
		*/
		public static $file;
		/*
		* Plugin dirname
		* @var string
		* @version 1.70
		*/
		public static $dirname;

	/***************************************************************************/

		/*
		* Bootstrap
		* @version 1.70
		*/
		public static function bootstrap( $file ) {
			self::$file    = $file;
			self::$dirname = dirname( $file );
			
			//Register Post Type			
			add_action( 'init', array( __CLASS__, 'register_post_types' ) );
			
			add_action( 'init',   array( __CLASS__, 'init' ) );

			//Localization
			add_action('plugins_loaded', array( __CLASS__, 'i18n' ));
			
			//Setup Coupon Image Sizes
			add_action( 'init',  array( __CLASS__, 'cctor_add_image_sizes' ) );

			//Load Admin Class if in Admin Section
			if ( is_admin() )
			Coupon_Creator_Plugin_Admin::bootstrap();
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
			//Create the Shortcode
			add_shortcode( 'coupon', array(  __CLASS__, 'cctor_allcoupons_shortcode' ) );
			//Load Single Coupon Template
			add_filter( 'template_include', array(  __CLASS__, 'get_coupon_post_type_template') );
			//Print Template Inline Custom CSS from Option
			add_action('coupon_print_head', array( __CLASS__, 'print_css' ), 100);				
		}

	/***************************************************************************/

	public static function i18n() {

	   $cctor_local_path = dirname( plugin_basename( self::$file ) ) . '/languages/';
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
				'capability_type'    => 'post',
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
		/**
		 * Activate
		 */
		public static function activate() {	
			// Flush rewrite rules so that users can access custom post types on the
			self::register_post_types();
			flush_rewrite_rules();
		}

		/**
		 * Deactivate
		 */
		public static function deactivate() {
			flush_rewrite_rules();
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
		* Register Coupon Creator Shortcode
		* @version 1.00
		*/
		public static function cctor_allcoupons_shortcode($atts) {
			   //Load Stylesheet for Coupon Creator when Shortcode Called
			   if( !wp_style_is( 'coupon_creator_css' ) ) {
				 wp_enqueue_style('coupon_creator_css');
			   }	 
			   //Coupon ID is the Custom Post ID
			   extract(shortcode_atts(array(
				"totalcoupons" => '-1',
				"couponid" => '',
				"coupon_align" => 'cctor_alignnone',
				"couponorderby" => 'date',
				"category" => ''
				), $atts ) );

				// Setup Query for Either Single Coupon or a Loop
					$args = array(
					'p' => $couponid,
					'posts_per_page' => $totalcoupons,
					'cctor_coupon_category' => $category,
					'post_type' => 'cctor_coupon',
					'post_status' => 'publish',
					'orderby' => $couponorderby
				);
					$alloutput = '';

					$allcouponpost = new WP_Query($args);

				// The Coupon Loop
				while ($allcouponpost->have_posts()) {

				$allcouponpost->the_post();
				$couponid = $allcouponpost->post->ID;

				// Custom Fields from Post Type
				$couponborder = get_post_meta($couponid, 'cctor_couponborder', true);
				$amountco = get_post_meta($couponid, 'cctor_amount', true);
				$colordiscount = get_post_meta($couponid, 'cctor_colordiscount', true);
				$colorheader = get_post_meta($couponid, 'cctor_colorheader', true);
				$expirationco = get_post_meta($couponid, 'cctor_expiration', true);
				$bordercolor = get_post_meta($couponid, 'cctor_bordercolor', true);
				$couponimage_id = get_post_meta($couponid, 'cctor_image', true);
				$couponimage = wp_get_attachment_image_src($couponimage_id, 'single_coupon');
				$couponimage = $couponimage[0];
				$permalink = get_permalink( $couponid );
				$descriptionco = get_post_meta($couponid, 'cctor_description', true);
				$daymonth_date_format = get_post_meta($couponid, 'cctor_date_format', true); //get the ignore expiration checkbox value
				
				//Build Click to Print Link - First Check if Option to Hide is Checked
				if (coupon_options('cctor_hide_print_link') == 0) {
					$nofollow = "";
					if (coupon_options('cctor_nofollow_print_link') == 1) {
						$nofollow = "rel='nofollow'";
					}
					//Set Image Link
					$couponimglink = "<a target='_blank' ".$nofollow." href='".$permalink."' title='Click to Open in Print View'><img class='cctor_coupon_image' src='".$couponimage."' alt='".get_the_title()."' title='Coupon ".get_the_title()."'></a>";
				
					$clicktoprintlink =	"<div class='cctor_opencoupon'><a ".$nofollow." href='".$permalink." 'onclick='window.open(this.href);return false;'>".__('Click to Open in Print View','coupon_creator')."</a></div><!--end .opencoupon -->";
					
				} else {
					//No Links for Image Coupon or Click to Print
					$couponimglink = "<img class='cctor_coupon_image' src='".$couponimage."' alt='".get_the_title()."' title='Coupon ".get_the_title()."'>";
					$clicktoprintlink =	"<div class='cctor_opencoupon'></div>";
				}				
				//Check Expiration if past date then exit
				$cc_blogtime = current_time('mysql');
				list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = preg_split( '([^0-9])', $cc_blogtime );
				$cc_today = strtotime($today_month."/".$today_day."/". $today_year);
				$cc_expiration_date = strtotime($expirationco);
				$ignore_expiration = get_post_meta($couponid, 'cctor_ignore_expiration', true); //get the ignore expiration checkbox value

				if ($cc_expiration_date >= $cc_today || $ignore_expiration == 1 ) { // Display coupon if expiration date is in future or if ignore box checked
					//Start Single View
					$alloutput .=  "<div class='cctor_coupon_container ". $coupon_align ."'>";
						// If Image Use as Coupon
						if ($couponimage) {
						
						$alloutput .=  $couponimglink;

						//No Image Create Coupon
						} else {
						$alloutput .=  "<div class='cctor_coupon'>";
						$alloutput .=  "<div class='cctor_coupon_content' style='border-color:".$bordercolor."!important;'>";
						$alloutput .=  "<h3 style='background-color:".$colordiscount."!important; color:".$colorheader."!important;'>" . $amountco . "</h3>";
						$alloutput .=	"<div class='cctor_deal'>".$descriptionco."</div>";
						if ($expirationco) {  // Only Display Expiration if Date
							if ($daymonth_date_format == 1 ) { //Change to Day - Month Style
								$expirationco = date("d/m/Y", $cc_expiration_date);
							}
						$alloutput .=	"<div class='cctor_expiration'>".__('Expires on:','coupon_creator')."&nbsp;".$expirationco."</div>";
							} //end If Expiration
						$alloutput .=	"</div> <!--end .coupon --></div> <!--end .cctor_coupon -->";
						}
					//Add Link to Open in Print View
					$alloutput .=	$clicktoprintlink;
					$alloutput .= 	"</div><!--end .cctor_coupon_container -->";
				} else {
					$alloutput .=  "<!-- ".get_the_title()." has expired on ".$expirationco." -->";
				}//End Coupon Display

			} //End While

			/* Restore original Post Data */
			wp_reset_postdata();

			// Return Variables
			return $alloutput;
		} //end cctor_allcoupons_shortcode

	/***************************************************************************/
		/*
		* Use Single Coupon Template from Plugin when creating the print version
		* @version 1.00
		*/
		function get_coupon_post_type_template($print_template) {
			 global $post;
			 if ($post->post_type == 'cctor_coupon') {
				  $print_template = CCTOR_PATH. 'templates/single-coupon.php';
			 }
			 return $print_template;
		}

	/***************************************************************************/

		/*
		* Hook Custom CSS into Print Template
		* @version 1.80
		* @param string $file
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
	
} //end Coupon_Creator_Plugin Class
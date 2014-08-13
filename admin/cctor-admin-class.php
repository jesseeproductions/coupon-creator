<?php
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

if ( ! class_exists( 'Coupon_Creator_Plugin_Admin' ) ) {	
	/*
	* Coupon Creator Admin Class
	* @version 1.70
	*/
	class Coupon_Creator_Plugin_Admin {
		/*
		* Admin Construct
		* @version 1.90
		*/
		public function __construct() {
			add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
			add_action( 'admin_init', array( __CLASS__, 'admin_upgrade' ) );
			add_action('admin_init', array( __CLASS__, 'cctor_flush_permalinks'));
			
			//Load Sanitize Functions
			Coupon_Creator_Plugin::include_file( 'admin/cctor-sanitize.php' );
			
			//Load Coupon Options Class
			Coupon_Creator_Plugin::include_file( 'admin/cctor-admin-options-class.php' );
			new Coupon_Creator_Plugin_Admin_Options();
			
			//Load Coupon Meta Box Class
			Coupon_Creator_Plugin::include_file( 'admin/cctor-post-meta-box-class.php' );
			new Coupon_Creator_Meta_Box();
		}
		
	/***************************************************************************/			
		/*
		* Admin Initialize Coupon Creator
		* @version 1.70
		*/
		public static function admin_init() {
			//Load Admin Coupon Scripts
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_style_scripts' ) );
			//Add Column Headers
			add_action( 'manage_posts_custom_column', array( __CLASS__, 'coupon_custom_column' ), 10, 2 );
			// Filter Columns
			add_filter( 'manage_edit-cctor_coupon_columns' ,  array( __CLASS__, 'coupon_columns' ) );

			//Add Button for Coupons in Editor
			Coupon_Creator_Plugin::include_file( 'admin/cctor-admin-inserter-class.php' );
			new Coupon_Creator_Inserter();
			
			//Add Options Link on Plugin Activation Page
			add_action('plugin_action_links', array( __CLASS__, 'plugin_setting_link' ) , 10, 2);			
	

		} //end admin_init
	/***************************************************************************/
	
	/*
	* Update Version Number Check
	* @version 1.70
	*/
	public static function admin_upgrade() {
		//Update Version Number
		if (get_option(CCTOR_VERSION_KEY) != CCTOR_VERSION_NUM) {
			// Then update the version value
			update_option(CCTOR_VERSION_KEY, CCTOR_VERSION_NUM);
		} 
	}
	/***************************************************************************/
	/*
	* Flush Permalink on Coupon Option Change
	* @version 1.80
	*/	
	public static function cctor_flush_permalinks() {
		if ( get_option('cctor_coupon_base_change') == true ) {
			flush_rewrite_rules();
			update_option('cctor_coupon_base_change', false);
		}
	}
		
	/***************************************************************************/
	/*
	* Add Options Link in Plugin entry of Plugins Menu
	* @version 1.70
	*/
	public static function plugin_setting_link($links, $file) {
		static $this_plugin;
	 
		if (!$this_plugin) {
			$this_plugin = 'coupon-creator/coupon_creator.php';
		}

		// make sure this is the coupon creator
		if ($file == $this_plugin) {
			$plugin_links[] = '<a href="' . get_bloginfo('wpurl') .'/wp-admin/edit.php?post_type=cctor_coupon&page=coupon-options">Options</a>';
	 
			// add the settings link to the links
			foreach($plugin_links as $link) {
				array_unshift($links, $link);
			}
		}
	 
		return $links;
	}		
		
	/***************************************************************************/

		/*
		* Register and Enqueue Style and Scripts
		* @version 1.80
		*/
		public static function enqueue_style_scripts( ) {
				
			$screen = get_current_screen();
						
			if ( 'cctor_coupon' == $screen->id ) {
						
				//Styles
				//Date Picker CSS
				$coupon_creator_admin = CCTOR_PATH.'admin/css/meta.css';
				wp_enqueue_style( 'coupon_creator_admin', CCTOR_URL . 'admin/css/meta.css', false, filemtime($coupon_creator_admin));
				//Style or WP Color Picker
				wp_enqueue_style( 'wp-color-picker' );  
				//Image Upload CSS
				wp_enqueue_style('thickbox');

				//Scripts
				//Media Manager from 3.5
				wp_enqueue_media();
				 
				//Script for WP Color Picker
				wp_enqueue_script( 'wp-color-picker' );
				$cctor_coupon_meta_js = CCTOR_PATH.'admin/js/cctor_coupon_meta.js';
				wp_enqueue_script('cctor_coupon_meta_js',  CCTOR_URL . '/admin/js/cctor_coupon_meta.js', array('jquery', 'media-upload','thickbox','farbtastic'), filemtime($cctor_coupon_meta_js), true);	
				
				//Script for Datepicker
				wp_enqueue_script('jquery-ui-datepicker');
				
				//Color Box For How to Videos
				$cctor_colorbox_css = CCTOR_PATH.'admin/colorbox/colorbox.css';
				wp_enqueue_style('cctor_colorbox_css', CCTOR_URL . '/admin/colorbox/colorbox.css', false, filemtime($cctor_colorbox_css));	
				
				$cctor_colorbox_js = CCTOR_PATH.'admin/colorbox/jquery.colorbox-min.js';
				wp_enqueue_script('cctor_colorbox_js',  CCTOR_URL . '/admin/colorbox/jquery.colorbox-min.js' ,array('jquery'), filemtime($cctor_colorbox_js), true);
				
				//do_action('cctor_admin_scripts');
			
			}
		}	
		
		/***************************************************************************/

		/*
		* Setup Custom Columns
		* @version 1.70
		* @param array $columns
		*/
		public static function coupon_columns( $columns ) {
			$cctor_columns = array();
		
			if( isset( $columns['cb'] ) ) {
				$cctor_columns['cb'] = $columns['cb'];
			}
		
			if( isset( $columns['title'] ) ) {
				$cctor_columns['title'] = __( 'Coupon Title', 'coupon_creator' );
			}
		
			if( isset( $columns['author'] ) ) {
				$cctor_columns['author'] = $columns['author'];
			}
			
			$cctor_columns['cctor_coupon_shortcode'] = __( 'Shortcode', 'coupon_creator' );
			
			$cctor_columns['cctor_coupon_expiration'] = __( 'Expiration Date', 'coupon_creator' );
		
			$cctor_columns['cctor_coupon_ignore_expiration'] = __( 'Ignore Expiration', 'coupon_creator' );
		
			if( isset( $columns['date'] ) ) {
				$cctor_columns['date'] = $columns['date'];
			}
			
			return $cctor_columns;
		}
		/*
		* Add Custom Meta Data to Columns
		* @version 1.70
		*/
		public static function coupon_custom_column( $column, $post_id ) {
			switch( $column ) {
				case 'cctor_coupon_shortcode':
					echo "<code>[coupon couponid='". $post_id ."' name='". get_the_title($post_id) ."']</code>";
					break;			
				case 'cctor_coupon_expiration':
					echo get_post_meta( $post_id, 'cctor_expiration', true );
					break;
				case 'cctor_coupon_ignore_expiration':
					if (get_post_meta( $post_id, 'cctor_ignore_expiration', true ) == 1) {
						echo "Yes";
					}
					break;
			}
		}
		
		/***************************************************************************/

	} //end Coupon_Creator_Plugin_Admin Class
	
} // class_exists( 'Coupon_Creator_Plugin_Admin' )
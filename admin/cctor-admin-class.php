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

			//Setup Admin
			add_action( 'admin_init', array( __CLASS__, 'cctor_admin_init' ) );

			//Update Version Number
			add_action( 'admin_init', array( __CLASS__, 'cctor_admin_upgrade' ) );

			//Flush Permalinks on Permalink Option Change
			add_action('admin_init', array( __CLASS__, 'cctor_flush_permalinks'));

			Coupon_Creator_Plugin::include_file( 'admin/cctor-admin-columns.php' );
			new Coupon_Admin_Columns();

			//Load Coupon Options Class
			Coupon_Creator_Plugin::include_file( 'admin/cctor-options-class.php' );
			new Coupon_Creator_Plugin_Admin_Options();

			//Load Coupon Meta Box Class
			Coupon_Creator_Plugin::include_file( 'admin/cctor-meta-box-class.php' );
			new Coupon_Creator_Meta_Box();

			//Init Pro Updater Class
			add_action('admin_init',  array(__CLASS__, 'cctor_activate_license'));

			add_action('admin_init',  array(__CLASS__, 'cctor_deactivate_license'));
		}

	/***************************************************************************/
		/*
		* Admin Initialize Coupon Creator
		* @version 1.70
		*/
		public static function cctor_admin_init() {

			if ( !class_exists( 'Coupon_Creator_Pro_Plugin' ) ) {
				//Add Button for Coupons in Editor
				Coupon_Creator_Plugin::include_file( 'admin/cctor-inserter-class.php' );
				new Coupon_Creator_Inserter();
			}
			//Add Options Link on Plugin Activation Page
			add_action('plugin_action_links', array( __CLASS__, 'plugin_setting_link' ) , 10, 2);

			//Load Admin Coupon Scripts
			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'cctor_edit_enqueue_style_scripts' ) );

		} //end admin_init
	/***************************************************************************/

	/*
	* Update Version Number Check
	* @version 1.70
	*/
	public static function cctor_admin_upgrade() {
		//Update Version Number
		if (get_option(CCTOR_VERSION_KEY) != CCTOR_VERSION_NUM) {
			// Then update the version value

			update_option( 'coupon_update_version', date('l jS \of F Y h:i:s A') );

			Coupon_Creator_Plugin::include_file( 'admin/cctor-update-scripts.php' );
			new Coupon_Update_Scripts();

			update_option(CCTOR_VERSION_KEY, CCTOR_VERSION_NUM);

			update_option( 'cctor_coupon_base_change', TRUE );

		}
	}

	/***************************************************************************/
	/*
	* Flush Permalink on Coupon Option Change
	* @version 1.80
	*/
		public static function cctor_flush_permalinks() {
			if ( get_option( 'cctor_coupon_base_change' ) == true || get_option( 'cctor_coupon_category_base_change' ) == true ) {

				Coupon_Creator_Plugin::cctor_register_post_types();
				flush_rewrite_rules();
				update_option( 'coupon_flush_perm_change', date( 'l jS \of F Y h:i:s A' ) );
				update_option( 'cctor_coupon_base_change', false );
				update_option( 'cctor_coupon_category_base_change', false );
		}
		}

	/***************************************************************************/
	/*
	* Add Options Link in Plugin entry of Plugins Menu
	* @version 2.0.3
	*/
	public static function plugin_setting_link($links, $file) {
		static $this_plugin;

		if (!$this_plugin) {
			$this_plugin = 'coupon-creator/coupon_creator.php';
		}

		// make sure this is the coupon creator
		if ($file == $this_plugin) {

			//Show Options Link
			$plugin_links[] = '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/edit.php?post_type=cctor_coupon&page=coupon-options">Options</a>';

			//Show Upgrade to Pro Link
			if ( !defined( 'CCTOR_HIDE_UPGRADE' ) || !CCTOR_HIDE_UPGRADE )  {
				$plugin_links[] = '<a href="http://cctor.link/Abqoi">Upgrade to Pro!</a>';
			}

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
		public static function cctor_edit_enqueue_style_scripts( ) {

			$screen = get_current_screen();

			if ( 'cctor_coupon' == $screen->id ) {

				//Styles
				$cctor_meta_css = CCTOR_PATH.'admin/css/admin-style.css';
				wp_enqueue_style( 'coupon-admin-style', CCTOR_URL . 'admin/css/admin-style.css', false, filemtime($cctor_meta_css));

				//Style or WP Color Picker
				wp_enqueue_style( 'wp-color-picker' );
				//Image Upload CSS
				wp_enqueue_style('thickbox');

				//jQuery UI
				global $wp_scripts;
				$jquery_version = isset( $wp_scripts->registered['jquery-ui-core']->ver ) ? $wp_scripts->registered['jquery-ui-core']->ver : '1.9.2';
				wp_enqueue_style( 'jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/' . $jquery_version . '/themes/smoothness/jquery-ui.css' );

				//Scripts
				//Media Manager from 3.5
				wp_enqueue_media();

				//Script for WP Color Picker
				wp_enqueue_script( 'wp-color-picker' );
				$cctor_coupon_meta_js = CCTOR_PATH.'admin/js/cctor_coupon_meta.js';
				wp_enqueue_script('cctor_coupon_meta_js',  CCTOR_URL . 'admin/js/cctor_coupon_meta.js', array('jquery', 'media-upload','thickbox','farbtastic'), filemtime($cctor_coupon_meta_js), true);

				//Localize Pro Meta Script
				wp_localize_script( 'cctor_coupon_meta_js', 'cctor_meta_js', array(
					'cctor_disable_content_msg' => __( ' Content Fields are disabled when using an Image Coupon' , 'coupon_creator_pro'),
					'cctor_disable_style_msg' => __( ' Style Fields are disabled when using an Image Coupon' , 'coupon_creator_pro')
					));


				//Script for Datepicker
				wp_enqueue_script('jquery-ui-datepicker');

				//Tabs
				wp_enqueue_script( 'jquery-ui-tabs' );
				
				//Accordian
				wp_enqueue_script( 'jquery-ui-accordion' );

				//Dialogs
				wp_enqueue_script( 'jquery-ui-dialog' );

				//Color Box For How to Videos
				$cctor_colorbox_css = CCTOR_PATH.'vendor/colorbox/colorbox.css';
				wp_enqueue_style('cctor_colorbox_css', CCTOR_URL . 'vendor/colorbox/colorbox.css', false, filemtime($cctor_colorbox_css));

				$cctor_colorbox_js = CCTOR_PATH.'vendor/colorbox/jquery.colorbox-min.js';
				wp_enqueue_script('cctor_colorbox_js',  CCTOR_URL . 'vendor/colorbox/jquery.colorbox-min.js' ,array('jquery'), filemtime($cctor_colorbox_js), true);

				//Hook to Load New Styles and Scripts
				do_action('cctor_meta_scripts_styles');

			}
		}

		/***************************************************************************/

		/*
		* Register and Enqueue Style and Scripts on Coupon Edit Screens
		* since 1.90
		*/
		public static function cctor_activate_license() {

			// listen for our activate button to be clicked
			if( isset( $_POST['cctor_license_activate'] ) ) {

				// run a quick security check
				if( ! check_admin_referer( 'cctor_license_nonce', 'cctor_license_nonce' ) ) {
					return false; // get out if we didn't click the Activate button
				}

				//Set WordPress Option Name
				$license_option_name = esc_attr($_POST['cctor_license_key']);

				// retrieve the license from the database
				$cctor_license_info = get_option( $license_option_name );

				//Check if the License has changed and deactivate
				if ($_POST['coupon_creator_options'][$license_option_name] != $cctor_license_info['key']) {

					$cctor_license_info['key'] = esc_attr(trim($_POST['coupon_creator_options'][$license_option_name]));

					delete_option( $license_option_name );

					update_option( $license_option_name, $cctor_license_info);

				}

				// data to send in our API request
				$api_params = array(
					'edd_action'=> 'activate_license',
					'license' 	=> esc_attr(trim($cctor_license_info['key'])),
					'item_name' => urlencode( esc_attr($_POST['cctor_license_name']) ), // the name of our product in EDD
					'url'       => home_url()
				);

				// Call the custom API.
				$response = wp_remote_get( esc_url_raw( add_query_arg( $api_params, COUPON_CREATOR_STORE_URL ) ), array( 'timeout' => 15, 'sslverify' => false ) );

				// make sure the response came back okay
				if ( is_wp_error( $response ) ) {
					return false;
				}
				// decode the license data
				$license_data = json_decode( wp_remote_retrieve_body( $response ) );

				//Remove Current Expiration
				$cctor_license_info['status'] = "nostatus";

				//Get Status of Key
				$cctor_license_info['status']  = esc_html($license_data->license);

				//Remove Current Expiration
				unset($cctor_license_info['expires']);

				//Set Expiration Date  for This License
				$cctor_license_info['expires'] = esc_html($license_data->expires);

				//if Expired Add that to the option.
				if ( isset( $license_data->error ) && $license_data->error == "expired" ) {
					$cctor_license_info['expired'] = esc_html($license_data->error);
				}

				//if Expired Add that to the option.
				if ( isset( $license_data->error ) &&  $license_data->error == "missing" ) {
					unset($cctor_license_info['expires']);
					unset($cctor_license_info['expired']);
					$cctor_license_info['status']  = esc_html($license_data->error);
				}

				//Update License Object
				update_option( $license_option_name, $cctor_license_info );

			}

			return true;
		}

		/*
		* Get Support Information for Options and Meta Field
		* @deprecated since 2.3
		*/
		public static function get_cctor_support_core_infomation() {

			_deprecated_function( __FUNCTION__, '2.3', 'Coupon_Creator_Help_Class' );

		}
		/***************************************************************************/

		/*
		* Deactivate a license key.
		* since 1.90
		*/
		public static function cctor_deactivate_license() {

			// listen for our activate button to be clicked
			if( isset( $_POST['cctor_license_deactivate'] ) ) {

				// run a quick security check
				if ( ! check_admin_referer( 'cctor_license_nonce', 'cctor_license_nonce' ) ) {
					return false; // get out if we didn't click the Activate button
				}

				$license_option_name = esc_attr( $_POST['cctor_license_key'] );

				// retrieve the license from the database
				$cctor_license_info = get_option( $license_option_name );

				// data to send in our API request
				$api_params = array(
					'edd_action' => 'deactivate_license',
					'license'    => esc_attr( trim( $cctor_license_info['key'] ) ),
					'item_name'  => urlencode( esc_attr( $_POST['cctor_license_name'] ) ),
					// the name of our product in EDD
					'url'        => home_url()
				);

				// Call the custom API.
				$response = wp_remote_get( esc_url_raw( add_query_arg( $api_params, COUPON_CREATOR_STORE_URL ) ), array( 'timeout'   => 15,
				                                                                                                         'sslverify' => false
				) );

				// make sure the response came back okay
				if ( is_wp_error( $response ) ) {
					return false;
				}

				// decode the license data
				$license_data = json_decode( wp_remote_retrieve_body( $response ) );

				// $license_data->license will be either "deactivated" or "failed"
				if ( $license_data->license == 'deactivated' || $license_data->license == 'failed' ) {

					unset( $cctor_license_info['status'] );
					unset( $cctor_license_info['expires'] );

					//Update License Object
					update_option( $license_option_name, $cctor_license_info );
				}

			}

			return true;
		}

			/***************************************************************************/

	} //end Coupon_Creator_Plugin_Admin Class

} // class_exists( 'Coupon_Creator_Plugin_Admin' )
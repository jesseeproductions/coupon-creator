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

			// Remove Coupon Row Actions
			add_filter( 'post_row_actions',  array( __CLASS__, 'cctor_remove_coupon_row_actions'), 10, 2 );

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

			// Add Columns
			add_filter( 'manage_edit-cctor_coupon_columns' ,  array( __CLASS__, 'cctor_list_columns' ) );

			//Custom Column Cases
			add_action( 'manage_posts_custom_column', array( __CLASS__, 'cctor_column_cases' ), 10, 2 );

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

			self::cctor_update_ignore_expiration();

			update_option(CCTOR_VERSION_KEY, CCTOR_VERSION_NUM);
		}
	}

	/***************************************************************************/
	/*
	* On Update Query Coupons and Change cctor_ignore_expiration value from on to 1
	* This if for Coupons made prior to 1.80
	* @version 2.1
	*/
	public static function cctor_update_ignore_expiration() {

		update_option( 'coupon_update_ignore_expiration', date( 'l jS \of F Y h:i:s A' ) );

		$args = array(
			'posts_per_page' => 500,
			'post_type'      => 'cctor_coupon',
			'post_status'    => 'publish',
			'meta_key'       => 'cctor_ignore_expiration',
			'meta_value'     => 'on'
		);

		$cctor_ignore_exp = new WP_Query( $args );

		if ( $cctor_ignore_exp ) {
			while ( $cctor_ignore_exp->have_posts() ) : $cctor_ignore_exp->the_post();

				update_post_meta( $cctor_ignore_exp->post->ID, 'cctor_ignore_expiration', 1 , 'on' );

			endwhile;
		}

		wp_reset_postdata();

	}

	/***************************************************************************/
	/*
	* Flush Permalink on Coupon Option Change
	* @version 1.80
	*/
	public static function cctor_flush_permalinks() {
		if ( get_option('cctor_coupon_base_change') == true ) {

			Coupon_Creator_Plugin::cctor_register_post_types();
			flush_rewrite_rules();
			update_option( 'coupon_flush_perm_change', date('l jS \of F Y h:i:s A') );
			update_option( 'cctor_coupon_base_change', false );
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
				$plugin_links[] = '<a href="http://cctor.us/procoupon">Upgrade to Pro!</a>';
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
				//Date Picker CSS
				$cctor_meta_css = CCTOR_PATH.'admin/css/cctor-meta.css';
				wp_enqueue_style( 'cctor_meta_css', CCTOR_URL . 'admin/css/cctor-meta.css', false, filemtime($cctor_meta_css));

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
		* Remove Coupon Row Actions if user does not have permision to manage
		* @version 1.90
		* @param array $actions, $post
		*/
		public static function cctor_remove_coupon_row_actions( $actions, $post ) {
		  global $current_screen, $current_user;

			if( $current_screen->post_type != 'cctor_coupon' ) return $actions;

			get_currentuserinfo();

			if(!current_user_can( 'edit_others_cctor_coupons', $post->ID ) && ($post->post_author != $current_user->ID))  {
				unset( $actions['edit'] );
				unset( $actions['view'] );
				unset( $actions['trash'] );
				unset( $actions['inline hide-if-no-js'] );
			}

			return $actions;
		}


		/***************************************************************************/

		/*
		* Setup Custom Columns
		* @version 2.0
		* @param array $columns
		*/
		public static function cctor_list_columns( $columns ) {
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

			$cctor_columns['cctor_coupon_showing'] = __( 'Coupon is ', 'coupon_creator' );

			$cctor_columns['cctor_coupon_shortcode'] = __( 'Shortcode', 'coupon_creator' );

			$cctor_columns['cctor_coupon_ignore_expiration'] = __( 'Ignore Expiration', 'coupon_creator' );

			$cctor_columns['cctor_coupon_expiration'] = __( 'Expiration Date', 'coupon_creator' );


			if( isset( $columns['date'] ) ) {
				$cctor_columns['date'] = $columns['date'];
			}

			//Filter Columns
			if(has_filter('cctor_filter_coupon_list_columns')) {
				$cctor_columns = apply_filters('cctor_filter_coupon_list_columns', $cctor_columns,  $columns);
			}

			return $cctor_columns;
		}

		/*
		* Add Custom Meta Data to Columns
		* @version 2.0
		*/
		public static function cctor_column_cases( $column, $post_id ) {
			switch( $column ) {
				case 'cctor_coupon_shortcode':
					echo "<code>[coupon couponid='". $post_id ."' name='". get_the_title($post_id) ."']</code>";
					break;
				case 'cctor_coupon_showing':

					$coupon_showing = Coupon_Creator_Plugin_Admin::cctor_admin_check_expiration($post_id);

					if ( $coupon_showing ) {
						echo "<p style='color: #048c7f; padding-left:5px;'>" . __( 'Showing', 'coupon_creator' ) . "</p>";
					} else {
						echo "<p style='color: #dd3d36; padding-left:5px;'>" . __( 'Not Showing', 'coupon_creator' ) . "</p>";
					}

					break;
				case 'cctor_coupon_expiration':
					//Coupon Expiration Date
					$expirationco = get_post_meta($post_id, 'cctor_expiration', true);

					$cc_expiration_date = strtotime($expirationco);

					if ($expirationco) { // Only Display Expiration if Date
						$daymonth_date_format = cctor_options('cctor_default_date_format'); //Date Format

						if ($daymonth_date_format == 1 ) { //Change to Day - Month Style
						$expirationco = date("d/m/Y", $cc_expiration_date);
						}

						echo $expirationco;
					}

					break;
				case 'cctor_coupon_ignore_expiration':
					if (get_post_meta( $post_id, 'cctor_ignore_expiration', true ) == 1) {
						echo "<p style='padding-left:40px;'>" . __( 'Yes', 'coupon_creator' ) . "</p>";
					}
					break;
			}

			if(has_filter('cctor_filter_column_cases')) {
				echo apply_filters('cctor_filter_column_cases', $column, $post_id);
			}
		}

		/***************************************************************************/

		/*
		* Admin Check if Coupon Shows on Front End
		* @version 2.0
		*/
		public static function cctor_admin_check_expiration($coupon_id) {

				//Ignore Expiration Value
				$ignore_expiration = get_post_meta($coupon_id, 'cctor_ignore_expiration', true);

				//Return If Not Passed Expiration Date
				$expiration = Coupon_Creator_Plugin_Admin::cctor_admin_expiration_and_current_date($coupon_id);

				//Enable Filter to stop coupon from showing
				$show_coupon_check = false;

				$show_coupon_check = apply_filters('cctor_admin_check_coupon', $show_coupon_check, $coupon_id);

				if (($expiration || $ignore_expiration == 1) && !$show_coupon_check) {

					return true;

				}	else {

					return false;

				}
		}

		/***************************************************************************/


		/*
		* Admin Check of Expiration Date
		* @version 2.0
		*/
		public static function cctor_admin_expiration_and_current_date($coupon_id) {

			//Coupon Expiration Date
			$expirationco = get_post_meta($coupon_id, 'cctor_expiration', true);
			$expiration['expiration'] = strtotime($expirationco);

			//Blog Time According to WordPress
			$cc_blogtime = current_time('mysql');
			list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = preg_split( '([^0-9])', $cc_blogtime );
			$expiration['today'] = strtotime($today_month."/".$today_day."/". $today_year);

			if ($expiration['expiration'] >= $expiration['today']) {
				return true;
			} else {
				return false;
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
				if( ! check_admin_referer( 'cctor_license_nonce', 'cctor_license_nonce' ) )
					return; // get out if we didn't click the Activate button


				$cctor_license_info = array();

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
				if ( is_wp_error( $response ) )
					return false;

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
				if ($license_data->error == "expired") {
					$cctor_license_info['expired'] = esc_html($license_data->error);
				}

				//if Expired Add that to the option.
				if ($license_data->error == "missing") {
					unset($cctor_license_info['expires']);
					unset($cctor_license_info['expired']);
					$cctor_license_info['status']  = esc_html($license_data->error);
				}

				//Update License Object
				update_option( $license_option_name, $cctor_license_info );

			}
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
				if( ! check_admin_referer( 'cctor_license_nonce', 'cctor_license_nonce' ) )
					return; // get out if we didn't click the Activate button

				$license_option_name = esc_attr($_POST['cctor_license_key']);

				// retrieve the license from the database
				$cctor_license_info = get_option( $license_option_name );

				// data to send in our API request
				$api_params = array(
					'edd_action'=> 'deactivate_license',
					'license' 	=> esc_attr(trim($cctor_license_info['key'])),
					'item_name' => urlencode( esc_attr($_POST['cctor_license_name']) ), // the name of our product in EDD
					'url'       => home_url()
				);

				// Call the custom API.
				$response = wp_remote_get( esc_url_raw( add_query_arg( $api_params, COUPON_CREATOR_STORE_URL ) ), array( 'timeout' => 15, 'sslverify' => false ) );

				// make sure the response came back okay
				if ( is_wp_error( $response ) )
					return false;

				// decode the license data
				$license_data = json_decode( wp_remote_retrieve_body( $response ) );

				// $license_data->license will be either "deactivated" or "failed"
				if( $license_data->license == 'deactivated' || $license_data->license == 'failed' ) {

					unset($cctor_license_info['status']);
					unset($cctor_license_info['expires']);

					//Update License Object
					update_option( $license_option_name, $cctor_license_info );
				}

			}
		}

		/***************************************************************************/

		/*
		* Get Support Information for Options and Meta Field
		* @version 2.0
		*/
		public static function get_cctor_support_core_infomation() {

			$support_html = '

				<h4 class="coupon-heading">Video Guides</h4>
				<ul>
					<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/tIau3ZNjoeI?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Creating a Coupon</a></li>
					<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/A1mULc_MyHs?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Creating an Image Coupon</a></li>
					<li><a  class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/sozW-J-g3Ts?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Inserter and Aligning Coupons</a></li>
					<li><a class="cctor-support youtube_colorbox" href="http://www.youtube.com/embed/h3Zg8rxIDdc?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Using the Coupon Creator Options</a></li>
				</ul>

				<h4 class="coupon-heading">Pro Video Guides</h4>
				<ul>
                    <li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/xH3GmKPzQKc?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">How to Create a WooCommerce Coupon</a></li>
                    <li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/iThKkEgYBDE?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">How to use the Popup Print View Feature</a></li>
                    <li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/h0YVXi2vq3g?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">How to use the View Shortcodes and Deal Display Options</a></li>
					<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/FI218DxXnrY?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Creating a Pro Coupon</a></li>
					<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/SqAG3s1FniA?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Creating a Pro Image Coupon</a></li>
					<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/8L0JmSB_V-E?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Using the Pro Options</a></li>
					<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/aVkwq8cIgB0?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Using the Pro Counter</a></li>
					<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/vmViVkoQB0M?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Using the Pro Background Image</a></li>
					<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/b3cV8gVf4lU?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Using the Pro Dimension Options</a></li>
					<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/pFnp5VsfwUE?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Using the Pro Text Overrides</a></li>
				</ul>

				<h4 class="coupon-heading">Resources</h4>
				<ul>
					<li><a class="cctor-support" target="_blank" href="http://cctor.us/1TaXXoc">Documentation</a> - Overview of CSS Selectors, Actions, Filters, Capabilities, and Post Types</li>
					<li><a class="cctor-support" target="_blank" href="http://cctor.us/1KV92nK">Frequently Asked Question</a> - Pre Sales, License, Requirements, and Setup Information</li>
					<li><a class="cctor-support" target="_blank" href="http://cctor.us/cg12Qf5">Guides</a> - User Guides and Troubleshooting Guides</li>
					<li><a class="cctor-support" target="_blank" href="http://cctor.us/1TaY1EH">Tutorials</a> - Customization Tutorials and More</li>
				</ul>';

			return	$support_html;
		}

		/***************************************************************************/

		/*
		* Get Support Information for Options and Meta Field
		* @version 2.0
		*/
		public static function get_cctor_support_core_contact() {

			$support_html = '
				<h4 class="coupon-heading">How to Contact Support</h4>
				<ul>
					<li>Please use the <a target="_blank" class="cctor-support" href="https://wordpress.org/support/plugin/coupon-creator/">WordPress.org Support Forum for the Coupon Creator</a>.</li>
					<li><br>Before contacting support please try to narrow or solve your issue by using one or all of these troubleshooting guides:
						<ul>
						<li><br><a class="cctor-support" target="_blank" href="http://cctor.us/1MnSYeM">Troubleshooting 404 Errors</a></li>
						<li><a class="cctor-support" target="_blank" href="http://cctor.us/1e2LhQH">Troubleshooting Conflicts</a></li>
						<li><a class="cctor-support" target="_blank" href="http://cctor.us/1B20bkq">Troubleshooting Javascript Errors</a></li>
						</ul>
					</li>

				</ul>';

			return	$support_html;
		}

		/***************************************************************************/

	} //end Coupon_Creator_Plugin_Admin Class

} // class_exists( 'Coupon_Creator_Plugin_Admin' )
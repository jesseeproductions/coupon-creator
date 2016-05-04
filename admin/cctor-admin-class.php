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

			self::cctor_update_expiration_option();

			//self::cctor_update_ignore_expiration();

			self::cctor_update_image_fields();

			update_option(CCTOR_VERSION_KEY, CCTOR_VERSION_NUM);

			update_option( 'cctor_coupon_base_change', TRUE );

		}
	}

	/***************************************************************************/
		/**
		 * Update Coupons with new Expiration Options in 2.3
		 */
		public static function cctor_update_expiration_option() {
			//Run this script once
			if ( get_option( 'coupon_update_expiration_type' ) ) {
				return;
			}
			$args = array(
				'posts_per_page' => 1000,
				'post_type'      => 'cctor_coupon',
				'post_status'    => 'publish',
			);

			$cctor_exp_option = new WP_Query( $args );

			if ( $cctor_exp_option ) {

				while ( $cctor_exp_option->have_posts() ) : $cctor_exp_option->the_post();

					//If there is an Expiration Option Skip this Coupon
					$cctor_expiration_option          = get_post_meta( $cctor_exp_option->post->ID, 'cctor_expiration_option', true );
					if ( $cctor_expiration_option ) {
						continue;
					}

					$cctor_ignore_expiration          = get_post_meta( $cctor_exp_option->post->ID, 'cctor_ignore_expiration', true );
					$cctor_expiration                 = get_post_meta( $cctor_exp_option->post->ID, 'cctor_expiration', true );
					$cctor_recurring_expiration_limit = get_post_meta( $cctor_exp_option->post->ID, 'cctor_recurring_expiration_limit', true );
					$cctor_recurring_expiration       = get_post_meta( $cctor_exp_option->post->ID, 'cctor_recurring_expiration', true );
					$cctor_x_days_expiration          = get_post_meta( $cctor_exp_option->post->ID, 'cctor_x_days_expiration', true );

					if ( 1 == $cctor_ignore_expiration ) {
						update_post_meta( $cctor_exp_option->post->ID, 'cctor_expiration_option', 1 );
					} elseif ( $cctor_expiration && ! $cctor_recurring_expiration_limit && ! $cctor_recurring_expiration ) {
						update_post_meta( $cctor_exp_option->post->ID, 'cctor_expiration_option', 2 );
					} elseif ( $cctor_expiration && $cctor_recurring_expiration_limit && $cctor_recurring_expiration ) {
						update_post_meta( $cctor_exp_option->post->ID, 'cctor_expiration_option', 3 );
					} elseif ( $cctor_x_days_expiration ) {
						update_post_meta( $cctor_exp_option->post->ID, 'cctor_expiration_option', 4 );
					}

				endwhile;
			}


			wp_reset_postdata();

			update_option( 'coupon_update_expiration_type', date( 'l jS \of F Y h:i:s A' ) );

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
			'posts_per_page' => 1000,
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
	* On Update Query Coupons and update cctor_outer_radius to cctor_img_outer_radius value and delete
	* This if for Image Coupons made prior to 2.1
	* @version 2.1
	*/
	public static function cctor_update_image_fields() {

		update_option( 'coupon_update_image_border_meta', date( 'l jS \of F Y h:i:s A' ) );

		$args = array(
			'posts_per_page' => 1000,
			'post_type'      => 'cctor_coupon',
			'post_status'    => 'publish',
			'meta_key'       => 'cctor_img_outer_radius'
		);

		$cctor_ignore_exp = new WP_Query( $args );

		if ( $cctor_ignore_exp ) {
			while ( $cctor_ignore_exp->have_posts() ) : $cctor_ignore_exp->the_post();

				update_post_meta( $cctor_ignore_exp->post->ID, 'cctor_outer_radius', get_post_meta( $cctor_ignore_exp->post->ID, 'cctor_img_outer_radius', true) );

				delete_post_meta( $cctor_ignore_exp->post->ID, 'cctor_img_outer_radius' );

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

			if ( 'edit-cctor_coupon' == $screen->id ) {
				//Styles
				$cctor_meta_css = CCTOR_PATH.'admin/css/cctor-meta.css';
				wp_enqueue_style( 'cctor_meta_css', CCTOR_URL . 'admin/css/cctor-meta.css', false, filemtime($cctor_meta_css));

			}
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
		* Remove Coupon Row Actions if user does not have permision to manage
		* @version 1.90
		* @param array $actions, $post
		*/
		public static function cctor_remove_coupon_row_actions( $actions, $post ) {
		  global $current_screen, $current_user;

			if( is_object( $current_screen ) && $current_screen->post_type != 'cctor_coupon' ) {
				return $actions;
			}

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
				$cctor_columns['title'] = __( 'Coupon Title', 'coupon-creator' );
			}

			if( isset( $columns['author'] ) ) {
				$cctor_columns['author'] = $columns['author'];
			}

			$cctor_columns['cctor_showing'] = __( 'Coupon is ', 'coupon-creator' );

			$cctor_columns['cctor_shortcode'] = __( 'Shortcode', 'coupon-creator' );

			$cctor_columns['cctor_ignore_expiration'] = __( 'Ignore Expiration', 'coupon-creator' );

			$cctor_columns['cctor_expiration_date'] = __( 'Expiration Date', 'coupon-creator' );


			if( isset( $columns['date'] ) ) {
				$cctor_columns['date'] = $columns['date'];
			}

			//Filter Columns
			if(has_filter('cctor_filter_coupon_list_columns')) {

				/**
				 * Filter the Admin Coupon List Columns Headers
				 *
				 * @param array $cctor_columns an array of column headers.
				 *
				 */
				$cctor_columns = apply_filters('cctor_filter_coupon_list_columns', $cctor_columns,  $columns);
			}

			return $cctor_columns;
		}

		/**
		 * Add Custom Meta Data to Columns
		 * @since 2.0
		 *
		 * @param $column
		 * @param $post_id
		 */
		public static function cctor_column_cases( $column, $post_id ) {

			if ( class_exists( 'CCtor_Pro_Expiration_Class' ) ) {
				$coupon_expiration = new CCtor_Pro_Expiration_Class();
			} else {
				$coupon_expiration = new CCtor_Expiration_Class();
			}

			switch ( $column ) {
				case 'cctor_showing':

					echo $coupon_expiration->get_admin_list_coupon_showing();

					break;
				case 'cctor_shortcode':

					echo "<code>[coupon couponid='" . $post_id . "' name='" . get_the_title( $post_id ) . "']</code>";

					break;
				case 'cctor_expiration_date':

					echo $coupon_expiration->get_display_expiration();

					break;
				case 'cctor_ignore_expiration':

					if ( 1 == $coupon_expiration->get_expiration_option() ) {
						echo "<p style='padding-left:40px;'>" . __( 'Yes', 'coupon-creator' ) . "</p>";
					}
					break;
			}

			if ( has_filter( 'cctor_filter_column_cases' ) ) {

				/**
				 * Filter the Admin Coupon List Columns Information per Coupon
				 *
				 * @since 1.80
				 *
				 * @param string $column            a string of data to display in the admin columns.
				 * @param int    $post_id           an integer of the coupon post
				 * @param object $coupon_expiration the expiration object.
				 *
				 */
				apply_filters( 'cctor_filter_column_cases', $column, $post_id, $coupon_expiration );
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
					<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/L9uf9q9JRtc?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Pro\'s couponloop shortcode, filter bar, and template system to manage coupons</a></li>
					<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/xEOdVUMFqg8?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Intro to Pro\'s Themer\'s Guide</a></li>
					<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/EQRv8g2nmuE?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">How to use the Border Styles</a></li>
					<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/JR4GA4lsOB0?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">How to use Recurring Expiration</a></li>
					<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/w67yqCZXF6I?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Using Columns and Rows in the Visual Editor</a></li>
                    <li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/iThKkEgYBDE?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">How to use the Popup Print View Feature</a></li>
                    <li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/h0YVXi2vq3g?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">How to use the View Shortcodes and Deal Display Options</a></li>
					<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/FI218DxXnrY?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Creating a Pro Coupon</a></li>
					<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/SqAG3s1FniA?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Creating a Pro Image Coupon</a></li>
					<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/8L0JmSB_V-E?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Using the Pro Options</a></li>
					<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/aVkwq8cIgB0?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Using the Pro Counter</a></li>
					<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/vmViVkoQB0M?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Using the Pro Background Image</a></li>
					<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/b3cV8gVf4lU?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Using the Pro Dimension Options</a></li>
					<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/pFnp5VsfwUE?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Using the Pro Text Overrides</a></li>
					 <li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/xH3GmKPzQKc?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">How to Create a WooCommerce Coupon</a></li>
				</ul>

				<h4 class="coupon-heading">Resources</h4>
				<ul>
					<li><a class="cctor-support" target="_blank" href="http://cctor.link/EsQPX">Documentation</a> - Overview of CSS Selectors, Actions, Filters, Capabilities, and Post Types</li>
					<li><a class="cctor-support" target="_blank" href="http://cctor.link/UzIZB">Frequently Asked Question</a> - Pre Sales, License, Requirements, and Setup Information</li>
					<li><a class="cctor-support" target="_blank" href="http://cctor.link/eQAEC">Guides</a> - User Guides and Troubleshooting Guides</li>
					<li><a class="cctor-support" target="_blank" href="http://cctor.link/loHtW">Tutorials</a> - Customization Tutorials and More</li>
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
					<li>Please use the <a target="_blank" class="cctor-support" href="http://cctor.link/ZlQvh">WordPress.org Support Forum for the Coupon Creator</a>.</li>
					<li><br>Before contacting support please try to narrow or solve your issue by using one or all of these troubleshooting guides:
						<ul>
						<li><br><a class="cctor-support" target="_blank" href="http://cctor.link/RgewD">Troubleshooting 404 Errors</a></li>
						<li><a class="cctor-support" target="_blank" href="http://cctor.link/4rMqT">Troubleshooting Conflicts</a></li>
						<li><a class="cctor-support" target="_blank" href="http://cctor.link/R7KRa">Troubleshooting Javascript Errors</a></li>
						</ul>
					</li>

				</ul>';

			return	$support_html;
		}

		/***************************************************************************/

	} //end Coupon_Creator_Plugin_Admin Class

} // class_exists( 'Coupon_Creator_Plugin_Admin' )
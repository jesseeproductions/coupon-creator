<?php
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

if ( ! class_exists( 'Coupon_Creator_Plugin_Admin_Options' ) ) {
	/*
	* Coupon_Creator_Plugin_Admin_Options
	* since 1.80
	*/
	class Coupon_Creator_Plugin_Admin_Options {
		/*
		* Tab Sections
		* since 1.80
		*/
		private $sections;
		/* Checkbox Fields
		* Construct
		* since 1.80
		*/
		private $checkboxes;
		/*
		* Option Fields
		* since 1.80
		*/
		public $options;

		/*
		* Construct
		* since 1.80
		*/
		public function __construct() {

			//Track Checkbox Options for validate_options()
			$this->checkboxes = array();
			$this->options = array();
			$this->get_options();

			add_action( 'admin_menu', array( &$this, 'coupon_options_page' ) );
			add_action( 'admin_init', array( &$this, 'register_options' ) );

			//Add Coupon Newsletter Sign Up
			add_action( 'cctor_after_option_form', array( __CLASS__, 'cctor_newsletter_signup' ) );

			//Set Standard Options if None Found
			if ( ! get_option( 'coupon_creator_options' ) )
				$this->initialize_options();
		}
	/***************************************************************************/

		/*
		* Coupon Creator Option Sections
		* since 1.80
		*/
		public function get_sections() {

			//Coupon Creator Options Tabs
			$this->sections['defaults']     = __( 'Defaults', 'coupon_creator' );
			$this->sections['permalinks']   = __( 'Link Attributes / Permalinks', 'coupon_creator' );
			$this->sections['display'] 		= __( 'Display', 'coupon_creator' );
			$this->sections['help']        = __( 'Help', 'coupon_creator' );
			$this->sections['license']   	= __( 'Licenses', 'coupon_creator' );
			$this->sections['pro']        = __( 'Pro', 'coupon_creator' );
			$this->sections['reset']        = __( 'Reset', 'coupon_creator' );

			unset($this->sections['license']);

			//Filter Option Tabs
			if(has_filter('cctor_option_sections')) {
				$this->sections = apply_filters('cctor_option_sections', $this->sections);
			}

		}

	/***************************************************************************/

		/*
		* Coupon Creator Admin Options Page
		* since 1.80
		*/
		public function coupon_options_page() {
			$admin_page = add_submenu_page(
				'edit.php?post_type=cctor_coupon', // parent_slug
				__( 'Coupon Creator Options', 'coupon_creator' ), // page_title
				__( 'Options', 'coupon_creator' ), // menu_title
				'manage_options', // capability
				'coupon-options', // menu_slug
				array( &$this, 'display_page' ) // function
			);

			add_action( 'admin_print_scripts-' . $admin_page, array(&$this, 'coupon_option_scripts' ) );
			add_action( 'admin_print_styles-' . $admin_page, array( &$this, 'coupon_option_styles' ) );

		}
		/*
		* Coupon Creator Options Page Scripts
		* since 1.80
		*/
		public function coupon_option_scripts() {
			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-tabs');

			//Script for WP Color Picker
			wp_enqueue_script( 'wp-color-picker' );
			$cctor_coupon_option_js = CCTOR_PATH.'admin/js/cctor_coupon_options.js';
			wp_enqueue_script('cctor_coupon_option_js',  CCTOR_URL . 'admin/js/cctor_coupon_options.js', array('jquery','thickbox','farbtastic'), filemtime($cctor_coupon_option_js), true);

			$cctor_colorbox_js = CCTOR_PATH.'admin/colorbox/jquery.colorbox-min.js';
			wp_enqueue_script('cctor_colorbox_js',  CCTOR_URL . '/admin/colorbox/jquery.colorbox-min.js' ,array('jquery'), filemtime($cctor_colorbox_js), true);

			//Hook to Load New Scripts
			do_action('cctor_opitons_scripts');
		}

		/*
		* Coupon Creator Options Page Styles
		* since 1.80
		*/
		public function coupon_option_styles() {

			$cctor_options_css = CCTOR_PATH.'admin/css/cctor-options.css';
			wp_enqueue_style( 'cctor_options_css', CCTOR_URL . 'admin/css/cctor-options.css', false, filemtime($cctor_options_css));

			//Style or WP Color Picker
			wp_enqueue_style( 'wp-color-picker' );

			//Color Box For How to Videos
			$cctor_colorbox_css = CCTOR_PATH.'admin/colorbox/colorbox.css';
			wp_enqueue_style('cctor_colorbox_css', CCTOR_URL . '/admin/colorbox/colorbox.css', false, filemtime($cctor_colorbox_css));

			//Hook to Load New Styles
			do_action('cctor_opitons_styles');

		}
	/***************************************************************************/
		/*
		* Coupon Creator Options
		* since 1.80
		*/
		public function create_option( $args = array() ) {

			$defaults = array(
				'id'      => 'default_id',
				'title'   => __( 'Default' ),
				'desc'    => __( 'This is a default description.' ),
				'alert'   => '',
				'condition'   => '',
				'std'     => '',
				'type'    => 'text',
				'section' => 'general',
				'choices' => array(),
				'class'   => '',
				'imagemsg'   => '',
				'size'	=> 35
			);

			extract( wp_parse_args( $args, $defaults ) );

			$field_args = array(
				'type'      => $type,
				'id'        => $id,
				'desc'      => $desc,
				'alert'		=> $alert,
				'condition' => $condition,
				'std'       => $std,
				'choices'   => $choices,
				'label_for' => $id,
				'class'     => $class,
				'imagemsg'	=> $imagemsg,
				'size'		=> $size
			);

			if ( $type == 'checkbox' )
				$this->checkboxes[] = $id;

			add_settings_field( $id, $title, array( $this, 'display_setting' ), 'coupon-options', $section, $field_args );
		}

	/***************************************************************************/

		/*
		* Coupon Creator Admin Settings Options Page
		* since 1.80
		*/
		public function display_page() {

			//Get Tab Sections to Show
			$this->get_sections();

			//Create Array of Tabs and Localize to Meta Script
			$tabs_array = array();

			foreach ( $this->sections as $section_slug => $section ) {
				$tabs_array[$section] = $section_slug;
			}

			$tabs_json_array = json_encode($tabs_array);

			$tabs_params = array(
				'tabs_arr' => $tabs_json_array,
			);

			wp_localize_script('cctor_coupon_option_js', 'cctor_coupon_option_js_vars', $tabs_params);

			echo '<div class="wrap">
				<div class="icon32" id="icon-options-general"></div>
				<h2><img src="'. CCTOR_URL . 'admin/images/coupon_creator.png"/>  ' . __( 'Coupon Creator Options' ) . '</h2>
				<h4>Coupon Creator: '. get_option(CCTOR_VERSION_KEY).'</h4>';

				do_action( 'cctor_before_option_form' );

					if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true )
						echo '<div class="updated fade"><p>' . __( 'Coupon Creator Options updated.' ) . '</p></div>';

					echo '<form action="options.php" method="post">';

					settings_fields( 'coupon_creator_options' );
					echo '<div class="cctor-tabs">
						<ul class="cctor-tabs-nav">';

					foreach ( $this->sections as $section_slug => $section )
						echo '<li><a href="#' . $section_slug . '">' . $section . '</a></li>';

					echo '</ul>';
					do_settings_sections( $_GET['page'] );

					echo '</div>
					<p class="submit"><input name="Submit" type="submit" class="button-primary" value="' . __( 'Save Changes' ) . '" /></p>

				</form>';

					do_action( 'cctor_after_option_form' );

				echo '<p style="text-align:right;">&copy; '.date("Y").' Jessee Productions, LLC</p>

			</div>';
		}

	/***************************************************************************/

		/*
		* Display Section
		* since 1.80
		*/
		public function display_section() {


		}

		/*
		* Coupon Creator Pro Section
		* since 1.80
		*/
		public function display_pro_section() {
			ob_start(); ?>
			<div>
				<h4><img alt="Get Coupon Creator Pro!" src="<?php echo CCTOR_URL; ?>admin/images/cctor-logo.png"/></h4>
				<br>
				<p><strong style="font-size:15px;"><a href="http://couponcreatorplugin.com">Purchase Pro</a> and get all the features below with 1 year of updates and direct support.</strong></p>
				<br>
				<ul>
				<h4>Coupon Creator Pro Features Include:</h4><br>
					<li>Set a Counter per coupon to expire the coupon after a limit has been reached:
					<img class="cctor-pro-img" alt="Coupon Creator Pro Counter" src="<?php echo CCTOR_URL; ?>admin/images/cctor-pro-counter.png"/>
					</li>
					<li>Change "Expires on:", "Click to Open in Print View", and "Print the Coupon" for all Coupons:
					<img class="cctor-pro-img" alt="Coupon Creator Pro Change Text" src="<?php echo CCTOR_URL; ?>admin/images/cctor-pro-text-overrides.png"/>
					</li>
					<li>Set Coupon Size for both views of the coupon for regular coupons and the image coupon as well:
					<img class="cctor-pro-img" alt="Coupon Creator Pro Change Text" src="<?php echo CCTOR_URL; ?>admin/images/cctor-pro-dimensions.png"/>
					</li>
					<li>Override "Click to Open in Print View" text and link per coupon</li>
					<li>Override "Print the Coupon" text and link per coupon</li>
				</ul>
				<ul>
				<h4>Coupon Creator Pro Style Features:</h4><br>
					<li>Set all the styles for the coupons as defaults to get the same custom look for all your coupons with less work</li>
					<li>Set Inside Border Radius </li>
					<li>Select Coupon Outside Border Color</li>
					<li>Set Outer Border Radius, works for the image coupon too</li>
					<li>Select Coupon Terms Text Color</li>
					<li>Select Coupon Background Color</li>
					<li>Choose a Background Image with option to set Background Repeat, Background Position, and Background Size
					<img class="cctor-pro-img" alt="Coupon Creator Pro Change Text" src="<?php echo CCTOR_URL; ?>admin/images/cctor-pro-styles.png"/>
					</li>
					<li>Direct Support through CouponCreatorPlugin.com</li>
				</ul>
				<br>
				<strong style="font-size:15px;"><a href="http://couponcreatorplugin.com">Purchase Pro Now!</a></strong>
			</div>
			<?php echo ob_get_clean();
		}

		/*
		* Coupon Creator Display Options
		* since 1.80
		*/
		public function display_setting( $args = array() ) {

			extract( $args );

			$options = get_option( 'coupon_creator_options' );

			if ( ! isset( $options[$id] ) && $type != 'checkbox' )
				$options[$id] = $std;
			elseif ( ! isset( $options[$id] ) )
				$options[$id] = 0;

			$field_class = '';
			if ( $class != '' )
				$field_class = ' ' . $class;

			switch ( $type ) {

				case 'heading':
					if ( $alert ) {
						echo '</td></tr><tr valign="top"><td colspan="2"><span class="description">' . $alert . '</span>';
					} else {
						echo '</td></tr><tr valign="top"><td colspan="2"><h4>' . $desc . '</h4>';
					}
					break;

				case 'text':
					if ( $alert != '' && cctor_options($condition) == 1 )
						echo '<div class="alert">' . $alert . '</div>';

					echo '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="coupon_creator_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '" size="' . $size . '" />';

					if ( $desc != '' )
						echo '<br /><span class="description">' . $desc . '</span>';

					break;

				case 'checkbox':

					echo '<input class="checkbox' . $field_class . '" type="checkbox" id="' . $id . '" name="coupon_creator_options[' . $id . ']" value="1" ' . checked( $options[$id], 1, false ) . ' /> <label for="' . $id . '">' . $desc . '</label>';

					break;

				// color
				case 'color':

				$default_color = '';
				if ( isset($std) ) {
					if ( $options[$id] !=  $std )
						$default_color = ' data-default-color="' .$std . '" ';
				}

					echo '<input class="color-picker ' . $field_class . '" type="text" id="' . $id . '" name="coupon_creator_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $options[$id] ) . '"' . $default_color .' /><br /><span class="description">' . $desc . '</span>';

					break;

				case 'select':
					echo '<select class="select' . $field_class . '" name="coupon_creator_options[' . $id . ']">';

					foreach ( $choices as $value => $label )
						echo '<option value="' . esc_attr( $value ) . '"' . selected( $options[$id], $value, false ) . '>' . $label . '</option>';

					echo '</select>';

					if ( $desc != '' )
						echo '<br /><span class="description">' . $desc . '</span>';

					break;

				case 'radio':
					$i = 0;
					foreach ( $choices as $value => $label ) {
						echo '<input class="radio' . $field_class . '" type="radio" name="coupon_creator_options[' . $id . ']" id="' . $id . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[$id], $value, false ) . '> <label for="' . $id . $i . '">' . $label . '</label>';
						if ( $i < count( $options ) - 1 )
							echo '<br />';
						$i++;
					}

					if ( $desc != '' )
						echo '<br /><span class="description">' . $desc . '</span>';

					break;

				case 'textarea':
					echo '<textarea class="' . $field_class . '" id="' . $id . '" name="coupon_creator_options[' . $id . ']" placeholder="' . $std . '" rows="12" cols="50">' . wp_htmledit_pre( $options[$id] ) . '</textarea>';

					if ( $desc != '' )
						echo '<br /><span class="description">' . $desc . '</span><br />';
					break;

				case 'cctor_support':

					echo '
					<h4>Video Guides</h4>
					<ul>
						<li><a class="cctor-support" href="#" class="youtube_colorbox" rel="how_to_videos">Creating a Coupon</a></li>
						<li><a class="cctor-support" href="#" class="youtube_colorbox" rel="how_to_videos">Creating an Image Coupon</a></li>
						<li><a  class="cctor-support"href="#" class="youtube_colorbox" rel="how_to_videos">Inserter and Aligning Coupons</a></li>
						<li><a class="cctor-support" href="#" class="youtube_colorbox" rel="how_to_videos">Using the Coupon Creator Options</a></li>
					</ul>

					<h4>Pro Video Guides</h4>
					<ul>
						<li><a class="cctor-support" href="#" class="youtube_colorbox" rel="how_to_videos">How to setup your License</a></li>
						<li><a class="cctor-support" href="#" class="youtube_colorbox" rel="how_to_videos">Creating a Pro Coupon</a></li>
						<li><a class="cctor-support" href="#" class="youtube_colorbox" rel="how_to_videos">Creating a Pro Image Coupon</a></li>								
						<li><a class="cctor-support" href="#" class="youtube_colorbox" rel="how_to_videos">Using the Pro Counter</a></li>
						<li><a class="cctor-support" href="#" class="youtube_colorbox" rel="how_to_videos">Using the Pro Background Image</a></li>
						<li><a class="cctor-support" href="#" class="youtube_colorbox" rel="how_to_videos">Using the Pro Dimension Options</a></li>
						<li><a class="cctor-support" href="#" class="youtube_colorbox" rel="how_to_videos">Using the Pro Text Overrides</a></li>
					</ul>
					
					<h4>Resources</h4>
					<ul>
						<li><a class="cctor-support" target="_blank" href="http://couponcreatorplugin.com/support/documentation/">Documentation</a> - Overview of CSS Selectors, Actions, Filters, Capabilities, and Post Types</li>
						<li><a class="cctor-support" target="_blank" href="http://couponcreatorplugin.com/support/frequently-asked-question/">Frequently Asked Question</a> - Pre Sales, License, Requirements, and Setup Information</li>
						<li><a class="cctor-support" target="_blank" href="http://couponcreatorplugin.com/support/guides/">Guides</a> - User Guides and Troubleshooting Guides</li>
						<li><a class="cctor-support" target="_blank" href="http://couponcreatorplugin.com/support/tutorials/">Tutorials</a> - Customization Tutorials and More</li>
					</ul>

					<h4>How to Contact Support</h4>
					<ul>
						<li>Please use the <a class="cctor-support" href="https://wordpress.org/support/plugin/coupon-creator/">WordPress.org Support Forum for the Coupon Creator</a>.</li>
						<li><br>Before contacting support please try to narrow or solve your issue by using one or all of these troubleshooting guides:
							<ul>
							<li><br><a class="cctor-support" target="_blank" href="http://couponcreatorplugin.com/knowledgebase/troubleshooting-404-errors/">Troubleshooting 404 Errors</a></li>
							<li><a class="cctor-support" target="_blank" href="http://couponcreatorplugin.com/knowledgebase/troubleshooting-conflicts/">Troubleshooting Conflicts</a></li>
							<li><a class="cctor-support" target="_blank" href="http://couponcreatorplugin.com/knowledgebase/troubleshooting-javascript-errors/">Troubleshooting Javascript Errors</a></li>
							</ul>
						</li>

					</ul>';

				break;

				case 'license':

					$cctor_license_info = array();
					$cctor_license = "cctor_" . $class;
					$cctor_license_read = "";

					$cctor_license_info	= get_option( $cctor_license );

					//If License is Valid then read only the field
					if( $cctor_license_info['status'] !== false && $cctor_license_info['status'] == 'valid' ) {
						$cctor_license_read = "readonly";
					}

					echo '<input class="regular-text' . $field_class . '" type="text" id="' . $id . '" name="coupon_creator_options[' . $id . ']" placeholder="' . $std . '" value="' . esc_attr( $cctor_license_info['key'] ) . '" size="' . $size . '" ' . $cctor_license_read . ' />';

					if ( $desc != '' )
						echo '<br /><span class="description">' . $desc . '</span>';
					break;

				case 'license_status':

					$cctor_license_info = array();
					$cctor_license = "cctor_" . $class;

					$cctor_license_info	= get_option( $cctor_license );

					//Coupon Expiration Date
					$expirationco =  $cctor_license_info['expires'];

					$cc_expiration_date = strtotime($expirationco);

					if ($expirationco) { // Only Display Expiration if Date
						$daymonth_date_format = cctor_options('cctor_default_date_format'); //Date Format

						if ($daymonth_date_format == 1 ) { //Change to Day - Month Style
							$expirationco = date("d/m/Y", $cc_expiration_date);
						} else {
							$expirationco = date("m/d/Y", $cc_expiration_date);
						}

						$expiration_date = sprintf(__(' and Expires on %s', 'coupon_creator' ), esc_attr($expirationco));
					}

					//if( $cctor_license_info['key'] != '' ) {

						if( $cctor_license_info['status'] !== false && $cctor_license_info['status'] == 'valid' ) {

							echo '<span style="color:green;">'. __( 'License is Active','coupon_creator' ). $expiration_date.'</span><br><br>';

								wp_nonce_field( 'cctor_license_nonce', 'cctor_license_nonce' );

							echo '<input type="hidden" class="cctor_license_key" name="cctor_license_key" value="cctor_'. $class .'"/>';
							echo '<input type="hidden" class="cctor_license_name" name="cctor_license_name" value="'. $condition .'"/>';
							echo '<input type="submit" class="cctor-license-button-act" name="cctor_license_deactivate" value="'. _('Deactivate License') .'"/>';

						 } else {
								$cctor_license_info_valid = "";
							if ($cctor_license_info['status'] == 'invalid' || $cctor_license_info['status'] == 'missing' && !$cctor_license_info['expired']) {
								$cctor_license_info_valid = __('License is Invalid', 'coupon_creator' );
							} elseif ($cctor_license_info['expired'] == "expired") {
								$cctor_license_info_valid =  sprintf(__('License Expired on %s', 'coupon_creator' ), esc_attr($expirationco));
							}
							else {
								$cctor_license_info_valid = __( 'License is Not Active','coupon_creator' );
							}

							echo '<span style="color:red;">'.$cctor_license_info_valid.'</span><br><br>';

								wp_nonce_field( 'cctor_license_nonce', 'cctor_license_nonce' );

							echo '<input type="hidden" class="cctor_license_key" name="cctor_license_key" value="cctor_'. $class .'"/>';
							echo '<input type="hidden" class="cctor_license_name" name="cctor_license_name" value="'. $condition .'"/>';
							echo '<input type="submit" class="cctor-license-button-det" name="cctor_license_activate" value="'. __('Activate License') .'"/>';

						 }
					//} else {
					//		echo __( 'Enter your license key and save changes, then Click Activate License.','coupon_creator' );
					//}
				break;
			}

			if(has_filter('cctor_option_cases')) {
				// this adds any addon fields (from plugins) to the array
				echo apply_filters('cctor_option_cases', $options, $type, $id, $desc, $alert, $condition, $field_class, $size, $std, $choices, $class , $imagemsg);
			}
		}
	/***************************************************************************/

		/*
		* Coupon Creator Options
		* since 1.80
		*/
		public function get_options() {

			//defaults
			$this->options['header_defaults'] = array(
				'section' => 'defaults',
				'title'   => '', // Not used for headings.
				'alert'    =>  __( '*These are defaults for new coupons only and do not change existing coupons.','coupon_creator' ),
				'type'    => 'heading'
			);
			$this->options['cctor_default_date_format'] = array(
				'section' => 'defaults',
				'title'   => __( 'Expiration Date Format', 'coupon_creator' ),
				'desc'    => __( 'Select the Date Format to show for all Coupons*', 'coupon_creator' ),
				'type'    => 'select',
				'std'     => 'month_first',
				'choices' => array(
					'0' =>  __( 'Month First - MM/DD/YYYY', 'coupon_creator' ),
					'1' => __( 'Day First - DD/MM/YYYY', 'coupon_creator' )
				)
			);

			//Discount Field Colors
			$this->options['header_discount'] = array(
				'section' => 'defaults',
				'title'   => '', // Not used for headings.
				'desc'    =>  __( 'Deal Field Colors','coupon_creator' ),
				'type'    => 'heading'
			);
			$this->options['cctor_discount_bg_color'] = array(
				'title' =>  __( 'Deal Background Color','coupon_creator' ),
				'desc'  =>  __( 'Choose default background color*','coupon_creator' ),
				'std'     => '#4377df',
				'type' => 'color', // color
				'section' => 'defaults'
			);
			$this->options['cctor_discount_text_color'] = array(
				'title' =>  __( 'Deal Text Color','coupon_creator' ),
				'desc'  =>  __( 'Choose default text color*','coupon_creator' ),
				'std'     => '#000000',
				'type' => 'color', // color
				'section' => 'defaults'
			);

			//Inner Border
			$this->options['header_inner_border'] = array(
				'section' => 'defaults',
				'title'   => '', // Not used for headings.
				'desc'    =>  __( 'Inner Border','coupon_creator' ),
				'type'    => 'heading'
			);
			$this->options['cctor_border_color'] = array(
				'title' =>  __( 'Inside Border Color','coupon_creator' ),
				'desc'  =>  __( 'Choose default inside border color*','coupon_creator' ),
				'std'     => '#81d742',
				'type' => 'color', // color
				'section' => 'defaults'
			);

			//LinkAttributes - Permalinks
			$this->options['no_follow_heading'] = array(
				'section' => 'permalinks',
				'title'   => '', // Not used for headings.
				'desc'    =>  __( 'Link Attribute Options','coupon_creator' ),
				'type'    => 'heading'
			);
			$this->options['cctor_nofollow_print_link'] = array(
				'section' => 'permalinks',
				'title'   => __( 'Click to Print Links', 'coupon_creator' ),
				'desc'    => __( 'Add nofollow to all the "Click to Print" links', 'coupon_creator' ),
				'type'    => 'checkbox',
				'std'     => 1 // Set to 1 to be checked by default, 0 to be unchecked by default.
			);
			$this->options['cctor_hide_print_link'] = array(
				'section' => 'permalinks',
				'title'   => __( 'Hide Click to Print Link', 'coupon_creator' ),
				'desc'    => __( 'This will hide the "Click to Print" links under the coupon' , 'coupon_creator'),
				'type'    => 'checkbox',
				'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
			);
			$this->options['cctor_nofollow_print_template'] = array(
				'section' => 'permalinks',
				'title'   => __( 'Print Template No Follow', 'coupon_creator' ),
				'desc'    => __( 'Add nofollow and noindex to the print template', 'coupon_creator' ),
				'type'    => 'checkbox',
				'std'     => 1 // Set to 1 to be checked by default, 0 to be unchecked by default.
			);
			$this->options['header_permalink'] = array(
				'section' => 'permalinks',
				'title'   => '', // Not used for headings.
				'desc'    =>  __( 'Permalink Options','coupon_creator' ),
				'type'    => 'heading'
			);
			$this->options['cctor_coupon_base'] = array(
				'title'   => __( 'Coupon Print Template Slug', 'coupon_creator' ),
				'desc'    => __( 'default: cctor_coupon', 'coupon_creator' ),
				'std'     => '',
				'type'    => 'text',
				'section' => 'permalinks',
				'class'   => 'permalink' //format text to lowercase before sanitizing
			);

			//Custom CSS
			$this->options['cctor_custom_css'] = array(
				'title'   => __( 'Custom Coupon Styles', 'coupon_creator' ),
				'desc'    => __( 'Enter any custom CSS here to apply to the coupons for the shortcode and the print template.(without &#60;style&#62; tags)', 'coupon_creator' ),
				'std'     => 'e.g. .cctor_coupon_container { width: 000px; }',
				'type'    => 'textarea',
				'section' => 'display',
				'class'   => 'code'
			);

			//Help
			$this->options['cctor_help'] = array(
				'section' => 'help',
				'title'   => __( 'Support: ', 'coupon_creator' ),
				'type'    => 'cctor_support',
				'std'     => 0,
				'desc'    => ''
			);

			$this->options['reset_heading'] = array(
				'section' => 'reset',
				'title'   => '', // Not used for headings.
				'desc'    =>  __( 'Coupon Creator Option Reset','coupon_creator' ),
				'type'    => 'heading'
			);

			//Reset
			$this->options['reset_theme'] = array(
				'section' => 'reset',
				'title'   => __( 'Reset', 'coupon_creator' ),
				'type'    => 'checkbox',
				'std'     => 0,
				'class'   => 'warning', // Custom class for CSS
				'desc'    => __( 'Check this box and click "Save Changes" below to reset all coupon creator options to their defaults. This does not change any existing coupon settings or remove your licenses.', 'coupon_creator' )
			);

			//Filter Option Fields
			if(has_filter('cctor_option_filter')) {
				$this->options = apply_filters('cctor_option_filter', $this->options);
			}

		}	// End get_options()

	/***************************************************************************/

		/*
		* Coupon Creator Display Newsletter Sign Up
		* since 1.90
		*/
		public static function cctor_newsletter_signup() {

							echo '<!-- Begin MailChimp Signup Form -->
					<div id="mc_embed_signup">
						<form action="//CouponCreatorPlugin.us9.list-manage.com/subscribe/post?u=f2b881e89d24e6f424aa25aa5&amp;id=2b82660ba0" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>

							<div id="mc_embed_signup_scroll">

							<h3>Sign Up for Coupon Creator Updates, Tips, and More</h3>
						<div class="mc-field-group">
							<input type="email" value="" placeholder="email address" name="EMAIL" class="required email" id="mce-EMAIL">
						</div>

							<div id="mce-responses">
								<div class="response" id="mce-error-response" style="display:none"></div>
								<div class="response" id="mce-success-response" style="display:none"></div>
							</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->

							<div style="position: absolute; left: -5000px;"><input type="text" name="b_f2b881e89d24e6f424aa25aa5_2b82660ba0" tabindex="-1" value=""></div>


							<input type="submit" value="Sign Me Up" name="subscribe" id="mc-embedded-subscribe" class="button">

							</div>
						</form>
					</div>

						<!--End mc_embed_signup-->';

		}

	/***************************************************************************/

		/*
		* Coupon Creator Initialize Options and Default Values
		* since 1.80
		*/
		public function initialize_options() {

			$default_options = array();

			foreach ( $this->options as $id => $option ) {
				if ( $option['type'] != 'heading' )
					$default_options[$id] = $option['std'];
			}

			update_option( 'coupon_creator_options', $default_options );

		}

		/*
		* Coupon Creator Register Options
		* since 1.80
		*/
		public function register_options() {

			//Get Tab Sections to Register
			$this->get_sections();

			register_setting( 'coupon_creator_options', 'coupon_creator_options', array ( &$this, 'validate_options' ) );

			foreach ( $this->sections as $slug => $title ) {
				if ( $slug == 'pro' )
					add_settings_section( $slug, $title, array( &$this, 'display_pro_section' ), 'coupon-options' );
				else
					add_settings_section( $slug, $title, array( &$this, 'display_section' ), 'coupon-options' );
			}
			$this->get_options();

			foreach ( $this->options as $id => $option ) {
				$option['id'] = $id;
				$this->create_option( $option );
			}

		}
		/*
		* Coupon Creator Admin Validate Options
		* since 1.80
		*/
		public function validate_options( $input ) {


			//if Reset is Checked then delete all options
			if ( ! isset( $input['reset_theme'] ) ) {

				//If No CheckBox Sent, then Unset the Option
				$options = get_option( 'coupon_creator_options' );

				foreach ( $this->checkboxes as $id ) {
					if ( isset( $options[$id] ) && ! isset( $input[$id] ) ) {
						unset( $options[$id] );
					}
				}

				//$id is option name - $option is array of values from $this->options
				foreach ( $this->options as $id => $option ) {

					if(isset($option['class'])){
						// Change Permalink Class Options to Lowercase
						if ( $option['class'] == 'permalink' ) {
							$input[$id] = str_replace(" ", "-",  strtolower(trim($input[$id])));
							//if option is new then set to flush permalinks
							if($options[$id] != $input[$id] ) {
								$permalink_change = $id . "_change";
								update_option($permalink_change, true);
							}
						}
					}
					//Prevent Placeholder From Saving in Option for Text Areas
					if($option['type'] == "textarea"){
						if ($input[$id] == $option['std']) {
							$input[$id] = false;
						}
					}

					// Create Separate License Option and Status
					if ( $option['type'] == 'license' ) {

						$cctor_license_info = array();

						//License WP Option Name
						$cctor_license = "cctor_" . $option['class'];

						//License Key
						$cctor_license_info['key'] = esc_attr(trim($input[$id]));

						//Get Existing Option
						$existing_license = get_option( $cctor_license );

						if ( !$existing_license['key'] ) {

							update_option( $cctor_license, $cctor_license_info);

						} elseif( $existing_license['key'] && $existing_license['key'] != $cctor_license_info['key'] ) {

							delete_option( $cctor_license );

							update_option( $cctor_license, $cctor_license_info);

						}

						// Remove to not save with Coupon Option Array
						$input[$id] = "";
					}

					// Handle License Status
					if ( $option['type'] == 'license_status' ) {
						// Remove to not save with Coupon Option Array
						$input[$id] = "";
					}

					// Sanitization Filter for each Option Type
					if(isset($input[$id])){
						if ( has_filter( 'cctor_sanitize_' . $option['type'] ) ) {
							$clean[$id] = apply_filters( 'cctor_sanitize_' . $option['type'], $input[$id], $option );
						}
					}

				}
				return $clean;
			}

			//Set Option to Flush Permalinks on Next Load as Reset was checked
			update_option('cctor_coupon_base_change', true);

			return false;

		}
	/***************************************************************************/


	} //end Coupon_Creator_Plugin_Admin_Options Class

} // class_exists( 'Coupon_Creator_Plugin_Admin_Options' )
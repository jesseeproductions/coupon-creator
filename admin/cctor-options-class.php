<?php
//If Direct Access Kill the Script
if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ ) {
	die( 'Access denied.' );
}

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
			$this->options    = array();
			$this->get_options();

			add_action( 'admin_menu', array( &$this, 'coupon_options_page' ) );
			add_action( 'admin_init', array( &$this, 'register_options' ), 15 );

			//Add Coupon Newsletter Sign Up
			add_action( 'cctor_after_option_form', array( __CLASS__, 'cctor_newsletter_signup' ) );

			if ( ! get_option( 'coupon_creator_options' ) ) {

				add_action( 'admin_init', array( &$this, 'set_defaults' ), 10 );

			}

		}
		/***************************************************************************/

		/*
		* Coupon Creator Set Default Options
		* since 2.1
		*/
		public function set_defaults() {

			$this->initialize_options();

		}
		/***************************************************************************/

		/*
		* Coupon Creator Option Sections
		* since 1.80
		*/
		public function get_sections() {

			//Coupon Creator Options Tabs
			$this->sections['defaults']   = __( 'Defaults', 'coupon-creator' );
			$this->sections['permalinks'] = __( 'Link Attributes / Permalinks', 'coupon-creator' );
			$this->sections['display']    = __( 'Display', 'coupon-creator' );
			$this->sections['help']       = __( 'Help', 'coupon-creator' );
			$this->sections['license']    = __( 'Licenses', 'coupon-creator' );
			$this->sections['reset']      = __( 'Reset', 'coupon-creator' );
			! defined( 'CCTOR_HIDE_UPGRADE' ) || ! CCTOR_HIDE_UPGRADE ? $this->sections['pro'] = __( 'Upgrade to Pro', 'coupon-creator' ) : '';

			unset( $this->sections['license'] );

			/**
			 * Filter Option Tabs
			 *
			 * @param array $sections an array of Option tab names and ids
			 *
			 */
			if ( has_filter( 'cctor_option_sections' ) ) {
				/**
				 * Filter the Coupon Creator Option Tab Header
				 *
				 *
				 * @param array $meta_tabs an array of tab headings.
				 *
				 */
				$this->sections = apply_filters( 'cctor_option_sections', $this->sections );
			}

		}

		/***************************************************************************/

		/*
		* Coupon Creator Admin Options Page
		* since 1.80
		*/
		public function coupon_options_page() {
			$admin_page = add_submenu_page( 'edit.php?post_type=cctor_coupon', // parent_slug
				__( 'Coupon Creator Options', 'coupon-creator' ), // page_title
				__( 'Options', 'coupon-creator' ), // menu_title
				'manage_options', // capability
				'coupon-options', // menu_slug
				array( &$this, 'display_page' ) // function
			);

			add_action( 'admin_print_scripts-' . $admin_page, array( &$this, 'coupon_option_scripts' ) );
			add_action( 'admin_print_styles-' . $admin_page, array( &$this, 'coupon_option_styles' ) );

		}

		/*
		* Coupon Creator Options Page Scripts
		* since 2.1
		*/
		public function coupon_option_scripts() {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-tabs' );

			//Script for WP Color Picker
			wp_enqueue_script( 'wp-color-picker' );
			$cctor_coupon_option_js = CCTOR_PATH . 'admin/js/cctor_coupon_options.js';
			wp_enqueue_script( 'cctor_coupon_option_js', CCTOR_URL . 'admin/js/cctor_coupon_options.js', array(
				'jquery',
				'thickbox',
				'farbtastic'
			), filemtime( $cctor_coupon_option_js ), true );

			$cctor_colorbox_js = CCTOR_PATH . 'vendor/colorbox/jquery.colorbox-min.js';
			wp_enqueue_script( 'cctor_colorbox_js', CCTOR_URL . 'vendor/colorbox/jquery.colorbox-min.js', array( 'jquery' ), filemtime( $cctor_colorbox_js ), true );

			/**
			 * Coupon Option Page Scripts
			 *
			 * @since 1.90
			 *
			 *
			 */
			do_action( 'cctor_options_scripts' );
		}

		/*
		* Coupon Creator Options Page Styles
		* since 1.80
		*/
		public function coupon_option_styles() {

			//jQuery UI
			global $wp_scripts;
			$jquery_version = isset( $wp_scripts->registered['jquery-ui-core']->ver ) ? $wp_scripts->registered['jquery-ui-core']->ver : '1.9.2';
			wp_enqueue_style( 'jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/' . $jquery_version . '/themes/smoothness/jquery-ui.css' );

			$cctor_options_css = CCTOR_PATH . 'admin/css/cctor-options.css';
			wp_enqueue_style( 'cctor_options_css', CCTOR_URL . 'admin/css/cctor-options.css', false, filemtime( $cctor_options_css ) );

			//Style or WP Color Picker
			wp_enqueue_style( 'wp-color-picker' );

			//Color Box For How to Videos
			$cctor_colorbox_css = CCTOR_PATH . 'vendor/colorbox/colorbox.css';
			wp_enqueue_style( 'cctor_colorbox_css', CCTOR_URL . 'vendor/colorbox/colorbox.css', false, filemtime( $cctor_colorbox_css ) );

			/**
			 * Coupon Option Page Styles
			 *
			 * @since 1.90
			 *
			 *
			 */
			do_action( 'cctor_options_styles' );

		}
		/***************************************************************************/
		/*
		* Coupon Creator Options
		* since 1.80
		*/
		public function create_option( $args = array() ) {

			$defaults = array(
				'id'        => 'default_id',
				'title'     => __( 'Default' ),
				'desc'      => __( 'This is a default description.' ),
				'alert'     => '',
				'condition' => '',
				'std'       => '',
				'type'      => 'text',
				'section'   => 'general',
				'choices'   => array(),
				'class'     => '',
				'imagemsg'  => '',
				'size'      => 35
			);

			$option_args = wp_parse_args( $args, $defaults );

			if ( $option_args['type'] == 'checkbox' ) {
				$this->checkboxes[] = $option_args['id'];
			}

			add_settings_field( $option_args['id'], $option_args['title'], array(
				$this,
				'display_setting'
			), 'coupon-options', $option_args['section'], $option_args );
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
				$tabs_array[ $section ] = $section_slug;
			}

			$tabs_json_array = json_encode( $tabs_array );

			//Detect if we saved or tried to save to set the current tab.
			$cctor_options_updated = get_settings_errors();

			global $wp_version;

			$cctor_tabs_variables = array(
				'tabs_arr'              => $tabs_json_array,
				'cctor_options_updated' => $cctor_options_updated,
				'wp_version'            => $wp_version,
			);

			wp_localize_script( 'cctor_coupon_option_js', 'cctor_coupon_option_js_vars', $cctor_tabs_variables );

			$js_troubleshoot_url = 'http://cctor.link/R7KRa';

			Coupon_Creator_Plugin::include_file( 'admin/cctor-help-class.php' );

			echo '<div class="wrap">
				<div class="icon32" id="icon-options-general"></div>
				<h2><img src="' . CCTOR_URL . 'admin/images/coupon_creator.png"/>  ' . __( 'Coupon Creator Options', 'coupon-creator' ) . '</h2>

				<div class="javascript-conflict cctor-error"><p>' . sprintf( __( 'There maybe a javascript conflict preventing some features from working.  <a href="%s" target="_blank" >Please check this guide to narrow down the cause.</a>', 'coupon-creator' ), esc_url( $js_troubleshoot_url ) ) . '</p></div>

				<h4>Coupon Creator: ' . get_option( CCTOR_VERSION_KEY ) . '</h4>';

			/**
			 * Before Coupon Options Form
			 *
			 * @since 1.90
			 *
			 *
			 */
			do_action( 'cctor_before_option_form' );

			if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true ) {
				echo '<div class="updated fade"><p>' . __( 'Coupon Creator Options updated.', 'coupon-creator' ) . '</p></div>';
			}

			echo '<form action="options.php" method="post">';

			settings_fields( 'coupon_creator_options' );
			echo '<div class="cctor-tabs">
						<ul class="cctor-tabs-nav">';

			foreach ( $this->sections as $section_slug => $section ) {
				echo '<li><a href="#' . $section_slug . '">' . $section . '</a></li>';
			}

			echo '</ul>';

			do_settings_sections( $_GET['page'] );

			echo '</div>
					<p class="submit"><input name="Submit" type="submit" class="button-primary" value="' . __( 'Save Changes' ) . '" /></p>

				</form>';

			/**
			 * After Coupon Options Form
			 *
			 * @since 1.90
			 *
			 *
			 */
			do_action( 'cctor_after_option_form' );

			echo '<p style="text-align:right;">&copy; ' . date( "Y" ) . ' Jessee Productions, LLC</p>

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
		public static function display_pro_section() {
			ob_start(); ?>
			<div class='cctor-pro-upsell'>
				<h4><img alt="Get Coupon Creator Pro!" src="<?php echo CCTOR_URL; ?>admin/images/cctor-logo.png"/></h4>
				<br>

				<p><strong style="font-size:15px;"><a target="_blank" href="http://cctor.link/Abqoi">Purchase Pro</a>
						and get all the features below with 1 year of updates and direct support.</strong></p>
				<br>
				<ul>
					<h4>Coupon Creator Pro Features Include:</h4><br>
					<li>Choose between 5 different border styles in Pro, including Saw Tooth, Stitched, Dotted, Coupon,
						and None.<br>
						<img class="cctor-pro-img" alt="Coupon Creator Pro Border Examples"
						     src="<?php echo CCTOR_URL; ?>admin/images/cctor-border-examples.gif"/>
					</li>
					<li>Setup Recurring Expirations with Patterns such as Monthly, Weekly, Biweekly, and Every 3
						Weeks:<br>
						<img class="cctor-pro-img" alt="Coupon Creator Pro Recurring Expiration"
						     src="<?php echo CCTOR_URL; ?>admin/images/cctor-recurring-expiration.gif"/>
					</li>
					<li>In Pro use the Visual editor to easily style the term's content on your site:<br>
						<img class="cctor-pro-img" alt="Coupon Creator Pro Visual Editor"
						     src="<?php echo CCTOR_URL; ?>admin/images/cctor-visual-editor.gif"/>
					</li>
					<li>Display the Print View in a Popup for any coupons and print directly from the Popup:<br>
						<img class="cctor-pro-img" alt="Coupon Creator Pro Popup"
						     src="<?php echo CCTOR_URL; ?>admin/images/cctor-popup.gif"/>
					</li>
					<li>Use the View Shortcodes to display content in the Shortcode View or the Print View only:<br>
						<img class="cctor-pro-img" alt="Coupon Creator Pro Shortcode for hooks and print views"
						     src="<?php echo CCTOR_URL; ?>admin/images/cctor-shortcodes.gif"/>
					</li>
					<li>Set a Counter per coupon to expire the coupon after a limit has been reached:<br>
						<img class="cctor-pro-img" alt="Coupon Creator Pro Counter"
						     src="<?php echo CCTOR_URL; ?>admin/images/cctor-pro-counter.png"/>
					</li>
					<li>Change "Expires on:", "Click to Open in Print View", and "Print the Coupon" for all Coupons</li>
					<li>Set Coupon Size for both views of the coupon for regular coupons and the image coupon</li>
					<li>Override "Click to Open in Print View" text and link per coupon</li>
					<li>Override "Print the Coupon" text and link per coupon</li>
					<li>Select where you want to display the Coupon Deal per coupon</li>
					<li>Disable the Print View per Coupon</li>
					<li>Add your Google Analytics Code to the Print Template from the Coupon Options</li>
					<li>Create and Display WooCommerce Coupons from the Coupon Creator Editor</li>
				</ul>
				<ul>
					<h4>Coupon Creator Pro Style Features:</h4><br>
					<li>Set all the styles for the coupons as defaults to get the same custom look for all your coupons
						with less work
					</li>
					<li>Set Inside Border Radius</li>
					<li>Select Coupon Outside Border Color</li>
					<li>Set Outer Border Radius, works for the image coupon too</li>
					<li>Select Coupon Terms Text Color</li>
					<li>Select Coupon Background Color</li>
					<li>Choose a Background Image with option to set Background Repeat, Background Position, and
						Background Size
					</li>
					<li>Direct Support through CouponCreatorPlugin.com</li>
				</ul>
				<br>
				<strong style="font-size:15px;"><a target="_blank" href="http://cctor.link/Abqoi">Purchase Pro Now!</a></strong>
			</div>
			<?php echo ob_get_clean();
		}

		/*
		* Coupon Creator Display Options
		* since 1.80
		*/
		public function display_setting( $option_args = array() ) {

			//Set for WP 4.3 and replacing wp_htmledit_pre
			global $wp_version;
			$cctor_required_wp_version = '4.3';

			$options = get_option( 'coupon_creator_options' );

			if ( ! isset( $options[ $option_args['id'] ] ) && $option_args['type'] != 'checkbox' ) {
				$options[ $option_args['id'] ] = $option_args['std'];
			} elseif ( ! isset( $options[ $option_args['id'] ] ) ) {
				$options[ $option_args['id'] ] = 0;
			}

			switch ( $option_args['type'] ) {

				case 'help':

					$help_class = new Coupon_Creator_Help_Class();
					$help_class->display_help( $option_args['section'] );

					break;

				case 'heading':
					if ( $option_args['alert'] ) {
						echo '</td></tr><tr valign="top"><td colspan="2"><span class="description">' . $option_args['alert'] . '</span>';
					} else {
						echo '</td></tr><tr valign="top"><td colspan="2"><h4>' . $option_args['desc'] . '</h4>';
					}
					break;

				case 'text':
					if ( $option_args['alert'] != '' && cctor_options( $option_args['condition'] ) == 1 ) {
						echo '<div class="cctor-error">' . $option_args['alert'] . '</div>';
					}

					echo '<input class="regular-text' . $option_args['class'] . '" type="text" id="' . $option_args['id'] . '" name="coupon_creator_options[' . $option_args['id'] . ']" placeholder="' . $option_args['std'] . '" value="' . esc_attr( $options[ $option_args['id'] ] ) . '" size="' . $option_args['size'] . '" />';

					if ( $option_args['desc'] != '' ) {
						echo '<br /><span class="description">' . $option_args['desc'] . '</span>';
					}

					break;

				case 'checkbox':

					echo '<input class="checkbox' . $option_args['class'] . '" type="checkbox" id="' . $option_args['id'] . '" name="coupon_creator_options[' . $option_args['id'] . ']" value="1" ' . checked( $options[ $option_args['id'] ], 1, false ) . ' /> <label for="' . $option_args['id'] . '">' . $option_args['desc'] . '</label>';

					break;

				// color
				case 'color':

					$default_color = '';
					if ( isset( $option_args['std'] ) ) {
						if ( $options[ $option_args['id'] ] != $option_args['std'] ) {
							$default_color = ' data-default-color="' . $option_args['std'] . '" ';
						}
					}

					echo '<input class="color-picker ' . $option_args['class'] . '" type="text" id="' . $option_args['id'] . '" name="coupon_creator_options[' . $option_args['id'] . ']" placeholder="' . $option_args['std'] . '" value="' . esc_attr( $options[ $option_args['id'] ] ) . '"' . $default_color . ' /><br /><span class="description">' . $option_args['desc'] . '</span>';

					break;

				case 'select':

					$cctor_select_value = $options[ $option_args['id'] ] ? $options[ $option_args['id'] ] : $option_args['std'];

					echo '<select class="select ' . $option_args['class'] . '" name="coupon_creator_options[' . $option_args['id'] . ']">';

					foreach ( $option_args['choices'] as $value => $label ) {

						$cctor_option_style = $option_args['class'] == 'css-select' ? 'style="' . esc_attr( $value ) . '"' : '';

						echo '<option ' . $cctor_option_style . ' value="' . esc_attr( $value ) . '"' . selected( $cctor_select_value, $value, false ) . '>' . esc_attr( $label ) . '</option>';

					}

					echo '</select>';

					if ( $option_args['desc'] != '' ) {
						echo '<br /><span class="description">' . $option_args['desc'] . '</span>';
					}

					break;

				case 'radio':
					$i = 0;
					foreach ( $option_args['choices'] as $value => $label ) {
						echo '<input class="radio' . $option_args['class'] . '" type="radio" name="coupon_creator_options[' . $option_args['id'] . ']" id="' . $option_args['id'] . $i . '" value="' . esc_attr( $value ) . '" ' . checked( $options[ $option_args['id'] ], $value, false ) . '> <label for="' . $option_args['id'] . $i . '">' . esc_attr( $label ) . '</label>';
						if ( $i < count( $options ) - 1 ) {
							echo '<br />';
						}
						$i ++;
					}

					if ( $option_args['desc'] != '' ) {
						echo '<br /><span class="description">' . $option_args['desc'] . '</span>';
					}

					break;

				case 'textarea':
					global $wp_version;
					if ( version_compare( $wp_version, $cctor_required_wp_version, '<' ) ) {
						echo '<textarea class="' . $option_args['class'] . '" id="' . $option_args['id'] . '" name="coupon_creator_options[' . $option_args['id'] . ']" placeholder="' . $option_args['std'] . '" rows="12" cols="50">' . wp_htmledit_pre( $options[ $option_args['id'] ] ) . '</textarea>';
					} else {
						echo '<textarea class="' . $option_args['class'] . '" id="' . $option_args['id'] . '" name="coupon_creator_options[' . $option_args['id'] . ']" placeholder="' . $option_args['std'] . '" rows="12" cols="50">' . format_for_editor( $options[ $option_args['id'] ] ) . '</textarea>';
					}

					if ( $option_args['desc'] != '' ) {
						echo '<br /><span class="description">' . $option_args['desc'] . '</span><br />';
					}
					break;

				case 'cctor_support':

					$help_class = new Coupon_Creator_Help_Class();
					$help_class->display_help( 'all' );
					echo Coupon_Creator_Help_Class::get_cctor_support_core_contact();

					break;

				case 'license':

					$cctor_license_info = array();
					$cctor_license      = "cctor_" . $option_args['class'];
					$cctor_license_info = get_option( $cctor_license );

					echo '<input class="regular-text' . $option_args['class'] . '" type="text" id="' . $option_args['id'] . '" name="coupon_creator_options[' . $option_args['id'] . ']" placeholder="' . $option_args['std'] . '" value="' . esc_attr( $cctor_license_info['key'] ) . '" size="' . $option_args['size'] . '"/>';

					if ( $option_args['desc'] != '' ) {
						echo '<br /><span class="description">' . $option_args['desc'] . '</span>';
					}
					break;

				case 'license_status':

					$cctor_license_info = array();
					$cctor_license      = "cctor_" . $option_args['class'];

					$cctor_license_info = get_option( $cctor_license );

					//Coupon Expiration Date
					if ( isset( $cctor_license_info['expires'] ) ) {
						$expirationco = $cctor_license_info['expires'];
					} else {
						$expirationco = '';;
					}

					$cc_expiration_date = strtotime( $expirationco );

					if ( $expirationco ) { // Only Display Expiration if Date
						$daymonth_date_format = cctor_options( 'cctor_default_date_format' ); //Date Format

						if ( $daymonth_date_format == 1 ) { //Change to Day - Month Style
							$expirationco = date( "d/m/Y", $cc_expiration_date );
						} else {
							$expirationco = date( "m/d/Y", $cc_expiration_date );
						}

						$expiration_date = sprintf( __( ' and Expires on %s', 'coupon-creator' ), esc_attr( $expirationco ) );
					}

					if ( isset( $cctor_license_info['status'] ) && $cctor_license_info['status'] !== false && $cctor_license_info['status'] == 'valid' ) {

						echo '<span style="color:green;">' . __( 'License is Active', 'coupon-creator' ) . $expiration_date . '</span><br><br>';

						wp_nonce_field( 'cctor_license_nonce', 'cctor_license_nonce' );

						echo '<input type="hidden" class="cctor_license_key" name="cctor_license_key" value="cctor_' . esc_attr( $option_args['class'] ) . '"/>';
						echo '<input type="hidden" class="cctor_license_name" name="cctor_license_name" value="' . esc_attr( $option_args['condition'] ) . '"/>';
						echo '<input type="submit" class="cctor-license-button-act" name="cctor_license_deactivate" value="' . _( 'Deactivate License' ) . '"/>';

					} else {
						$cctor_license_info_valid = "";
						if ( isset( $cctor_license_info['status'] ) && ( $cctor_license_info['status'] == 'invalid' || $cctor_license_info['status'] == 'missing' ) && ! $cctor_license_info['expired'] ) {
							$cctor_license_info_valid = __( 'License is Invalid', 'coupon-creator' );
						} elseif ( isset( $cctor_license_info['expired'] ) && $cctor_license_info['expired'] == "expired" ) {
							$cctor_license_info_valid = sprintf( __( 'License Expired on %s', 'coupon-creator' ), esc_attr( $expirationco ) );
						} else {
							$cctor_license_info_valid = __( 'License is Not Active', 'coupon-creator' );
						}

						echo '<span style="color:red;">' . $cctor_license_info_valid . '</span><br><br>';

						wp_nonce_field( 'cctor_license_nonce', 'cctor_license_nonce' );

						echo '<input type="hidden" class="cctor_license_key" name="cctor_license_key" value="cctor_' . esc_attr( $option_args['class'] ) . '"/>';
						echo '<input type="hidden" class="cctor_license_name" name="cctor_license_name" value="' . esc_attr( $option_args['condition'] ) . '"/>';
						echo '<input type="submit" class="cctor-license-button-det" name="cctor_license_activate" value="' . __( 'Activate License' ) . '"/>';

					}

					break;
			}

			if ( has_filter( 'cctor_option_cases' ) ) {
				/**
				 * Filter the cases for Coupon Creator Meta
				 *
				 * @param array $options     current coupon option field being displayed.
				 * @param array $option_args current value of option saved.
				 */
				echo apply_filters( 'cctor_option_cases', $options, $option_args );
			}
		}
		/***************************************************************************/

		/*
		* Coupon Creator Options
		* since 1.80
		*/
		public function get_options() {

			//defaults
			$this->options['defaults_help'] = array(
				'section' => 'defaults',
				'type'    => 'help'
			);
			$this->options['header_defaults'] = array(
				'section' => 'defaults',
				'title'   => '',
				'alert'   => __( '*These are defaults for new coupons only and do not change existing coupons.', 'coupon-creator' ),
				'type'    => 'heading'
			);
			//Expiration
			$this->options['header_expiration'] = array(
				'section' => 'defaults',
				'title'   => '',
				'desc'    => __( 'Expiration', 'coupon-creator' ),
				'type'    => 'heading'
			);

			$expiration_options = array(
				'1' => __( 'Ignore Expiration', 'coupon-creator' ),
				'2' => __( 'Expiration Date', 'coupon-creator' )
			);
			if ( class_exists( 'Coupon_Creator_Pro_Plugin' ) ) {
				$expiration_options = array(
					'1' => __( 'Ignore Expiration', 'coupon-creator' ),
					'2' => __( 'Expiration Date', 'coupon-creator' ),
					'3' => __( 'Recurring Expiration', 'coupon-creator' ),
					'4' => __( 'Expires in X Days', 'coupon-creator' )
				);
			}

			$this->options['cctor_expiration_option'] = array(
				'section' => 'defaults',
				'title'   => __( 'Expiration Option', 'coupon-creator' ),
				'desc'    => __( 'Choose the expiration method for this coupon', 'coupon-creator' ),
				'std'     => '1',
				'type'    => 'select',
				'choices' => $expiration_options,
			);

			$this->options['cctor_default_date_format']                  = array(
				'section' => 'defaults',
				'title'   => __( 'Expiration Date Format', 'coupon-creator' ),
				'desc'    => __( 'Select the Date Format to show for all Coupons*', 'coupon-creator' ),
				'type'    => 'select',
				'std'     => '0',
				'choices' => array(
					'0' => __( 'Month First - MM/DD/YYYY', 'coupon-creator' ),
					'1' => __( 'Day First - DD/MM/YYYY', 'coupon-creator' )
				)
			);
			$this->options['cctor_pro_recurrence_pattern_default']       = array(
				'type'    => '',
				'section' => ''
			);
			$this->options['cctor_pro_recurrence_pattern_limit_default'] = array(
				'type'    => '',
				'section' => ''
			);
			$this->options['cctor_pro_recurrence_pattern_limit_default'] = array(
				'type'    => '',
				'section' => ''
			);
			$this->options['cctor_pro_x_days_default'] = array(
				'type'    => '',
				'section' => ''
			);

			//Outer Border
			$this->options['cctor_pro_heading_outer_border'] = array(
				'type'    => '',
				'section' => ''
			);
			$this->options['cctor_pro_default_border_style'] = array(
				'type'    => '',
				'section' => ''
			);
			$this->options['cctor_outer_border_color']       = array(
				'type'    => '',
				'section' => ''
			);
			$this->options['cctor_pro_outer_border_default'] = array(
				'type'    => '',
				'section' => ''
			);

			//Inner Border
			$this->options['header_inner_border']            = array(
				'section' => 'defaults',
				'title'   => '',
				'desc'    => __( 'Inner Border', 'coupon-creator' ),
				'type'    => 'heading'
			);
			$this->options['cctor_border_color']             = array(
				'title'   => __( 'Inside Border Color', 'coupon-creator' ),
				'desc'    => __( 'Choose default inside border color*', 'coupon-creator' ),
				'std'     => '#81d742',
				'type'    => 'color', // color
				'section' => 'defaults'
			);
			$this->options['cctor_pro_inner_border_default'] = array(
				'type'    => '',
				'section' => ''
			);

			//Discount Field Colors
			$this->options['header_discount']           = array(
				'section' => 'defaults',
				'title'   => '', // Not used for headings.
				'desc'    => __( 'Deal Field Colors', 'coupon-creator' ),
				'type'    => 'heading'
			);
			$this->options['cctor_discount_bg_color']   = array(
				'title'   => __( 'Deal Background Color', 'coupon-creator' ),
				'desc'    => __( 'Choose default background color*', 'coupon-creator' ),
				'std'     => '#4377df',
				'type'    => 'color', // color
				'section' => 'defaults'
			);
			$this->options['cctor_discount_text_color'] = array(
				'title'   => __( 'Deal Text Color', 'coupon-creator' ),
				'desc'    => __( 'Choose default text color*', 'coupon-creator' ),
				'std'     => '#000000',
				'type'    => 'color', // color
				'section' => 'defaults'
			);

			//LinkAttributes - Permalinks
			$this->options['no_follow_heading']             = array(
				'section' => 'permalinks',
				'title'   => '', // Not used for headings.
				'desc'    => __( 'Link Attribute Options', 'coupon-creator' ),
				'type'    => 'heading'
			);
			$this->options['cctor_nofollow_print_link']     = array(
				'section' => 'permalinks',
				'title'   => __( 'Print View Links', 'coupon-creator' ),
				'desc'    => __( 'Add nofollow to all the "Click to Open in Print View" links', 'coupon-creator' ),
				'type'    => 'checkbox',
				'std'     => 1 // Set to 1 to be checked by default, 0 to be unchecked by default.
			);
			$this->options['cctor_hide_print_link']         = array(
				'section' => 'permalinks',
				'title'   => __( 'Disable Print View', 'coupon-creator' ),
				'desc'    => __( 'This will disable all custom links and the popup option in Pro as well as the "Click to Open in Print View" links under the coupon', 'coupon-creator' ),
				'type'    => 'checkbox',
				'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
			);
			$this->options['cctor_nofollow_print_template'] = array(
				'section' => 'permalinks',
				'title'   => __( 'Print Template No Follow', 'coupon-creator' ),
				'desc'    => __( 'Add nofollow and noindex to the print template', 'coupon-creator' ),
				'type'    => 'checkbox',
				'std'     => 1 // Set to 1 to be checked by default, 0 to be unchecked by default.
			);
			$this->options['header_permalink']              = array(
				'section' => 'permalinks',
				'title'   => '', // Not used for headings.
				'desc'    => __( 'Permalink Options', 'coupon-creator' ),
				'type'    => 'heading'
			);
			$this->options['cctor_coupon_base']             = array(
				'title'   => __( 'Coupon Print Template Slug', 'coupon-creator' ),
				'desc'    => __( 'default: cctor_coupon', 'coupon-creator' ),
				'std'     => '',
				'type'    => 'text',
				'section' => 'permalinks',
				'class'   => 'permalink' //format text to lowercase before sanitizing
			);
			$this->options['cctor_coupon_category_base']    = array(
				'type'    => '',
				'section' => ''
			);

			//Custom CSS
			$this->options['cctor_custom_css'] = array(
				'title'   => __( 'Custom Coupon Styles', 'coupon-creator' ),
				'desc'    => __( 'Enter any custom CSS here to apply to the coupons for the shortcode and the print template.(without &#60;style&#62; tags)', 'coupon-creator' ),
				'std'     => 'e.g. .cctor_coupon_container { width: 000px; }',
				'type'    => 'textarea',
				'section' => 'display',
				'class'   => 'code'
			);
			//wpautop
			$this->options['cctor_wpautop'] = array(
				'section' => 'display',
				'title'   => __( 'Auto P Filter', 'coupon-creator' ),
				'desc'    => __( 'Check to remove <a href="http://codex.wordpress.org/Function_Reference/wpautop" target="_blank">wpautop filter</a> from Coupon Terms Field', 'coupon-creator' ),
				'type'    => 'checkbox',
				'std'     => 1 // Set to 1 to be checked by default, 0 to be unchecked by default.
			);
			//wpautop
			$this->options['cctor_print_base_css'] = array(
				'section' => 'display',
				'title'   => __( 'Print View Base CSS', 'coupon-creator' ),
				'desc'    => __( 'Check to disable the base CSS in Print View', 'coupon-creator' ),
				'type'    => 'checkbox',
				'std'     => 0 // Set to 1 to be checked by default, 0 to be unchecked by default.
			);

			//Search
			$this->options['search_heading'] = array(
				'section' => 'display',
				'title'   => '',
				'desc'    => __( 'WordPress Search', 'coupon-creator' ),
				'type'    => 'heading'
			);
			$this->options['coupon-search']  = array(
				'section' => 'display',
				'title'   => __( 'Coupon Search', 'coupon-creator' ),
				'type'    => 'checkbox',
				'std'     => 0,
				'class'   => '',
				'desc'    => __( 'Check this to prevent the Coupon Creator from modifying the search query to remove the coupon custom post type.', 'coupon-creator' )
			);

			//Help
			$this->options['cctor_help'] = array(
				'section' => 'help',
				'title'   => __( 'Support: ', 'coupon-creator' ),
				'type'    => 'cctor_support',
				'std'     => 0,
				'desc'    => ''
			);

			$this->options['reset_heading'] = array(
				'section' => 'reset',
				'title'   => '', // Not used for headings.
				'desc'    => __( 'Coupon Creator Option Reset', 'coupon-creator' ),
				'type'    => 'heading'
			);

			//Reset
			$this->options['reset_theme'] = array(
				'section' => 'reset',
				'title'   => __( 'Reset', 'coupon-creator' ),
				'type'    => 'checkbox',
				'std'     => 0,
				'class'   => 'warning', // Custom class for CSS
				'desc'    => __( 'Check this box and click "Save Changes" below to reset all coupon creator options to their defaults. This does not change any existing coupon settings or remove your licenses.', 'coupon-creator' )
			);

			//Filter Option Fields
			if ( has_filter( 'cctor_option_filter' ) ) {
				/**
				 * Filter the options fields from Coupon Creator
				 *
				 *
				 * @param array $this ->options an array of fields to display in option tabs.
				 *
				 */
				$this->options = apply_filters( 'cctor_option_filter', $this->options );
			}

		}    // End get_options()

		/***************************************************************************/

		/*
		* Coupon Creator Display Newsletter Sign Up
		* since 1.90
		*/
		public static function cctor_newsletter_signup() {

			echo '<div class="cctor-promo-boxes">

					<h3>Keep The Coupon Creator Going!</h3>
					<p>Every time you rate <strong>5 stars</strong>, it shows your support for the Coupon Creator and helps make it better!</p>
					<p><a href="https://wordpress.org/support/view/plugin-reviews/coupon-creator?filter=5" target="_blank" class="button-primary">Rate It</a></p>
				</div>';

			echo '<!-- Begin MailChimp Signup Form -->
					<div id="mc_embed_signup" class="cctor-promo-boxes">
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
					</div><!--End mc_embed_signup-->';
		}

		/***************************************************************************/

		/*
		* Coupon Creator Initialize Options and Default Values
		* since 1.80
		*/
		public function initialize_options() {

			$default_options = array();
			$this->get_options();

			foreach ( $this->options as $id => $option ) {

				if ( $option['type'] != 'heading' && isset( $option['std'] ) ) {

					//Sanitize Default
					$cctor_sanitize = new Coupon_Creator_Plugin_Sanitize( $option['type'], $option['std'], $option );

					//Set Sanitized Input in Array
					$default_options[ $id ] = $cctor_sanitize->result;
				}

			}

			update_option( 'coupon_creator_options', $default_options );

		}

		/*
		* Coupon Creator Register Options
		* since 1.80
		*/
		public function register_options() {

			//Get Tab Sections and Options
			$this->get_sections();
			$this->get_options();

			register_setting( 'coupon_creator_options', 'coupon_creator_options', array( &$this, 'validate_options' ) );

			foreach ( $this->sections as $slug => $title ) {
				if ( $slug == 'pro' ) {
					add_settings_section( $slug, $title, array( &$this, 'display_pro_section' ), 'coupon-options' );
				} else {
					add_settings_section( $slug, $title, array( &$this, 'display_section' ), 'coupon-options' );
				}
			}


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
					if ( isset( $options[ $id ] ) && ! isset( $input[ $id ] ) ) {
						unset( $options[ $id ] );
					}
				}

				//$id is option name - $option is array of values from $this->options
				foreach ( $this->options as $id => $option ) {

					if ( isset( $option['class'] ) ) {
						// Change Permalink Class Options to Lowercase
						if ( $option['class'] == 'permalink' ) {
							$input[ $id ] = str_replace( " ", "-", strtolower( trim( $input[ $id ] ) ) );
							//if option is new then set to flush permalinks
							if ( $options[ $id ] != $input[ $id ] ) {
								$permalink_change = $id . "_change";
								update_option( $permalink_change, true );
							}
						}
					}
					//Prevent Placeholder From Saving in Option for Text Areas
					if ( $option['type'] == "textarea" ) {
						if ( $input[ $id ] == $option['std'] ) {
							$input[ $id ] = false;
						}
					}

					// Create Separate License Option and Status
					if ( $option['type'] == 'license' && isset( $input[ $id ] ) ) {

						//Send Input to Sanitize Class, will return sanitized input or no input if no sanitization method
						$cctor_sanitize = new Coupon_Creator_Plugin_Sanitize( $option['type'], $input[ $id ], $option );

						$cctor_license_info = array();

						//License WP Option Name
						$cctor_license = "cctor_" . $option['class'];

						//License Key
						$cctor_license_info['key'] = $cctor_sanitize->result;

						//Get Existing Option
						$existing_license = get_option( $cctor_license );

						if ( ! $existing_license['key'] ) {

							update_option( $cctor_license, $cctor_license_info );

						} elseif ( $existing_license['key'] && $existing_license['key'] != $cctor_license_info['key'] ) {

							delete_option( $cctor_license );

							update_option( $cctor_license, $cctor_license_info );

						}

						// Remove to not save with Coupon Option Array
						$input[ $id ] = "";
					}

					// Handle License Status
					if ( $option['type'] == 'license_status' ) {
						// Remove to not save with Coupon Option Array
						$input[ $id ] = "";
					}

					// Sanitization Filter for each Option Type
					if ( isset( $input[ $id ] ) && $option['type'] != 'license' && $option['type'] != 'license_status' ) {

						//Send Input to Sanitize Class, will return sanitized input or no input if no sanitization method
						$cctor_sanitize = new Coupon_Creator_Plugin_Sanitize( $option['type'], $input[ $id ], $option );

						//Set Sanitized Input in Array
						$clean[ $id ] = $cctor_sanitize->result;

					}

				}

				return $clean;
			}

			//Set Option to Flush Permalinks on Next Load as Reset was checked
			update_option( 'cctor_coupon_base_change', true );

			return false;

		}
		/***************************************************************************/


	} //end Coupon_Creator_Plugin_Admin_Options Class

} // class_exists( 'Coupon_Creator_Plugin_Admin_Options' )
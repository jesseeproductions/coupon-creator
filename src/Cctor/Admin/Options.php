<?php
//If Direct Access Kill the Script
if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ ) {
	die( 'Access denied.' );
}


/*
* Admin Options Class
*
*/


class Cctor__Coupon__Admin__Options Extends Pngx__Admin__Options {

	/*
	* Tab Sections
	*/
	protected $sections;
	/*
	* Checkbox Fields
	*/
	protected $checkboxes;
	/*
	* Option Fields
	*/
	public $options;

	/*
	* Options Page Slug
	*/
	protected $options_slug = 'coupon-options';

	/*
	* Construct
	*/
	public function __construct() {

		//Track Checkbox Options for validate_options()
		$this->checkboxes = array();
		$this->get_fields();
		$this->set_sections();

		add_action( 'admin_menu', array( $this, 'options_page' ) );
		add_action( 'admin_init', array( $this, 'register_options' ), 15 );

		//Add Coupon Newsletter Sign Up
		add_action( 'cctor_after_option_form', array( __CLASS__, 'cctor_newsletter_signup' ) );

		if ( ! get_option( 'coupon_creator_options' ) ) {
			add_action( 'admin_init', array( &$this, 'set_defaults' ), 10 );
		}

		add_action( 'pngx_before_option_form', array( __CLASS__, 'display_options_header' ) , 10 );

	}

	/*
	* Admin Options Page
	*/
	public function options_page() {

		$admin_page = add_submenu_page( 'edit.php?post_type=cctor_coupon', // parent_slug
			__( 'Coupon Creator Options', 'coupon-creator' ), // page_title
			__( 'Options', 'coupon-creator' ), // menu_title
			'manage_options', // capability
			$this->options_slug, // menu_slug
			array( $this, 'display_fields' ) // function
		);

		add_action( 'admin_print_scripts-' . $admin_page,  array( 'Cctor__Coupon__Admin__Assets', 'load_assets' ) );

	}

	/*
	* Register Options
	*/
	public function register_options() {

		register_setting( 'coupon_creator_options', 'coupon_creator_options', array( $this, 'validate_options' ) );

		foreach ( $this->sections as $slug => $title ) {
			if ( 'pro' == $slug ) {
				add_settings_section( $slug, $title, array( $this, 'display_pro_section' ), $this->options_slug );
			} else {
				add_settings_section( $slug, $title, array( $this, 'display_fields' ), $this->options_slug );
			}
		}

		foreach ( $this->options as $id => $option ) {
			$option['id'] = $id;
			$this->create_option( $option );
		}

	}

	/*
	* Option Tabs
	*/
	public function set_sections() {

		//Section Tab Headings
		$this->sections['defaults']   = __( 'Defaults', 'coupon-creator' );
		/*$this->sections['permalinks'] = __( 'Link Attributes / Permalinks', 'coupon-creator' );
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
		//if ( has_filter( 'cctor_option_sections' ) ) {
			/**
			 * Filter the Coupon Creator Option Tab Header
			 *
			 * @param array $meta_tabs an array of tab headings.
			 *
			 */
		//	$this->sections = apply_filters( 'cctor_option_sections', $this->sections );
		//}

	}

	/*
	* Options Header
	*/
	public static function display_options_header() {

		$js_troubleshoot_url = 'http://cctor.link/R7KRa';

		echo '<div class="wrap">
			<div class="icon32" id="icon-options-general"></div>
			<h2><img src="' . Cctor__Coupon__Main::instance()->resource_url . 'images/coupon_creator.png"/>  ' . __( 'Coupon Creator Options', 'coupon-creator' ) . '</h2>

			<div class="javascript-conflict pngx-error"><p>' . sprintf( __( 'There maybe a javascript conflict preventing some features from working.  <a href="%s" target="_blank" >Please check this guide to narrow down the cause.</a>', 'coupon-creator' ), esc_url( $js_troubleshoot_url ) ) . '</p></div>

			<h4>Coupon Creator: ' . get_option( Cctor__Coupon__Main::CCTOR_VERSION_KEY ) . '</h4>';

		if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true ) {
			echo '<div class="updated fade"><p>' . __( 'Coupon Creator Options updated.', 'coupon-creator' ) . '</p></div>';
		}

	}

	/*
	* Coupon Creator Pro Section
	*/
	public static function display_pro_section() {
		ob_start(); ?>
		<div class='cctor-pro-upsell'>
			<h4><img alt="Get Coupon Creator Pro!" src="<?php echo Cctor__Coupon__Main::instance()->resource_url; ?>images/cctor-logo.png"/></h4>
			<br>

			<p><strong style="font-size:15px;"><a target="_blank" href="http://cctor.link/Abqoi">Purchase Pro</a>
					and get all the features below with 1 year of updates and direct support.</strong></p>
			<br>
			<ul>
				<h4>Coupon Creator Pro Features Include:</h4><br>
				<li>Choose between 5 different border styles in Pro, including Saw Tooth, Stitched, Dotted, Coupon,
					and None.<br>
					<img class="cctor-pro-img" alt="Coupon Creator Pro Border Examples"
					     src="<?php echo Cctor__Coupon__Main::instance()->resource_url; ?>images/cctor-border-examples.gif"/>
				</li>
				<li>Setup Recurring Expirations with Patterns such as Monthly, Weekly, Biweekly, and Every 3
					Weeks:<br>
					<img class="cctor-pro-img" alt="Coupon Creator Pro Recurring Expiration"
					     src="<?php echo Cctor__Coupon__Main::instance()->resource_url; ?>images/cctor-recurring-expiration.gif"/>
				</li>
				<li>In Pro use the Visual editor to easily style the term's content on your site:<br>
					<img class="cctor-pro-img" alt="Coupon Creator Pro Visual Editor"
					     src="<?php echo Cctor__Coupon__Main::instance()->resource_url; ?>images/cctor-visual-editor.gif"/>
				</li>
				<li>Display the Print View in a Popup for any coupons and print directly from the Popup:<br>
					<img class="cctor-pro-img" alt="Coupon Creator Pro Popup"
					     src="<?php echo Cctor__Coupon__Main::instance()->resource_url; ?>images/cctor-popup.gif"/>
				</li>
				<li>Use the View Shortcodes to display content in the Shortcode View or the Print View only:<br>
					<img class="cctor-pro-img" alt="Coupon Creator Pro Shortcode for hooks and print views"
					     src="<?php echo Cctor__Coupon__Main::instance()->resource_url; ?>images/cctor-shortcodes.gif"/>
				</li>
				<li>Set a Counter per coupon to expire the coupon after a limit has been reached:<br>
					<img class="cctor-pro-img" alt="Coupon Creator Pro Counter"
					     src="<?php echo Cctor__Coupon__Main::instance()->resource_url; ?>images/cctor-pro-counter.png"/>
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
			<strong style="font-size:15px;"><a target="_blank" href="http://cctor.link/Abqoi">Purchase Pro
					Now!</a></strong>
		</div>
		<?php echo ob_get_clean();
	}

	/***************************************************************************/

	/*
	* Coupon Creator Options
	* since 1.80
	*/
	public function get_fields() {

		//defaults
		$this->options['defaults_help']   = array(
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

		/*$this->options['cctor_default_date_format']                  = array(
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
		$this->options['cctor_pro_x_days_default']                   = array(
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
		$this->options['permalinks_help']               = array(
			'section' => 'permalinks',
			'type'    => 'help'
		);
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
			'section' => '',
			'class'   => ''
		);

		//display
		$this->options['display_help'] = array(
			'section' => 'display',
			'type'    => 'help'
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
			'type'    => 'help',
			'std'     => 0,
			'desc'    => ''
		);

		$this->options['reset_heading'] = array(
			'section' => 'reset',
			'title'   => '', // Not used for headings.
			'desc'    => __( 'Coupon Creator Option Reset', 'coupon-creator' ),
			'type'    => 'heading'
		);

		$this->options['license_help'] = array(
			'section' => 'license',
			'type'    => 'help'
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
		/*	$this->options = apply_filters( 'cctor_option_filter', $this->options );
		}*/

	}    // End get_fields()

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

} //end Coupon_Creator_Plugin_Admin_Options Class


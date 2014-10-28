<?php
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

if ( ! class_exists( 'Coupon_Creator_Plugin_Admin_Options' ) ) {	
	/*
	* Coupon_Creator_Plugin_Admin_Options
	* @version 1.80
	*/
	class Coupon_Creator_Plugin_Admin_Options {
		/*
		* Tab Sections
		* @version 1.80
		*/
		private $sections;
		/* Checkbox Fields
		* Construct
		* @version 1.80
		*/
		private $checkboxes;
		/*
		* Option Fields
		* @version 1.80
		*/
		public $options;
		
		/*
		* Construct
		* @version 1.80
		*/
		public function __construct() {
			
			//Track Checkbox Options for validate_options()
			$this->checkboxes = array();
			$this->options = array();
			$this->get_options();
				
			add_action( 'admin_menu', array( &$this, 'coupon_options_page' ) );
			add_action( 'admin_init', array( &$this, 'register_options' ) );
			
			//Set Standard Options if None Found
			if ( ! get_option( 'coupon_creator_options' ) )
				$this->initialize_options();
		}
	/***************************************************************************/

		/*
		* Coupon Creator Option Sections
		* @version 1.80
		*/
		public function get_sections() {
			
			//Coupon Creator Options Tabs
			$this->sections['defaults']     = __( 'Defaults', 'coupon_creator' );
			$this->sections['permalinks']   = __( 'Link Attributes / Permalinks', 'coupon_creator' );
			$this->sections['display'] 		= __( 'Display', 'coupon_creator' );
			$this->sections['reset']        = __( 'Reset', 'coupon_creator' );
			$this->sections['pro']        = __( 'Pro', 'coupon_creator' );

			//Filter Option Tabs
			if(has_filter('cctor_option_sections')) {
				$this->sections = apply_filters('cctor_option_sections', $this->sections);
			} 
			
		}
		
	/***************************************************************************/
		
		/*
		* Coupon Creator Admin Options Page
		* @version 1.80
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
		* @version 1.80
		*/
		public function coupon_option_scripts() {
			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-tabs');
			 
			//Script for WP Color Picker
			wp_enqueue_script( 'wp-color-picker' );
			$cctor_coupon_meta_js = CCTOR_PATH.'admin/js/cctor_coupon_options.js';
			wp_enqueue_script('cctor_coupon_meta_js',  CCTOR_URL . '/admin/js/cctor_coupon_options.js', array('jquery','thickbox','farbtastic'), filemtime($cctor_coupon_meta_js), true);	
			
			//Hook to Load New Scripts
			do_action('cctor_opitons_scripts');
		}
		
		/*
		* Coupon Creator Options Page Styles
		* @version 1.80
		*/
		public function coupon_option_styles() {
			
			$cctor_options_css = CCTOR_PATH.'admin/css/cctor-options.css';
			wp_enqueue_style( 'cctor_options_css', CCTOR_URL . 'admin/css/cctor-options.css', false, filemtime($cctor_options_css));
			
			//Style or WP Color Picker
			wp_enqueue_style( 'wp-color-picker' );  
			
			//Hook to Load New Styles
			do_action('cctor_opitons_styles');
	
		}
	/***************************************************************************/
		/*
		* Coupon Creator Options
		* @version 1.80
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
		* @version 1.80
		*/
		public function display_page() {
			
			//Get Tab Sections to Show
			$this->get_sections();
			
			echo '<div class="wrap">
				<div class="icon32" id="icon-options-general"></div>
				<h2><img src="'. CCTOR_URL . 'admin/images/coupon_creator.png"/>  ' . __( 'Coupon Creator Options' ) . '</h2>
				<h4>Version: '. get_option(CCTOR_VERSION_KEY).'</h4>';
				
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
				
				echo '<script type="text/javascript">
					jQuery(document).ready(function($) {
						var sections = [];';
						
						foreach ( $this->sections as $section_slug => $section )
							echo "sections['$section'] = '$section_slug';";
						
						echo 'var wrapped = $(".wrap h3").wrap("<div class=\"cctor-tabs-panel\">");
						wrapped.each(function() {
							$(this).parent().append($(this).parent().nextUntil("div.cctor-tabs-panel"));
						});
						$(".cctor-tabs-panel").each(function(index) {
							$(this).attr("id", sections[$(this).children("h3").text()]);
							if (index > 0)
								$(this).addClass("cctor-tabs-hide");
						});
						$(".cctor-tabs").tabs({
							fx: { opacity: "toggle", duration: "fast" }
						});
						
						$("input[type=text], textarea").each(function() {
							if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "")
								$(this).css("color", "#999");
						});
						
						$("input[type=text], textarea").focus(function() {
							if ($(this).val() == $(this).attr("placeholder") || $(this).val() == "") {
								$(this).val("");
								$(this).css("color", "#000");
							}
						}).blur(function() {
							if ($(this).val() == "" || $(this).val() == $(this).attr("placeholder")) {
								$(this).val($(this).attr("placeholder"));
								$(this).css("color", "#999");
							}
						});
						
						
						$(".wrap h3, .wrap table").show();
						
						// Browser compatibility
						if ($.browser.mozilla) 
							$("form").attr("autocomplete", "off");
					});
					
					/*Hide Row if Label is Empty*/
					jQuery(".form-table label:empty").parent().hide();

				</script>
							
				<p style="text-align:right;">&copy; '.date("Y").' Jessee Productions, LLC</p>
			
			</div>';
		}	
		
	/***************************************************************************/
	
		/*
		* Display Section
		* @version 1.80
		*/
		public function display_section() {


		}

		/*
		* Coupon Creator About Section
		* @version 1.80
		*/
		public function display_about_section() {
			
			echo '<p>Copyright '.date("Y").' Jessee Productions, LLC</p>';
			
		}
		
		/*
		* Coupon Creator Pro Section
		* @version 1.80
		*/
		public function display_pro_section() {
			ob_start(); ?>	
			<div>
				<h2><img src="<?php echo CCTOR_URL . 'admin/images/coupon-creator-pro.png'; ?>" alt="Coupon Creator Pro Coming Soon!"/></h2>
				<br>
				<a href="http://JesseeProductions.com/coupon-creator/" target="_blank">Request Features for the Pro Version</a>
				<br>
				<br><hr>
			</div>
			<?php echo ob_get_clean();
		}
		
		/*
		* Coupon Creator Display Options
		* @version 1.80
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
						echo cctor_options('cctor_custom_css');
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
		* @version 1.80
		*/
		public function get_options() {
			
			/* Coupon Creator Options
			===========================================*/		
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
			$this->options['header_discount'] = array(
				'section' => 'defaults',
				'title'   => '', // Not used for headings.
				'desc'    =>  __( 'Discount Field Colors','coupon_creator' ),
				'type'    => 'heading'
			);				
			$this->options['cctor_discount_bg_color'] = array(
				'title' =>  __( 'Discount Background Color','coupon_creator' ),
				'desc'  =>  __( 'Choose default background color*','coupon_creator' ),
				'std'     => '#4377df',
				'type' => 'color', // color
				'section' => 'defaults'
			);
			$this->options['cctor_discount_text_color'] = array(
				'title' =>  __( 'Discount Text Color','coupon_creator' ),
				'desc'  =>  __( 'Choose default text color*','coupon_creator' ),
				'std'     => '#000000',
				'type' => 'color', // color
				'section' => 'defaults'
			);
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
				'title'   => __( 'Coupon Creator Base', 'coupon_creator' ),
				'desc'    => __( 'default: cctor_coupon', 'coupon_creator' ),
				'std'     => '',
				'type'    => 'text',
				'section' => 'permalinks',
				'class'   => 'permalink' //format text to lowercase before sanitizing
			);				
			$this->options['cctor_custom_css'] = array(
				'title'   => __( 'Custom Coupon Styles', 'coupon_creator' ),
				'desc'    => __( 'Enter any custom CSS here to apply to the coupons for the shortcode and the print template.(without &#60;style&#62; tags)', 'coupon_creator' ),
				'std'     => 'e.g. .cctor_coupon_container { width: 000px; }',
				'type'    => 'textarea',
				'section' => 'display',
				'class'   => 'code'
			);				
			$this->options['reset_heading'] = array(
				'section' => 'reset',
				'title'   => '', // Not used for headings.
				'desc'    =>  __( 'Coupon Creator Option Reset','coupon_creator' ),
				'type'    => 'heading'
			);
			$this->options['reset_theme'] = array(
				'section' => 'reset',
				'title'   => __( 'Reset', 'coupon_creator' ),
				'type'    => 'checkbox',
				'std'     => 0,
				'class'   => 'warning', // Custom class for CSS
				'desc'    => __( 'Check this box and click "Save Changes" below to reset all coupon creator options to their defaults.', 'coupon_creator' )
			);				
			
			//Filter Option Fields
			if(has_filter('cctor_option_filter')) {
				$this->options = apply_filters('cctor_option_filter', $this->options);
			}
			
		}	// End get_options()
		
	/***************************************************************************/	

		/*
		* Coupon Creator Initialize Options and Default Values
		* @version 1.80
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
		* @version 1.80
		*/		
		public function register_options() {
			
			//Get Tab Sections to Register
			$this->get_sections();
			
			register_setting( 'coupon_creator_options', 'coupon_creator_options', array ( &$this, 'validate_options' ) );
			
			foreach ( $this->sections as $slug => $title ) {
				if ( $slug == 'about' )
					add_settings_section( $slug, $title, array( &$this, 'display_about_section' ), 'coupon-options' );
				elseif ( $slug == 'pro' )
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
		* @version 1.80
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
							$input[$id] = str_replace(" ", "-",  strtolower($input[$id]));
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
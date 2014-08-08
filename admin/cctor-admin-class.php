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
		* Admin Bootstrap
		* @version 1.70
		*/
		public static function bootstrap() {
			add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
			add_action( 'admin_init', array( __CLASS__, 'admin_upgrade' ) );
			add_action('admin_init', array( __CLASS__, 'cctor_flush_permalinks'));
			
			//Load Sanitize Functions
			Coupon_Creator_Plugin_Admin::include_file( 'cctor-sanitize.php' );
			
			//Load Coupon Options Class
			Coupon_Creator_Plugin_Admin::include_file( 'cctor-admin-options-class.php' );
			$coupon_settings = new Coupon_Creator_Plugin_Admin_Options();
			//Coupon_Creator_Plugin_Admin_Options::bootstrap();
			
			//Load Coupon Meta Box Class
			Coupon_Creator_Plugin_Admin::include_file( 'cctor-post-meta-box-class.php' );
			$coupon_meta_box = new Coupon_Creator_Meta_Box();
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
			add_action('media_buttons_context', array( __CLASS__, 'add_cc_coupon_button' ));
			//Add Options Link on Plugin Activation Page
			add_action('plugin_action_links', array( __CLASS__, 'plugin_setting_link' ) , 10, 2);							

		} //end admin_init
	/***************************************************************************/

	/*
	* Update Version Number Check
	* @version 1.70
	*/
	function admin_upgrade() {
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
	function cctor_flush_permalinks() {
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
	function plugin_setting_link($links, $file) {
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
			* Add Coupon Inserter Button Above WordPress Editor
			* @version 1.00
			*/
			public static function add_cc_coupon_button($context) {
			
				//add Content for inline popup for Coupon Inserter
				add_action('admin_footer', array( __CLASS__, 'add_coupon_inline_popup' ));
				
				//path to coupon icon
				$img = CCTOR_URL . 'admin/images/coupon_creator.png';
				//the id of the container I want to show in the popup
				$container_id = 'coupon_container';
				//our popup's title
				$title = 'Insert Coupon';
				
				// display ui button for 3.5 and greater
				$context .="<style>.cctor_insert_icon{
							background:url('{$img}') no-repeat top left;
							display: inline-block;
							height: 16px;
							margin: 0 2px 0 0;
							vertical-align: text-top;
							width: 16px;
							}
							.wp-core-ui a.cctor_insert_link{
							 padding-left: 0.4em;
							}
						 </style>
							<a class='thickbox button cctor_insert_link' id='add_jp_gallery'  title='{$title}' href='#TB_inline?width=640&inlineId={$container_id}'><span class='cctor_insert_icon'></span>Add Coupon</a>";
							
				  return $context;
				  
			} //End Insert Icon Creation
		
			/*
			* Coupon Inserter Popup Coding and Script
			* @version 1.00
			*/
			public static function add_coupon_inline_popup() { ?>
					<!--Script to insert Coupon ShortCode Into Editor -->
					<script>
						//Insert Shortcode into Editor
						function InsertCoupon(){
							var coupon_id = jQuery("#coupon_select").val();
								if (coupon_id == "loop") {
									var coupon_shortcode = "coupon";
									var coupon_category = jQuery("#coupon_category_select").val();
									var coupon_category = " category=\""+ coupon_category + "\" ";
								} else {
									var coupon_shortcode = "coupon";
									var coupon_category = "";
								}
							var coupon_name = jQuery("#coupon_select option[value='" + coupon_id + "']").text().replace(/[\[\]]/g, '');
							var cctor_align = jQuery("#coupon_align").val();
							var coupon_align = jQuery("#coupon_align option[value='" + cctor_align + "']").text().replace(/[\[\]]/g, '');
							window.send_to_editor("[" + coupon_shortcode + " couponid=\"" + coupon_id + "\"" + coupon_category + " coupon_align=\"" + cctor_align + "\" name=\"" + coupon_name + "\"]");
						}

						//Toggle Category Input when Loop Selected
						function show_category() {
							var coupon_select = document.getElementById("coupon_select");
							var coupon_selection = coupon_select.options[coupon_select.selectedIndex].value;

							var category_select = document.getElementById("coupon_category_select_container");

							if (coupon_selection == "loop") {
								category_select.style.visibility = "visible";
							}
							else {
								category_select.style.visibility = "hidden";
							}
						}
					</script>

					<style>
						#coupon_category_select_container {
							visibility: hidden;
						}
					</style>

				<!--Start Thickbox Popup -->
				<div id="coupon_container" style="display:none;">
				  <h2>Coupon Creator Shortcode:</h2>
					<?php
						 $querycoupon = new WP_Query( 'post_status=publish&post_type=cctor_coupon&posts_per_page=-1' );
						// The Coupon Loop
						if ($querycoupon->have_posts()) {
					?>
					<div style="padding:15px;">
						<!--Create a Select Box with Coupon Titles -->
						<label for="coupon_select">Select Loop or an Individual Coupon</label>
							<select name="coupon_select_box" id="coupon_select" onchange="show_category()">
								<option value="#" ></option>
								<option value="loop" >Coupon Loop</option>
								<?php
								while ($querycoupon->have_posts()) {
								$querycoupon->the_post(); ?>
									<!--Adding the Value as ID for the Shortcode and the Title for Humans-->
									<option value="<?php the_ID(); ?>" ><?php the_title(); ?></option>

								<?php } ?>
							</select><br> <!--End Select Box Coupons-->

						<!--Create a Select Box for Categories -->
						<div id="coupon_category_select_container"><br>
							<label for="coupon-categories">Select a Coupon Category to use in the Loop</label>
								<select id="coupon_category_select" name="coupon_category_select">
								<option value="#" ></option>
								 <option value="">All Categories</option>
								 <?php
									$values = array(
									  'orderby' => 'name',
									  'order' => 'ASC',
									  'echo' => 1,
									  'selected' => $kat = get_query_var( 'cat' ),
									  'name' => 'cat',
									  'id' => '',
									  'taxonomy' => 'cctor_coupon_category'
									 );
								  $categories = get_categories($values);
								  foreach ($categories as $category) {
									$option = '<option value="'.$category->name.'">';
									$option .= $category->cat_name;
									$option .= '</option>';
									echo $option;
								  }
								 ?>
								</select> <!--End Select Box Categories-->
						</div><br>
						<!--Create a Select Box for Align -->
						<label for="coupon_align">Select How to Align the Coupon(s)</label>
							<select name="coupon_align_select_box" id="coupon_align">
								 <option value="cctor_alignnone">None</option>
								 <option value="cctor_alignleft">Align Left</option>
								 <option value="cctor_alignright">Align Right</option>
								 <option value="cctor_aligncenter">Align Center</option>
							</select><br> <!--End Select Box Align -->
					</div> <!--End Div -->
					<br/>

					<div style="padding:15px;">
						<!--Insert into Editor Button that Calls Script-->
						<input type="button" id="coupon-submit" onclick="InsertCoupon();" class="button-primary" value="Insert Coupon" name="submit" />
					</div>

					<?php }  ?>
				</div> <!--End #coupon_container -->
			<?php }
			
	/***************************************************************************/

		/*
		* Register and Enqueue Style and Scripts
		* @version 1.80
		*/
		public static function enqueue_style_scripts( ) {
				
			$screen = get_current_screen();
			
			
			if ( 'cctor_coupon' == $screen->id || 'cctor_coupon_page_coupon_creator_settings' == $screen->id ) {
				
				//Styles
				//Date Picker CSS
				$coupon_creator_admin = CCTOR_PATH.'admin/css/admin.css';
				wp_enqueue_style( 'coupon_creator_admin', CCTOR_URL . 'admin/css/admin.css', false, filemtime($coupon_creator_admin));
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

		/*
		* Include Admin File
		* @version 1.70
		* @param string $file
		*/
		public static function include_file( $file ) {
			include CCTOR_PATH . 'admin/' . $file;
		}

		/***************************************************************************/

	} //end Coupon_Creator_Plugin_Admin Class
	
} // class_exists( 'Coupon_Creator_Plugin_Admin' )
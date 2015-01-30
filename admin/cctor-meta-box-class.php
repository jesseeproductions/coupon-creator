<?php
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

if ( ! class_exists( 'Coupon_Creator_Meta_Box' ) ) {
	/*
	* Coupon_Creator_Meta_Box
	* since 1.80
	*/
	class Coupon_Creator_Meta_Box {

		/*
		* Construct
		* since 1.80
		*/
		public function __construct() {
			//Setup Coupon Meta Boxes
			add_action( 'add_meta_boxes', array( __CLASS__, 'cctor_add_meta_boxes' ) );
			//Save Meta Boxes Data
			add_action( 'save_post', array( __CLASS__, 'cctor_save_coupon_creator_meta' ), 10, 2 );
			//Coupon Expiration Information
			add_action( 'edit_form_after_title',  array( __CLASS__, 'cctor_information_box' ) , 10 );
		}
	/***************************************************************************/

		/*
		* Coupon Creator Meta Sections
		* since 1.90
		*/
		public static function get_cctor_tabs() {

			$meta_tabs = array();

			//Coupon Creator Options Tabs
			$meta_tabs['content']     	= __( 'Content', 'coupon_creator' );
			$meta_tabs['style']   		= __( 'Style', 'coupon_creator' );
			$meta_tabs['expiration'] 	= __( 'Expiration', 'coupon_creator' );
			$meta_tabs['image_coupon'] 	= __( 'Image Coupon', 'coupon_creator' );
			$meta_tabs['help'] 	= __( 'Help', 'coupon_creator' );

			//Filter Option Tabs
			if(has_filter('cctor_filter_meta_tabs')) {
				$meta_tabs = apply_filters('cctor_filter_meta_tabs', $meta_tabs);
			}

			return $meta_tabs;
		}
	/***************************************************************************/

		/*
		* Live Preview Below Title
		* since 1.00
		*/
		public static function cctor_meta_expiration_check($coupon_id) {

			//Ignore Expiration Value
			$ignore_expiration = get_post_meta($coupon_id, 'cctor_ignore_expiration', true);

			//Return If Not Passed Expiration Date
			$expiration = cctor_expiration_and_current_date($coupon_id);

			//Enable Filter to stop coupon from showing
			$show_coupon_check = false;

			$show_coupon_check = apply_filters('cctor_filter_meta_show_coupon_check', $show_coupon_check, $coupon_id);

			if (($expiration || $ignore_expiration == 1) && !$show_coupon_check) {

				return true;

			}	else {

				return false;

			}
		}
	/***************************************************************************/

		/*
		* Live Preview Below Title
		* since 1.00
		*/
		public static function cctor_information_box() {

			global $pagenow, $post, $typenow;

		if (empty($typenow) && !empty($_GET['post'])) {
			$post = get_post($_GET['post']);
			$typenow = $post->post_type;
		}

			//Display Message on Coupon Edit Screen, but not on a new coupon until saved
			if($pagenow !='post-new.php' && $typenow=='cctor_coupon'){

				$coupon_id = $post->ID;
							
				if(Coupon_Creator_Meta_Box::cctor_meta_expiration_check($coupon_id)) {

					echo '<div class="cctor-meta-bg cctor-message"><div>'.__('This Coupon is Showing', 'coupon_creator' ) .'</div></div>';


				} else {

					echo '<div class="cctor-meta-bg cctor-error"><p>'.__('This Coupon is not Showing', 'coupon_creator' ).'</p></div>';

				}

				//Check for Ignore Expiration
				$ignore_expiration = get_post_meta($post->ID, 'cctor_ignore_expiration', true);

				if($ignore_expiration) {
					$ignore_expiration_msg = __('Ignore Coupon Expiration is On', 'coupon_creator' );
				} else {
					$ignore_expiration_msg = __('Ignore Coupon Expiration is Off', 'coupon_creator' );
				}

				//Check for Expiration
				$expirationco = get_post_meta($coupon_id, 'cctor_expiration', true);
				$expiration['expiration'] = strtotime($expirationco);

				//Date Format
				if ($expirationco) { // Only Display Expiration if Date
					$daymonth_date_format = get_post_meta($coupon_id, 'cctor_date_format', true); //Date Format

					if ($daymonth_date_format == 1 ) { //Change to Day - Month Style
					$expirationco = date("d/m/Y", $expiration['expiration']);
					}
				}

				//Blog Time According to WordPress
				$cc_blogtime = current_time('mysql');
				list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = preg_split( '([^0-9])', $cc_blogtime );
				$expiration['today'] = strtotime($today_month."/".$today_day."/". $today_year);

				$expired_msg = "";

				if (!$ignore_expiration) {
					if ($expiration['expiration'] >= $expiration['today']) {
						$expired_msg = '<div>'. __('This Coupon Expires On ', 'coupon_creator' ) .$expirationco.'</div>';
					} else {
						$expired_msg = '<div>'. __('This Coupon Expired On ', 'coupon_creator' ) .$expirationco.'</div>';
					}
				}
				echo '<div class="cctor-meta-bg cctor-message">';

				echo '<div>'. $ignore_expiration_msg .'</div>';


				echo  $expired_msg;

				//Hook Meta Box Message
				do_action( 'cctor_meta_message', $coupon_id);

				echo '</div>';

			}
		}

	/***************************************************************************/

		/*
		* Add Meta Boxes
		* since 1.80
		*/
		public static function cctor_add_meta_boxes() {
			global $pagenow, $typenow;
		if (empty($typenow) && !empty($_GET['post'])) {
			$post = get_post($_GET['post']);
			$typenow = $post->post_type;
		}

		if (is_admin() && $pagenow=='post-new.php' OR $pagenow=='post.php' && $typenow=='cctor_coupon') {
					add_meta_box(
						'coupon_creator_meta_box', // id
						__( 'Coupon Fields', 'coupon_creator' ), // title
						array( __CLASS__, 'cctor_show_meta_box' ), // callback
						'cctor_coupon', // post_type
						'normal', // context
						'high' // priority
					);

					if($pagenow !='post-new.php'){
						add_meta_box(
							'coupon_creator_shortcode', // id
							__( 'Coupon Shortcode', 'coupon_creator' ), // title
							array( __CLASS__, 'cctor_show_coupon_shortcode' ), // callback
							'cctor_coupon', // post_type
							'side' // context
						);
					}

					//Hook for More Meta Boxes
					do_action( 'cctor_add_meta_box');

			}
		}

		/***************************************************************************/

		/*
		* Load Meta Box Functions
		* since 1.80
		* @param stdClass $post
		*/
		public static function cctor_show_meta_box( $post, $metabox  ) {

				wp_nonce_field( 'coupon_creator_save_post', 'coupon_creator_nonce' );

				//Get Tab Sections
				$meta_tabs = self::get_cctor_tabs();

				//Get Meta Boxes
				$coupon_creator_meta_fields = self::cctor_metabox_options();

				//Create Array of Tabs and Localize to Meta Script
				$tabs_array = array();

				foreach ( $meta_tabs as $tab_slug => $tab ) {
					$tabs_array[$tab] = $tab_slug;
				}

				$tabs_json_array = json_encode($tabs_array);

				$tabs_params = array(
					'tabs_arr' => $tabs_json_array,
				);

				wp_localize_script('cctor_coupon_meta_js', 'cctor_coupon_meta_js_vars', $tabs_params);

				ob_start(); ?>

			<div class="cctor-tabs">
				<ul class="cctor-tabs-nav">

					<?php //Create Tabs
					foreach ( $meta_tabs as $tab_slug => $tab )
						echo '<li><a href="#' . $tab_slug . '">' . $tab . '</a></li>';
					?>
				</ul>

			<?php foreach  ( $meta_tabs as $tab_slug => $tab ) { ?>

			<div class="coupon-section-fields form-table">

				<h3><?php echo $tab; ?></h3>

				<?php foreach ($coupon_creator_meta_fields as $field) {

					if ($field['section'] == $metabox['id'] && $field['tab'] == $tab_slug) :

					// get value of this field if it exists for this post
					$meta = get_post_meta($post->ID, $field['id'], true); ?>

					<div class="cctor-meta-field-wrap field-wrap-<?php echo $field['type']; ?> field-wrap-<?php echo $field['id']; ?>">

						<?php if (isset($field['label'])) { ?>

						<div class="cctor-meta-label label-<?php echo $field['type']; ?> label-<?php echo $field['id']; ?>">
							<label for="<?php echo $field['id']; ?>"><?php echo $field['label']; ?></label>
						</div>

						<?php } ?>

						<div class="cctor-meta-field field-<?php echo $field['type']; ?> field-<?php echo $field['id']; ?>">

							<?php switch($field['type']) {

								case 'heading':?>

									<h4 class="coupon-heading"><?php echo $field['desc']; ?></h4>

								<?php break;

								// text
								case 'text':?>
									<?php if (  isset($field['alert']) && $field['alert'] != '' && cctor_options($field['condition']) == 1 )
											echo '<div class="cctor-error">&nbsp;&nbsp;' . $field['alert'] . '</div>';
									?>
									<input type="text" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" value="<?php echo esc_attr($meta); ?>" size="30" />
										<br /><span class="description"><?php echo $field['desc']; ?></span>

								<?php break;
								// textarea
								case 'textarea': ?>

									<textarea name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" cols="60" rows="4"><?php echo wp_htmledit_pre($meta); ?></textarea>
										<br /><span class="description"><?php echo $field['desc']; ?></span>

								<?php break;
								// textarea
								case 'textarea_w_tags': ?>

									<textarea name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" cols="60" rows="4"><?php echo wp_htmledit_pre( $meta ); ?></textarea>
										<br /><span class="description"><?php echo $field['desc']; ?></span>

								<?php break;
								// checkbox
								case 'checkbox': ?>

									<input type="checkbox" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" <?php echo checked( $meta, 1, false ); ?>/><label for="<?php echo $field['id']; ?>"><?php echo $field['desc']; ?></label>

								<?php break;

								case 'select':
								
								//Find Current Selected Value or use Default
								if ( $meta ) {
									$selected = $meta;
								} else {
									$selected = $field['value'];
								}

								?>
									<select id="<?php echo $field['id']; ?>" class="select <?php echo $field['id']; ?>" name="<?php echo $field['id']; ?>">

									<?php foreach ( $field['choices'] as $value => $label ) {

										echo '<option value="'. esc_attr( $value ).'"'.selected( $value , $selected ).'>'. $label .'</option>';

									}?>
									</select>
									<span class="description"><?php echo $field['desc']; ?></span>

								<?php	break;
								// image using Media Manager from WP 3.5 and greater
								case 'image': ?>

									<?php //Check existing field and if numeric
										$image = "";

									if (is_numeric($meta)) {
										$image = wp_get_attachment_image_src($meta, 'medium');
										$image = $image[0];
										$image = '<div style="display:none" id="'.$field['id'].'" class="cctor_coupon_default_image cctor_coupon_box">'.$field['image'].'</div> <img src="'. $image .'" id="'. $field['id'] .'" class="cctor_coupon_image cctor_coupon_box_img" />';
									} else {
										$image = '<div style="display:block" id="'.$field['id'].'" class="cctor_coupon_default_image cctor_coupon_box">'.$field['image'].'</div> <img style="display:none" src="" id="'. $field['id'] .'" class="cctor_coupon_image cctor_coupon_box_img" />';
									}?>

											<?php echo $image; ?><br />
											<input name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" type="hidden" class="upload_coupon_image" type="text" size="36" name="ad_image" value="<?php echo esc_attr( $meta ); ?>" />
											<input id="<?php echo $field['id']; ?>" class="coupon_image_button" type="button" value="Upload Image" />
											<small> <a href="#" id="<?php echo $field['id']; ?>" class="cctor_coupon_clear_image_button">Remove Image</a></small>
											<br /><span class="description"><?php echo $field['desc']; ?></span>

								<?php break;
								// color
								case 'color': ?>
									<?php //Check if Values and If None, then use default
										if (!$meta) {
											$meta = $field['value'];
										}
									?>
									<input class="color-picker" type="text" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" value="<?php echo esc_attr( $meta ); ?>" data-default-color="<?php echo $field['value']; ?>"/>
										<br /><span class="description"><?php echo $field['desc']; ?></span>

								<?php break;
								 // date
								 case 'date': 
								 
								//Blog Time According to WordPress
								$cctor_todays_date = "";
								if ($field['id'] == "cctor_expiration" ) {
									$cc_blogtime = current_time('mysql');
								
									list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = preg_split( '([^0-9])', $cc_blogtime 
									);
								
									if ( cctor_options('cctor_default_date_format') == 1 || $meta == 1 ) {
										$today_first = $today_day;
										$today_second = $today_month;
									} else {
										$today_first = $today_month;
										$today_second = $today_day;									
									}
			
									$cctor_todays_date = '<span class="description">'. __( 'Today\'s Date is ','coupon_creator' ) . $today_first.'/'.$today_second.'/'. $today_year . '</span>';
								}
								?>
	
									<input type="text" class="datepicker" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" value="<?php echo esc_attr( $meta ); ?>" size="10" />
									<br /><span class="description"><?php echo $field['desc']; ?></span>
									<?php echo $cctor_todays_date; ?>
									
								<?php break;
									// Videos
								 case 'cctor_support':?>

									<?php echo Coupon_Creator_Plugin_Admin::get_cctor_support_core_infomation(); 
										
										  echo Coupon_Creator_Plugin_Admin::get_cctor_support_core_contact();
									?>

								<?php break;

							} //end switch

						if(has_filter('cctor_filter_meta_cases')) {
							// this adds any addon fields (from plugins) to the array
							echo apply_filters('cctor_filter_meta_cases', $field, $meta, $post);
						} ?>

					</div> <!-- end .cctor-meta-field.field-<?php echo $field['type']; ?>.field-<?php echo $field['id']; ?> -->

				</div> <!-- end .cctor-meta-field-wrap.field-wrap-<?php echo $field['type']; ?>.field-wrap-<?php echo $field['id']; ?>	-->

			<?php
				endif; //end if in section check

			} // end foreach fields?>

			</div>	<!-- end .coupon-section-fields.form-table -->

			<?php } // end foreach tabs?>

		</div>	<!-- end .cctor-tabs -->

		<?php
				echo ob_get_clean();
		}

		/***************************************************************************/

		/*
		* Load Meta Box Functions
		* since 1.90
		* @param stdClass $post
		*/
		public static function cctor_show_coupon_shortcode( $post, $metabox  ) {
			?><p class="shortcode">
			<?php  _e( 'Place this coupon in your posts, pages, custom post types, or widgets by using the shortcode below:<br><br>', 'coupon_creator' ); ?>
			<code>[coupon couponid="<?php echo $post->ID; ?>" name="<?php echo $post->post_title; ?>"]</code>
			</p><?php

		}
		/***************************************************************************/

		/*
		* Load Meta Box Functions
		* since 1.80
		*/
		public static function cctor_metabox_options() {

			// Field Array  cctor_
			$prefix = 'cctor_';

				//Content
				$coupon_creator_meta_fields[$prefix . 'heading_deal'] = array(
					'id' => $prefix . 'heading_deal',
					'title'   => '',
					'desc'    =>  __( 'Coupon Deal','coupon_creator' ),
					'type'    => 'heading',
					'section' => 'coupon_creator_meta_box',
					'tab' => 'content'
				);
				$coupon_creator_meta_fields[$prefix . 'amount'] =	array(
					'label' => __('Deal', 'coupon_creator' ),
					'desc' => __('Enter coupon deal - 30% OFF! or Buy One Get One Free, etc...', 'coupon_creator' ),
					'id' => $prefix . 'amount',
					'type'  => 'text',
					'alert' => '',
					'section' => 'coupon_creator_meta_box',
					'tab' => 'content'
				);		
				$coupon_creator_meta_fields[$prefix . 'deal_display'] =	array(
					'label' => '',
					'desc' => '',
					'id' => $prefix . 'deal_display',
					'type'  => '',
					'alert' => '',
					'section' => 'coupon_creator_meta_box',
					'tab' => 'content'
				);				
				$coupon_creator_meta_fields[$prefix . 'heading_terms'] = array(
					'id' => $prefix . 'heading_terms',
					'title'   => '',
					'desc'    =>  __( 'Coupon Terms','coupon_creator' ),
					'type'    => 'heading',
					'section' => 'coupon_creator_meta_box',
					'tab' => 'content'
				);
				$coupon_creator_meta_fields[$prefix . 'description'] =	array(
					'label' => __('Terms', 'coupon_creator' ),
					'desc' => __('Enter the terms of the discount', 'coupon_creator' ),
					'id' => $prefix . 'description',
					'type'  => 'textarea_w_tags',
					'section' => 'coupon_creator_meta_box',
					'tab' => 'content'
				);

				//Style
				$coupon_creator_meta_fields[$prefix . 'heading_color'] = array(
					'id' => $prefix . 'heading_color',
					'title'   => '',
					'desc'    =>  __( 'Discount Field Colors','coupon_creator' ),
					'type'    => 'heading',
					'section' => 'coupon_creator_meta_box',
					'tab' => 'style'
				);
				$coupon_creator_meta_fields[$prefix . 'colordiscount'] =	array(
					'label' => __('Discount Background Color', 'coupon_creator' ),
					'desc'  => __('Choose background color', 'coupon_creator' ),
					'id' => $prefix . 'colordiscount',
					'type' => 'color', // color
					'value' => cctor_options('cctor_discount_bg_color'),
					'section' => 'coupon_creator_meta_box',
					'tab' => 'style'
				);
				$coupon_creator_meta_fields[$prefix . 'colorheader'] =	array(
					'label' => __('Discount Text Color', 'coupon_creator' ),
					'desc'  => __('Choose color for discount text', 'coupon_creator' ),
					'id' => $prefix . 'colorheader',
					'type' => 'color', // color
					'value' => cctor_options('cctor_discount_text_color'),
					'section' => 'coupon_creator_meta_box',
					'tab' => 'style'
				);

				//Inside Border
				$coupon_creator_meta_fields[$prefix . 'heading_inside_color'] = array(
					'id' => $prefix . 'heading_inside_color',
					'title'   => '',
					'desc'    =>  __( 'Inner Border','coupon_creator' ),
					'type'    => 'heading',
					'section' => 'coupon_creator_meta_box',
					'tab' => 'style'
				);
				$coupon_creator_meta_fields[$prefix . 'bordercolor'] =	array(
					'label' => __('Inside Border Color', 'coupon_creator' ),
					'desc'  => __('Choose inside border color', 'coupon_creator' ),
					'id' => $prefix . 'bordercolor',
					'type' => 'color', // color
					'value' => cctor_options('cctor_border_color'),
					'section' => 'coupon_creator_meta_box',
					'tab' => 'style'
				);
				//Expiration
				$coupon_creator_meta_fields[$prefix . 'heading_expiration'] = array(
					'id' => $prefix . 'heading_expiration',
					'title'   => '',
					'desc'    =>  __( 'Expiration','coupon_creator' ),
					'type'    => 'heading',
					'section' => 'coupon_creator_meta_box',
					'tab' => 'expiration'
				);
				$coupon_creator_meta_fields[$prefix . 'expiration'] =	array(
					'label' => __('Expiration Date', 'coupon_creator' ),
					'id' => $prefix . 'expiration',
					'desc' => __('The coupon will not display without the date and will not display on your site after the date', 'coupon_creator' ),
					'type'  => 'date',
					'section' => 'coupon_creator_meta_box',
					'tab' => 'expiration'
				);
				$coupon_creator_meta_fields[$prefix . 'date_format'] =	array(
					'label'=> __('Date Format', 'coupon_creator' ),
					'desc'  => __('Choose the date format', 'coupon_creator' ),
					'id'    => $prefix . 'date_format',
					'value' => cctor_options('cctor_default_date_format'),
					'type'    => 'select',
					'choices' => array(
						'0' =>  __( 'Month First - MM/DD/YYYY', 'coupon_creator' ),
						'1' => __( 'Day First - DD/MM/YYYY', 'coupon_creator' )
					),
					'section' => 'coupon_creator_meta_box',
					'tab' => 'expiration'
				);
				$coupon_creator_meta_fields[$prefix . 'ignore_expiration'] =	array(
					'label'=> __('Ignore Expiration Date', 'coupon_creator' ),
					'desc'  => __('Check this to ignore the expiration date', 'coupon_creator' ),
					'id'    => $prefix . 'ignore_expiration',
					'type'  => 'checkbox',
					'section' => 'coupon_creator_meta_box',
					'tab' => 'expiration'
				);

				//Image Coupon
				$coupon_creator_meta_fields[$prefix . 'image'] =	array(
					'label'  => '',
					'desc'  => __('Upload an image to use as the entire coupon - Current image size is for 390 pixels in width with auto height', 'coupon_creator' ),
					'id'    => $prefix . 'image',
					'type'  => 'image',
					'image'  => 'Image Coupon',
					'section' => 'coupon_creator_meta_box',
					'tab' => 'image_coupon'
				);

				//Help
				$coupon_creator_meta_fields[$prefix . 'videos'] =	array(
					'label'  => __( '', 'coupon_creator' ),
					'id'    => $prefix . 'videos',
					'type'  => 'cctor_support',
					'section' => 'coupon_creator_meta_box',
					'tab' => 'help'
				);

			if(has_filter('cctor_filter_meta_fields')) {
				//Add Fields to the Coupon Creator Meta Box
				$coupon_creator_meta_fields = apply_filters('cctor_filter_meta_fields', $coupon_creator_meta_fields);
			}

			return $coupon_creator_meta_fields;
		}

		/***************************************************************************/
		/*
		* Save Meta Boxes
		* since 1.80
		*/
		public static function cctor_save_coupon_creator_meta( $post_id, $post ) {
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
				return;

			if ( !isset( $_POST['coupon_creator_nonce'] ) )
				return;

			if ( !wp_verify_nonce( $_POST['coupon_creator_nonce'], 'coupon_creator_save_post' ) )
				return;

			if ( !current_user_can( 'edit_post', $post->ID ) )
				return;


				// Save data
				//Get Meta Fields
				$coupon_creator_meta_fields = self::cctor_metabox_options();

				//For Each Field Sanitize the Post Data
				foreach ( $coupon_creator_meta_fields as $option ) {

					//Hook Meta Box Save
					do_action( 'cctor_save_meta_fields',$post_id, $option);

					//If No CheckBox Sent, then delete meta
					if ($option['type'] == 'checkbox' ) {

						$coupon_meta_checkbox = get_post_meta($post_id, $option['id'], true);

						if ($coupon_meta_checkbox && ! isset( $_POST[$option['id']] ) ) {
							delete_post_meta($post_id,  $option['id']);
						}

					}

					// Final Check if value should be saved then sanitize and save
					if(isset($_POST[$option['id']])){
						if ( has_filter( 'cctor_sanitize_' . $option['type'] ) ) {

							$old = get_post_meta($post_id, $option['id'], true);

							$new = $_POST[$option['id']];

							if ( !is_null($new) && $new != $old) {
								update_post_meta($post_id, $option['id'], apply_filters( 'cctor_sanitize_' . $option['type'], $new, $option ));
							} elseif ('' == $new && $old) {
								delete_post_meta($post_id, $option['id'], $old);
							}

						}
					}

				}
		}

			/***************************************************************************/

	} //end Coupon_Creator_Meta_Box Class

} // class_exists( 'Coupon_Creator_Meta_Box' )
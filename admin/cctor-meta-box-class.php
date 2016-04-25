<?php
//If Direct Access Kill the Script
if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ ) {
	die( 'Access denied.' );
}

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

			//Coupon Expiration Class
			add_action( 'init', array( 'CCtor_Expiration_Class', 'meta_info' ) );

			//Coupon Expiration Information
			add_action( 'edit_form_after_title', array( __CLASS__, 'coupon_information_box' ) );
			//JS Error Check
			add_action( 'cctor_meta_message', array( __CLASS__, 'get_js_error_check_msg' ) );
		}

		/***************************************************************************/


		public static function coupon_information_box() {

			global $pagenow, $post, $typenow;
			$coupon_id = $post->ID;

			if ( empty( $typenow ) && ! empty( $_GET['post'] ) ) {
				$post    = get_post( $_GET['post'] );
				$typenow = $post->post_type;
			}

			//Display Message on Coupon Edit Screen, but not on a new coupon until saved
			if ( $pagenow != 'post-new.php' && $typenow == 'cctor_coupon' ) {

				//Hook Meta Box Message
				do_action( 'cctor_meta_message', $coupon_id );

			}

		}

		/***************************************************************************/

		/**
		 * Javascript Conflict Message
		 *
		 * @return string
		 */
		public static function get_js_error_check_msg() {

			$js_troubleshoot_url = 'http://cctor.link/R7KRa';

			$js_msg = '<div class="javascript-conflict cctor-error"><p>' . sprintf( __( 'There maybe a javascript conflict preventing some features from working.  <a href="%s" target="_blank" >Please check this guide to narrow down the cause.</a>', 'coupon-creator' ), esc_url( $js_troubleshoot_url ) ) . '</p></div>';

			return $js_msg;

		}

		/***************************************************************************/

		/*
		* Coupon Creator Meta Sections
		* since 1.90
		*/
		public static function get_cctor_tabs() {

			$meta_tabs = array();

			//Coupon Creator Options Tabs
			$meta_tabs['content']      = __( 'Content', 'coupon-creator' );
			$meta_tabs['style']        = __( 'Style', 'coupon-creator' );
			$meta_tabs['expiration']   = __( 'Expiration', 'coupon-creator' );
			$meta_tabs['image_coupon'] = __( 'Image Coupon', 'coupon-creator' );
			$meta_tabs['help']         = __( 'Help', 'coupon-creator' );
			! defined( 'CCTOR_HIDE_UPGRADE' ) || ! CCTOR_HIDE_UPGRADE ? $meta_tabs['pro'] = __( 'Upgrade to Pro', 'coupon-creator' ) : '';

			//Filter Option Tabs
			if ( has_filter( 'cctor_filter_meta_tabs' ) ) {

				/**
				 * Filter the Coupon Creator Meta Tab Header
				 *
				 *
				 * @param array $meta_tabs an array of tab headings.
				 *
				 */
				$meta_tabs = apply_filters( 'cctor_filter_meta_tabs', $meta_tabs );
			}

			return $meta_tabs;
		}
		/***************************************************************************/

		/*
		* Add Meta Boxes
		* since 1.80
		*/
		public static function cctor_add_meta_boxes() {
			global $pagenow, $typenow;
			if ( empty( $typenow ) && ! empty( $_GET['post'] ) ) {
				$post    = get_post( $_GET['post'] );
				$typenow = $post->post_type;
			}

			if ( is_admin() && $pagenow == 'post-new.php' OR $pagenow == 'post.php' && $typenow == 'cctor_coupon' ) {
				add_meta_box( 'coupon_creator_meta_box', // id
					__( 'Coupon Fields', 'coupon-creator' ), // title
					array( __CLASS__, 'cctor_show_meta_box' ), // callback
					'cctor_coupon', // post_type
					'normal', // context
					'high' // priority
				);

				if ( $pagenow != 'post-new.php' ) {
					add_meta_box( 'coupon_creator_shortcode', // id
						__( 'Coupon Shortcode', 'coupon-creator' ), // title
						array( __CLASS__, 'cctor_show_coupon_shortcode' ), // callback
						'cctor_coupon', // post_type
						'side' // context
					);
				}

				//Hook for More Meta Boxes
				do_action( 'cctor_add_meta_box' );

			}
		}

		/***************************************************************************/

		/*
		* Load Meta Box Functions
		* since 1.80
		* @param stdClass $post
		*/
		public static function cctor_show_meta_box( $post, $metabox ) {

			wp_nonce_field( 'coupon_creator_save_post', 'coupon_creator_nonce' );

			//Set for WP 4.3 and replacing wp_htmledit_pre
			global $wp_version;
			$cctor_required_wp_version = '4.3';

			//Get Tab Sections
			$meta_tabs = self::get_cctor_tabs();

			//Get Meta Boxes
			$coupon_creator_meta_fields = self::cctor_metabox_options();

			//Create Array of Tabs and Localize to Meta Script
			$tabs_array = array();

			foreach ( $meta_tabs as $tab_slug => $tab ) {
				$tabs_array[ $tab ] = $tab_slug;
			}

			$tabs_json_array = json_encode( $tabs_array );

			//Detect if we saved or tried to save to set the current tab.
			global $message;
			$cctor_coupon_updated = $message;

			$coupon_id = isset( $_GET['post'] ) ? $_GET['post'] : '';

			$cctor_tabs_variables = array(
				'tabs_arr'             => $tabs_json_array,
				'cctor_coupon_updated' => $cctor_coupon_updated,
				'cctor_coupon_id'      => $coupon_id,
			);

			wp_localize_script( 'cctor_coupon_meta_js', 'cctor_coupon_meta_js_vars', $cctor_tabs_variables );

			ob_start(); ?>

			<div class="cctor-tabs">
				<ul class="cctor-tabs-nav">

					<?php //Create Tabs
					foreach ( $meta_tabs as $tab_slug => $tab ) {
						echo '<li><a href="#' . $tab_slug . '">' . $tab . '</a></li>';
					}
					?>
				</ul>

				<?php foreach ( $meta_tabs as $tab_slug => $tab ) { ?>

					<div class="coupon-section-fields form-table">

						<h3 class="cctor-tab-heading-<?php echo $tab_slug; ?>"><?php echo $tab; ?></h3>

						<?php foreach ( $coupon_creator_meta_fields as $field ) {

							if ( $field['type'] && $field['section'] == $metabox['id'] && $field['tab'] == $tab_slug ) :

								// get value of this field if it exists for this post
								$meta      = get_post_meta( $post->ID, $field['id'], true );

								//Wrap Class for Conditionals
								$wrapclass = isset( $field['wrapclass'] ) ? $field['wrapclass'] : '';
								?>

								<div
									class="cctor-meta-field-wrap field-wrap-<?php echo esc_html( $field['type'] ); ?> field-wrap-<?php echo esc_html( $field['id'] ); ?> <?php echo esc_html( $wrapclass ); ?>">

									<?php if ( isset( $field['label'] ) ) { ?>

										<div
											class="cctor-meta-label label-<?php echo $field['type']; ?> label-<?php echo $field['id']; ?>">
											<label
												for="<?php echo $field['id']; ?>"><?php echo $field['label']; ?></label>
										</div>

									<?php } ?>

									<div
										class="cctor-meta-field field-<?php echo $field['type']; ?> field-<?php echo $field['id']; ?>">

										<?php switch ( $field['type'] ) {

											case 'heading':
												?>

												<h4 class="coupon-heading"><?php echo $field['desc']; ?></h4>

												<?php break;

											case 'message':
												?>

												<span class="description"><?php echo $field['desc']; ?></span>

												<?php break;

											// text
											case 'text':
												?>
												<?php if ( isset( $field['alert'] ) && $field['alert'] != '' && cctor_options( $field['condition'] ) == 1 ) {
												echo '<div class="cctor-error">&nbsp;&nbsp;' . $field['alert'] . '</div>';
											}
												?>
												<input type="text" name="<?php echo $field['id']; ?>"
												       id="<?php echo $field['id']; ?>"
												       value="<?php echo esc_attr( $meta ); ?>" size="30"/>
												<br/><span class="description"><?php echo $field['desc']; ?></span>

												<?php break;
											// url
											case 'url':
												?>
												<input type="text" name="<?php echo $field['id']; ?>"
												       id="<?php echo $field['id']; ?>"
												       value="<?php echo esc_url( $meta ); ?>" size="30"/>
												<br/><span class="description"><?php echo $field['desc']; ?></span>

												<?php break;
											// textarea
											case 'textarea': ?>
												<?php if ( version_compare( $wp_version, $cctor_required_wp_version, '<' ) ) { ?>
													<textarea name="<?php echo $field['id']; ?>"
													          id="<?php echo $field['id']; ?>" cols="60"
													          rows="4"><?php echo wp_htmledit_pre( $meta ); ?></textarea>
													<br/><span class="description"><?php echo $field['desc']; ?></span>
												<?php } else { ?>
													<textarea name="<?php echo $field['id']; ?>"
													          id="<?php echo $field['id']; ?>" cols="60"
													          rows="4"><?php echo format_for_editor( $meta ); ?></textarea>
													<br/><span class="description"><?php echo $field['desc']; ?></span>
												<?php } ?>
												<?php break;

											// checkbox
											case 'checkbox':

												//Check for Default
												global $pagenow;
												$selected = '';
												if ( $meta ) {
													$selected = $meta;
												} elseif ( $pagenow == 'post-new.php' && isset( $field['value'] ) ) {
													$selected = $field['value'];
												}

												?>

												<input type="checkbox" name="<?php echo $field['id']; ?>"
												       id="<?php echo $field['id']; ?>" <?php echo checked( $selected, 1, false ); ?>/>
												<label
													for="<?php echo $field['id']; ?>"><?php echo $field['desc']; ?></label>

												<?php break;

											case 'select':

												//Check for Default
												global $pagenow;
												$selected = '';
												if ( $meta ) {
													$selected = $meta;
												} elseif ( $pagenow == 'post-new.php' ) {
													$selected = $field['value'];
												}

												?>
												<select id="<?php echo $field['id']; ?>"
												        class="select <?php echo $field['id']; ?>"
												        name="<?php echo $field['id']; ?>">

													<?php foreach ( $field['choices'] as $value => $label ) {

														echo '<option value="' . esc_attr( $value ) . '"' . selected( $value, $selected ) . '>' . $label . '</option>';

													} ?>
												</select>
												<span class="description"><?php echo $field['desc']; ?></span>

												<?php break;
											// image using Media Manager from WP 3.5 and greater
											case 'image': ?>

												<?php //Check existing field and if numeric
												$image = "";

												if ( is_numeric( $meta ) ) {
													$image = wp_get_attachment_image_src( $meta, 'medium' );
													$image = $image[0];
													$image = '<div style="display:none" id="' . $field['id'] . '" class="cctor_coupon_default_image cctor_coupon_box">' . $field['image'] . '</div> <img src="' . $image . '" id="' . $field['id'] . '" class="cctor_coupon_image cctor_coupon_box_img" />';
												} else {
													$image = '<div style="display:block" id="' . $field['id'] . '" class="cctor_coupon_default_image cctor_coupon_box">' . $field['image'] . '</div> <img style="display:none" src="" id="' . $field['id'] . '" class="cctor_coupon_image cctor_coupon_box_img" />';
												} ?>

												<?php echo $image; ?><br/>
												<input name="<?php echo $field['id']; ?>"
												       id="<?php echo $field['id']; ?>" type="hidden"
												       class="upload_coupon_image" type="text" size="36" name="ad_image"
												       value="<?php echo esc_attr( $meta ); ?>"/>
												<input id="<?php echo $field['id']; ?>" class="coupon_image_button"
												       type="button" value="Upload Image"/>
												<small><a href="#" id="<?php echo $field['id']; ?>"
												          class="cctor_coupon_clear_image_button">Remove Image</a>
												</small>
												<br/><span class="description"><?php echo $field['desc']; ?></span>

												<?php break;
											// color
											case 'color': ?>
												<?php //Check if Values and If None, then use default
												if ( ! $meta ) {
													$meta = $field['value'];
												}
												?>
												<input class="color-picker" type="text"
												       name="<?php echo $field['id']; ?>"
												       id="<?php echo $field['id']; ?>"
												       value="<?php echo esc_attr( $meta ); ?>"
												       data-default-color="<?php echo $field['value']; ?>"/>
												<br/><span class="description"><?php echo $field['desc']; ?></span>

												<?php break;
											// date
											case 'date':

												//Blog Time According to WordPress
												$cctor_todays_date = "";
												if ( $field['id'] == "cctor_expiration" ) {
													$cc_blogtime = current_time( 'mysql' );

													list( $today_year, $today_month, $today_day, $hour, $minute, $second ) = preg_split( '([^0-9])', $cc_blogtime );

													if ( cctor_options( 'cctor_default_date_format' ) == 1 || $meta == 1 ) {
														$today_first  = $today_day;
														$today_second = $today_month;
													} else {
														$today_first  = $today_month;
														$today_second = $today_day;
													}

													$cctor_todays_date = '<span class="description">' . __( 'Today\'s Date is ', 'coupon-creator' ) . $today_first . '/' . $today_second . '/' . $today_year . '</span>';
												}
												?>

												<input type="text" class="datepicker" name="<?php echo $field['id']; ?>"
												       id="<?php echo $field['id']; ?>"
												       value="<?php echo esc_attr( $meta ); ?>" size="10"/>
												<br/><span class="description"><?php echo $field['desc']; ?></span>
												<?php echo $cctor_todays_date; ?>

												<?php break;
											// Videos
											case 'cctor_support':
												?>

												<?php echo Coupon_Creator_Plugin_Admin::get_cctor_support_core_infomation();
												echo Coupon_Creator_Plugin_Admin::get_cctor_support_core_contact();
												?>

												<?php break;

											// Videos
											case 'cctor_pro':

												echo ! defined( 'CCTOR_HIDE_UPGRADE' ) || ! CCTOR_HIDE_UPGRADE ? Coupon_Creator_Plugin_Admin_Options::display_pro_section() : '';

												break;

										} //end switch

										if ( has_filter( 'cctor_filter_meta_cases' ) ) {
											/**
											 * Filter the cases for Coupon Creator Meta
											 *
											 * @param array $field current coupon meta field being displayed.
											 * @param array $meta  current value of meta saved.
											 * @param obj   $post  object of current post beign edited.
											 */
											echo apply_filters( 'cctor_filter_meta_cases', $field, $meta, $post );
										} ?>

									</div>
									<!-- end .cctor-meta-field.field-<?php echo $field['type']; ?>.field-<?php echo $field['id']; ?> -->

								</div> <!-- end .cctor-meta-field-wrap.field-wrap-<?php echo $field['type']; ?>.field-wrap-<?php echo $field['id']; ?>	-->

								<?php
							endif; //end if in section check

						} // end foreach fields?>

					</div>    <!-- end .coupon-section-fields.form-table -->

				<?php } // end foreach tabs?>

			</div>    <!-- end .cctor-tabs -->

			<?php
			echo ob_get_clean();
		}

		/***************************************************************************/

		/*
		* Load Meta Box Functions
		* since 1.90
		* @param stdClass $post
		*/
		public static function cctor_show_coupon_shortcode( $post, $metabox ) {
			?><p class="shortcode">
			<?php _e( 'Place this coupon in your posts, pages, custom post types, or widgets by using the shortcode below:<br><br>', 'coupon-creator' ); ?>
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
			$coupon_creator_meta_fields[ $prefix . 'heading_deal' ]  = array(
				'id'        => $prefix . 'heading_deal',
				'title'     => '',
				'desc'      => __( 'Coupon Deal', 'coupon-creator' ),
				'type'      => 'heading',
				'section'   => 'coupon_creator_meta_box',
				'tab'       => 'content',
				'wrapclass' => 'cctor-img-coupon'
			);
			$coupon_creator_meta_fields[ $prefix . 'amount' ]        = array(
				'label'     => __( 'Deal', 'coupon-creator' ),
				'desc'      => __( 'Enter coupon deal - 30% OFF! or Buy One Get One Free, etc...', 'coupon-creator' ),
				'id'        => $prefix . 'amount',
				'type'      => 'text',
				'alert'     => '',
				'section'   => 'coupon_creator_meta_box',
				'tab'       => 'content',
				'wrapclass' => 'cctor-img-coupon deal-display'
			);
			$coupon_creator_meta_fields[ $prefix . 'deal_display' ]  = array(
				'id'        => $prefix . 'deal_display',
				'type'      => '',
				'section'   => 'coupon_creator_meta_box',
				'tab'       => 'content',
				'wrapclass' => 'cctor-img-coupon'
			);
			$coupon_creator_meta_fields[ $prefix . 'heading_terms' ] = array(
				'id'        => $prefix . 'heading_terms',
				'title'     => '',
				'desc'      => __( 'Coupon Terms', 'coupon-creator' ),
				'type'      => 'heading',
				'section'   => 'coupon_creator_meta_box',
				'tab'       => 'content',
				'wrapclass' => 'cctor-img-coupon'
			);
			$coupon_creator_meta_fields[ $prefix . 'description' ]   = array(
				'label'     => __( 'Terms', 'coupon-creator' ),
				'desc'      => __( 'Enter the terms of the discount', 'coupon-creator' ),
				'id'        => $prefix . 'description',
				'type'      => 'textarea',
				'class'     => 'code',
				'section'   => 'coupon_creator_meta_box',
				'tab'       => 'content',
				'wrapclass' => 'cctor-img-coupon'
			);

			//Style Tab
			//Outer Border Placeholders
			$coupon_creator_meta_fields[ $prefix . 'heading_pro_display' ]  = array(
				'id'      => $prefix . 'heading_pro_display',
				'type'    => '',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'style'
			);
			$coupon_creator_meta_fields[ $prefix . 'coupon_border_themes' ] = array(
				'id'      => $prefix . 'coupon_border_themes',
				'type'    => '',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'style'
			);
			$coupon_creator_meta_fields[ $prefix . 'outer_border_color' ]   = array(
				'id'      => $prefix . 'outer_border_color',
				'type'    => '',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'style'
			);
			$coupon_creator_meta_fields[ $prefix . 'outer_radius' ]         = array(
				'id'      => $prefix . 'outer_radius',
				'type'    => '',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'style'
			);

			//Inside Border
			$coupon_creator_meta_fields[ $prefix . 'heading_inside_color' ] = array(
				'id'        => $prefix . 'heading_inside_color',
				'title'     => '',
				'desc'      => __( 'Inner Border', 'coupon-creator' ),
				'type'      => 'heading',
				'section'   => 'coupon_creator_meta_box',
				'tab'       => 'style',
				'wrapclass' => 'cctor-img-coupon'
			);
			$coupon_creator_meta_fields[ $prefix . 'bordercolor' ]          = array(
				'label'     => __( 'Inside Border Color', 'coupon-creator' ),
				'desc'      => __( 'Choose inside border color', 'coupon-creator' ),
				'id'        => $prefix . 'bordercolor',
				'type'      => 'color', // color
				'value'     => cctor_options( 'cctor_border_color' ),
				'section'   => 'coupon_creator_meta_box',
				'tab'       => 'style',
				'wrapclass' => 'cctor-img-coupon'
			);
			$coupon_creator_meta_fields[ $prefix . 'inside_radius' ]        = array(
				'id'      => $prefix . 'inside_radius',
				'type'    => '',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'style'
			);

			//Discount
			$coupon_creator_meta_fields[ $prefix . 'heading_color' ] = array(
				'id'        => $prefix . 'heading_color',
				'title'     => '',
				'desc'      => __( 'Deal Field Colors', 'coupon-creator' ),
				'type'      => 'heading',
				'section'   => 'coupon_creator_meta_box',
				'tab'       => 'style',
				'wrapclass' => 'cctor-img-coupon deal-display'
			);
			$coupon_creator_meta_fields[ $prefix . 'colordiscount' ] = array(
				'label'     => __( 'Deal Background Color', 'coupon-creator' ),
				'desc'      => __( 'Choose background color', 'coupon-creator' ),
				'id'        => $prefix . 'colordiscount',
				'type'      => 'color', // color
				'value'     => cctor_options( 'cctor_discount_bg_color' ),
				'section'   => 'coupon_creator_meta_box',
				'tab'       => 'style',
				'wrapclass' => 'cctor-img-coupon deal-display'
			);
			$coupon_creator_meta_fields[ $prefix . 'colorheader' ]   = array(
				'label'     => __( 'Deal Text Color', 'coupon-creator' ),
				'desc'      => __( 'Choose color for discount text', 'coupon-creator' ),
				'id'        => $prefix . 'colorheader',
				'type'      => 'color', // color
				'value'     => cctor_options( 'cctor_discount_text_color' ),
				'section'   => 'coupon_creator_meta_box',
				'tab'       => 'style',
				'wrapclass' => 'cctor-img-coupon deal-display'
			);

			//Expiration
			$coupon_creator_meta_fields[ $prefix . 'heading_expiration' ] = array(
				'id'      => $prefix . 'heading_expiration',
				'title'   => '',
				'desc'    => __( 'Expiration', 'coupon-creator' ),
				'type'    => 'heading',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'expiration'
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

			$coupon_creator_meta_fields[ $prefix . 'expiration_option' ] = array(
				'label'   => __( 'Expiration Option', 'coupon-creator' ),
				'desc'    => __( 'Choose the expiration method for this coupon', 'coupon-creator' ),
				'id'      => $prefix . 'expiration_option',
				'value'   => '',
				'type'    => 'select',
				'choices' => $expiration_options,
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'expiration'
			);

			$coupon_creator_meta_fields[ $prefix . 'expiration_msg_1' ] = array(
				'desc'      => __( 'This coupon will not expire and always show on the front end of your site.', 'coupon-creator' ),
				'id'        => $prefix . 'expiration_msg_1',
				'type'      => 'message',
				'section'   => 'coupon_creator_meta_box',
				'tab'       => 'expiration',
				'wrapclass' => 'expiration-field expiration-1'
			);
			$coupon_creator_meta_fields[ $prefix . 'expiration_msg_2' ] = array(
				'desc'      => __( 'This coupon will no longer show the day after the expiration date.', 'coupon-creator' ),
				'id'        => $prefix . 'expiration_msg_2',
				'type'      => 'message',
				'section'   => 'coupon_creator_meta_box',
				'tab'       => 'expiration',
				'wrapclass' => 'expiration-field expiration-2'
			);
			$coupon_creator_meta_fields[ $prefix . 'expiration_msg_3' ] = array(
				'desc'      => __( 'This coupon\'s expiration will change based on the choosen pattern.', 'coupon-creator' ),
				'id'        => $prefix . 'expiration_msg_3',
				'type'      => 'message',
				'section'   => 'coupon_creator_meta_box',
				'tab'       => 'expiration',
				'wrapclass' => 'expiration-field expiration-3'
			);
			$coupon_creator_meta_fields[ $prefix . 'expiration_msg_4' ] = array(
				'desc'      => __( 'This coupon will expire X days from when the date a visitor sees it.', 'coupon-creator' ),
				'id'        => $prefix . 'expiration_msg_4',
				'type'      => 'message',
				'section'   => 'coupon_creator_meta_box',
				'tab'       => 'expiration',
				'wrapclass' => 'expiration-field expiration-4'
			);

			$coupon_creator_meta_fields[ $prefix . 'date_format' ] = array(
				'label'     => __( 'Date Format', 'coupon-creator' ),
				'desc'      => __( 'Choose the date format', 'coupon-creator' ),
				'id'        => $prefix . 'date_format',
				'value'     => cctor_options( 'cctor_default_date_format' ),
				'type'      => 'select',
				'choices'   => array(
					'0' => __( 'Month First - MM/DD/YYYY', 'coupon-creator' ),
					'1' => __( 'Day First - DD/MM/YYYY', 'coupon-creator' )
				),
				'section'   => 'coupon_creator_meta_box',
				'tab'       => 'expiration',
				'wrapclass' => 'expiration-field expiration-2 expiration-3 expiration-4'
			);

			$coupon_creator_meta_fields[ $prefix . 'expiration' ] = array(
				'label'     => __( 'Expiration Date', 'coupon-creator' ),
				'id'        => $prefix . 'expiration',
				'desc'      => __( 'The coupon will not display without the date and will not display on your site after the date', 'coupon-creator' ),
				'type'      => 'date',
				'section'   => 'coupon_creator_meta_box',
				'tab'       => 'expiration',
				'wrapclass' => 'expiration-field expiration-2 expiration-3'
			);

			$coupon_creator_meta_fields[ $prefix . 'ignore_expiration' ] = array(
				'label'     => __( 'Ignore Expiration Date', 'coupon-creator' ),
				'desc'      => __( 'Check this to ignore the expiration date', 'coupon-creator' ),
				'id'        => $prefix . 'ignore_expiration',
				'type'      => 'checkbox',
				'section'   => 'coupon_creator_meta_box',
				'tab'       => 'expiration',
				'wrapclass' => 'expiration-field'
			);

			//Image Coupon
			$coupon_creator_meta_fields[ $prefix . 'image' ] = array(
				'label'   => '',
				'desc'    => __( 'Upload an image to use as the entire coupon - Current image size is for 390 pixels in width with auto height', 'coupon-creator' ),
				'id'      => $prefix . 'image',
				'type'    => 'image',
				'image'   => 'Image Coupon',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'image_coupon'
			);
			//Help
			$coupon_creator_meta_fields[ $prefix . 'videos' ] = array(
				'label'   => '',
				'id'      => $prefix . 'videos',
				'type'    => 'cctor_support',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'help'
			);

			//Upgreade to Pro
			$coupon_creator_meta_fields[ $prefix . 'upgrade_to_pro' ] = array(
				'label'   => '',
				'id'      => $prefix . 'upgrade_to_pro',
				'type'    => 'cctor_pro',
				'section' => 'coupon_creator_meta_box',
				'tab'     => 'pro'
			);

			if ( has_filter( 'cctor_filter_meta_fields' ) ) {
				/**
				 * Filter the meta fields from Coupon Creator
				 *
				 *
				 * @param array $coupon_creator_meta_fields an array of fields to display in meta tabs.
				 *
				 */
				$coupon_creator_meta_fields = apply_filters( 'cctor_filter_meta_fields', $coupon_creator_meta_fields );
			}

			return $coupon_creator_meta_fields;
		}

		/***************************************************************************/
		/*
		* Save Meta Boxes
		* since 1.80
		*/
		public static function cctor_save_coupon_creator_meta( $post_id, $post ) {

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			if ( ! isset( $_POST['coupon_creator_nonce'] ) ) {
				return;
			}

			if ( ! wp_verify_nonce( $_POST['coupon_creator_nonce'], 'coupon_creator_save_post' ) ) {
				return;
			}

			if ( ! current_user_can( 'edit_post', $post->ID ) ) {
				return;
			}

			// Save data
			//Get Meta Fields
			$coupon_creator_meta_fields = self::cctor_metabox_options();

			//Expiration Option Auto Check Ignore Input
			if ( 1 == $_POST['cctor_expiration_option'] ) {
				$_POST['cctor_ignore_expiration'] = 'on';
			} elseif ( 'on' == $_POST['cctor_ignore_expiration'] && 1 != $_POST['cctor_expiration_option'] ) {
				unset( $_POST['cctor_ignore_expiration'] );
			}

			//For Each Field Sanitize the Post Data
			foreach ( $coupon_creator_meta_fields as $option ) {

				//Hook Meta Box Save
				do_action( 'cctor_save_meta_fields', $post_id, $option );

				//If No CheckBox Sent, then delete meta
				if ( $option['type'] == 'checkbox' ) {

					$coupon_meta_checkbox = get_post_meta( $post_id, $option['id'], true );

					if ( $coupon_meta_checkbox && ! isset( $_POST[ $option['id'] ] ) ) {
						delete_post_meta( $post_id, $option['id'] );
					}

				}

				// Final Check if value should be saved then sanitize and save
				if ( isset( $_POST[ $option['id'] ] ) ) {

					//Send Input to Sanitize Class, will return sanitized input or no input if no sanitization method
					$cctor_sanitize = new Coupon_Creator_Plugin_Sanitize( $option['type'], $_POST[ $option['id'] ], $option );

					$old = get_post_meta( $post_id, $option['id'], true );

					$new = $_POST[ $option['id'] ];

					if ( ! is_null( $new ) && $new != $old ) {
						update_post_meta( $post_id, $option['id'], $cctor_sanitize->result );
					} elseif ( '' == $new && $old ) {
						delete_post_meta( $post_id, $option['id'], $old );
					}

				}
			}

		}

		/***************************************************************************/

	}//end Coupon_Creator_Meta_Box Class

}//class_exists( 'Coupon_Creator_Meta_Box' )
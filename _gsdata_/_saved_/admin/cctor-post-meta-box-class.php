<?php
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

if ( ! class_exists( 'Coupon_Creator_Meta_Box' ) ) {	
	/*
	* Coupon_Creator_Meta_Box
	* @version 1.80
	*/
	class Coupon_Creator_Meta_Box {

		/*
		* Construct
		* @version 1.80
		*/
		public function __construct() {
			//Setup Coupon Meta Boxes
			add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_boxes' ) );	
			//Save Meta Boxes Data
			add_action( 'save_post', array( __CLASS__, 'save_coupon_creator_meta' ),50, 2 );
		}
	/***************************************************************************/

		/*
		* Coupon Creator Meta Sections
		* @version 1.90
		*/
		public function get_tabs() {
			
			$meta_tabs = array();
			
			//Coupon Creator Options Tabs
			$meta_tabs['content']     	= __( 'Content', 'coupon_creator' );
			$meta_tabs['style']   		= __( 'Style', 'coupon_creator' );
			$meta_tabs['expiration'] 	= __( 'Expiration', 'coupon_creator' );
			$meta_tabs['image_coupon'] 	= __( 'Image Coupon', 'coupon_creator' );
			$meta_tabs['help'] 	= __( 'Help', 'coupon_creator' );

			//Filter Option Tabs
			if(has_filter('cctor_meta_tabs')) {
				$meta_tabs = apply_filters('cctor_meta_tabs', $meta_tabs);
			} 
			
			return $meta_tabs;
		}		
		
	/***************************************************************************/

		/*
		* Add Meta Boxes
		* @version 1.80
		*/
		public static function add_meta_boxes() {
			global $pagenow, $typenow;
		if (empty($typenow) && !empty($_GET['post'])) {
			$post = get_post($_GET['post']);
			$typenow = $post->post_type;
		}
		
		if (is_admin() && $pagenow=='post-new.php' OR $pagenow=='post.php' && $typenow=='cctor_coupon') {
					add_meta_box(
						'coupon_creator_meta_box', // id
						__( 'Coupon Fields', 'coupon_creator' ), // title
						array( __CLASS__, 'show_coupon_creator_meta_box' ), // callback
						'cctor_coupon', // post_type
						'normal', // context
						'default' // priority
					);
								
					if($pagenow !='post-new.php'){
						add_meta_box(
							'coupon_creator_shortcode', // id
							__( 'Coupon Shortcode', 'coupon_creator' ), // title
							array( __CLASS__, 'show_coupon_shortcode' ), // callback
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
		* @version 1.80
		* @param stdClass $post
		*/
		public static function show_coupon_creator_meta_box( $post, $metabox  ) {
		
				wp_nonce_field( 'coupon_creator_save_post', 'coupon_creator_nonce' );
								
				//Get Tab Sections
				$meta_tabs = self::get_tabs();
				
				//Get Meta Boxes
				$coupon_creator_meta_fields = self::metabox_options();
				
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
								
									<input type="text" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" value="<?php echo $meta; ?>" size="30" />
										<br /><span class="description"><?php echo $field['desc']; ?></span>
										
								<?php break;
								// textarea
								case 'textarea': ?>
								
									<textarea name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" cols="60" rows="4"><?php echo $meta; ?></textarea>
										<br /><span class="description"><?php echo $field['desc']; ?></span>
										
								<?php break;
								// textarea
								case 'textarea_w_tags': ?>
								
									<textarea name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" cols="60" rows="4"><?php echo $meta; ?></textarea>
										<br /><span class="description"><?php echo $field['desc']; ?></span>
										
								<?php break;								
								// checkbox 
								case 'checkbox': ?>						
								
									<input type="checkbox" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" <?php echo checked( $meta, 1, false ); ?>/><label for="<?php echo $field['id']; ?>"><?php echo $field['desc']; ?></label>
									
								<?php break;
								
								case 'select': 
								
								/*echo $meta ."<br>";
								echo $field['value'] ."<br>";
								$selected = ""; */
								
								//Find Current Selected Value or use Default
								if ($meta) {
									$selected = $meta;
								} else {
									$selected = $field['value'];
								}
								
								?>
									<select id="<?php echo $field['id']; ?>" class="select <?php echo $field['id']; ?>" name="<?php echo $field['id']; ?>">
									
									<?php foreach ( $field['choices'] as $value => $label ) { 
									
										echo '<option value="'. $value.'"'.selected( $value , $selected ).'>'. $label .'</option>';
									
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
										$image = '<div style="display:block" id="'.$field['id'].'" class="cctor_coupon_default_image cctor_coupon_box">'.$field['image'].'</div> <img style="display:none" src="'. $image .'" id="'. $field['id'] .'" class="cctor_coupon_image cctor_coupon_box_img" />';
									}?>
									
											<?php echo $image; ?><br />
											<input name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" type="hidden" class="upload_coupon_image" type="text" size="36" name="ad_image" value="<?php echo $meta; ?>" /> 
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
									<input class="color-picker" type="text" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" value="<?php echo $meta; ?>" data-default-color="<?php echo $field['value']; ?>"/>
										<br /><span class="description"><?php echo $field['desc']; ?></span>
										
								<?php break;
								 // date
								 case 'date': ?>
								 
									<input type="text" class="datepicker" name="<?php echo $field['id']; ?>" id="<?php echo $field['id']; ?>" value="<?php echo $meta; ?>" size="10" />
									<br /><span class="description"><?php echo $field['desc']; ?></span>
											
								<?php break;
									// Videos
								 case 'cctor_videos':?>
								 
									<p>
									<a href='http://www.youtube.com/embed/9Uui5fNqg_I?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1' class='youtube_colorbox' rel='how_to_videos'>How to create a coupon</a><br/>
									<a href='http://www.youtube.com/embed/pS37ahKChqc?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1' class='youtube_colorbox' rel='how_to_videos'>How to create an image coupon</a><br/>
									<a href='http://www.youtube.com/embed/mMAJq47AjzE?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1' class='youtube_colorbox' rel='how_to_videos'>How to insert and align coupons</a>
									<br/>(click to open)
									</p>
									
								<?php break;
		
							} //end switch

						if ($field['type'] =="wysiwyg") {	
							//print_r($field);	
							//echo $field['section'];
							//print_r($metabox);
							//echo $metabox['id'];
						}	
						if(has_filter('cctor_meta_field_cases')) {
							// this adds any addon fields (from plugins) to the array
							echo apply_filters('cctor_meta_field_cases', $field, $meta);
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
		* @version 1.90
		* @param stdClass $post
		*/
		public static function show_coupon_shortcode( $post, $metabox  ) {
			?><p class="shortcode">
			<?php  _e( 'Place this coupon in your posts, pages, custom post types, or widgets by using the shortcode below:<br><br>', 'coupon_creator' ); ?> 
			<code>[coupon couponid="<?php echo $post->ID; ?>" name="<?php echo $post->post_title; ?>"]</code>
			</p><?php 
		
		}
		/***************************************************************************/
		
		/*
		* Load Meta Box Functions
		* @version 1.80  
		*/
		public static function metabox_options() {
		
			// Field Array  cctor_
			$prefix = 'cctor_';
				$coupon_creator_meta_fields[$prefix . 'heading_coupon'] = array(
					'id' => $prefix . 'heading_coupon',
					'title'   => '',
					'desc'    =>  __( 'Coupon Heading','coupon_creator' ),
					'type'    => 'heading',
					'section' => 'coupon_creator_meta_box',
					'tab' => 'content'
				);				
				$coupon_creator_meta_fields[$prefix . 'amount'] =	array(
					'label' => __('Discount', 'coupon_creator' ),
					'desc' => __('Enter the discount amount  - 30%, Buy One Get One Free, etc...', 'coupon_creator' ),
					'id' => $prefix . 'amount',
					'type'  => 'text',
					'section' => 'coupon_creator_meta_box',
					'tab' => 'content'
				);		
				$coupon_creator_meta_fields[$prefix . 'heading_color'] = array(
					'id' => $prefix . 'heading_color',
					'title'   => '',
					'desc'    =>  __( 'Coupon Colors','coupon_creator' ),
					'type'    => 'heading',
					'section' => 'coupon_creator_meta_box',
					'tab' => 'style'
				);				
				$coupon_creator_meta_fields[$prefix . 'colordiscount'] =	array(
					'label' => __('Discount Background Color', 'coupon_creator' ),
					'desc'  => __('Choose background color', 'coupon_creator' ),
					'id' => $prefix . 'colordiscount',
					'type' => 'color', // color
					'value' => coupon_options($prefix . 'discount_bg_color'),
					'section' => 'coupon_creator_meta_box',
					'tab' => 'style'
				);	
				$coupon_creator_meta_fields[$prefix . 'colorheader'] =	array(
					'label' => __('Discount Text Color', 'coupon_creator' ),
					'desc'  => __('Choose color for discount text', 'coupon_creator' ),
					'id' => $prefix . 'colorheader',
					'type' => 'color', // color
					'value' => coupon_options($prefix . 'discount_text_color'),
					'section' => 'coupon_creator_meta_box',
					'tab' => 'style'
				);	
				$coupon_creator_meta_fields[$prefix . 'bordercolor'] =	array(
					'label' => __('Inside Border Color', 'coupon_creator' ),
					'desc'  => __('Choose inside border color', 'coupon_creator' ),
					'id' => $prefix . 'bordercolor',
					'type' => 'color', // color
					'value' => coupon_options($prefix . 'border_color'),
					'section' => 'coupon_creator_meta_box',
					'tab' => 'style'
				);	
				$coupon_creator_meta_fields[$prefix . 'heading_deal'] = array(
					'id' => $prefix . 'heading_deal',
					'title'   => '',
					'desc'    =>  __( 'Coupon Terms','coupon_creator' ),
					'type'    => 'heading',
					'section' => 'coupon_creator_meta_box',
					'tab' => 'content'
				);			
				$coupon_creator_meta_fields[$prefix . 'description'] =	array(
					'label' => __('Terms:', 'coupon_creator' ),
					'desc' => __('Enter the terms of the discount', 'coupon_creator' ),
					'id' => $prefix . 'description',
					'type'  => 'textarea_w_tags',
					'section' => 'coupon_creator_meta_box',
					'tab' => 'content'
				);	
				$coupon_creator_meta_fields[$prefix . 'heading_expiration'] = array(
					'id' => $prefix . 'heading_expiration',
					'title'   => '',
					'desc'    =>  __( 'Expiration','coupon_creator' ),
					'type'    => 'heading',
					'section' => 'coupon_creator_meta_box',
					'tab' => 'expiration'
				);	
				$coupon_creator_meta_fields[$prefix . 'expiration'] =	array(
					'label' => __('Expiration Date:', 'coupon_creator' ),
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
					'value' => coupon_options($prefix . 'default_date_format'),
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
				/*$coupon_creator_meta_fields[$prefix . 'heading_image'] = array(
					'id' => $prefix . 'heading_image',
					'title'   => '',
					'desc'    =>  __( 'Image Coupon','coupon_creator' ),
					'type'    => 'heading',
					'section' => 'coupon_creator_meta_box',
					'tab' => 'image_coupon'
				);*/				
				$coupon_creator_meta_fields[$prefix . 'image'] =	array(
					'label'  => '',
					'desc'  => __('Upload an image to use as the entire coupon - Current image size is for 390 pixels in width with auto height', 'coupon_creator' ),
					'id'    => $prefix . 'image',
					'type'  => 'image',
					'image'  => 'Optional Coupon Image',
					'section' => 'coupon_creator_meta_box',
					'tab' => 'image_coupon'
				);	
				$coupon_creator_meta_fields[$prefix . 'heading_support'] = array(
					'id' => $prefix . 'heading_support',
					'title'   => '',
					'desc'    =>  __( 'How to Videos','coupon_creator' ),
					'type'    => 'heading',
					'section' => 'coupon_creator_meta_box',
					'tab' => 'help'
				);		
				$coupon_creator_meta_fields[$prefix . 'videos'] =	array(
					'label'  => __( 'Coupon Creator How to Videos:', 'coupon_creator' ),
					'id'    => $prefix . 'videos',
					'type'  => 'cctor_videos',
					'section' => 'coupon_creator_meta_box',
					'tab' => 'help'
				);		
	
			if(has_filter('cctor_meta_fields')) {
				//Add Fields to the Coupon Creator Meta Box
				$coupon_creator_meta_fields = apply_filters('cctor_meta_fields', $coupon_creator_meta_fields);
			} 
			
			return $coupon_creator_meta_fields;
		}
		
		/***************************************************************************/
		/*
		* Save Meta Boxes
		* @version 1.80
		*/
		public static function save_coupon_creator_meta( $post_id, $post ) {
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
				$coupon_creator_meta_fields = self::metabox_options();
				
				//For Each Field Sanitize the Post Data
				foreach ( $coupon_creator_meta_fields as $option ) {

					//If No CheckBox Sent, then delete meta
					if ($option['type'] == 'checkbox' ) {
						
						$coupon_meta_checkbox = get_post_meta($post_id, $option['id'], true);
						
						if ($coupon_meta_checkbox && ! isset( $_POST[$option['id']] ) ) {
							delete_post_meta($post_id,  $option['id']);
						}	
						
					} 

					// Sanitization Filter for each Meta Field Type
					if(isset($_POST[$option['id']])){
						if ( has_filter( 'cctor_sanitize_' . $option['type'] ) ) {
							$clean[$option['id']] = apply_filters( 'cctor_sanitize_' . $option['type'], $_POST[$option['id']], $option );
						}	
					}

				}				
				
				//Loop Through Sanitized Data and Save to MetaBox if different from existing
				foreach ($clean as $key => $value ) {

						$old = get_post_meta($post_id, $key, true);
						$new = $value;
						
						/*if ( $key == "cctor_date_format") {
							echo $old ." old<br>";
							echo $new ." new<br>";
							if ( !is_null($new)  && $new != $old) {
								echo $new ." new2<br>";
							}
						}*/
						
						
						
						if ( !is_null($new) && $new != $old) {
							update_post_meta($post_id, $key, $new);
						} elseif ('' == $new && $old) {
							delete_post_meta($post_id, $key, $old);
						} 
				} 
			
			
		}
		
			/***************************************************************************/	
			
	} //end Coupon_Creator_Meta_Box Class
	
} // class_exists( 'Coupon_Creator_Meta_Box' )
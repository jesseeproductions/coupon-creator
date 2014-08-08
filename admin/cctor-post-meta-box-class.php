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
						'high' // priority
					);
					
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
				
				$coupon_creator_meta_fields = self::metabox_options();
							
				ob_start(); ?>
	
				<table class="form-table">
				<?php foreach ($coupon_creator_meta_fields as $field) {
					
					if ($field['section'] == $metabox['id']) :
					// get value of this field if it exists for this post
					$meta = get_post_meta($post->ID, $field['id'], true); ?>

						<tr>
							<th><label for="<?php echo $field['id']; ?>"><?php echo $field['label']; ?></label></th>
							<td>
							<?php switch($field['type']) { 
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
								// image using Media Manager from WP 3.5 and greater
								case 'image': 
								
									$image = plugins_url('/images/optional_coupon.png' , __FILE__ ); ?>
									<img id="<?php echo $field['id']; ?>" class="cctor_coupon_default_image" style="display:none" src="<?php echo $image; ?>">
									<?php //Check existing field and if numeric
									if (is_numeric($meta)) { 
										$image = wp_get_attachment_image_src($meta, 'medium'); 
										$image = $image[0];
									} ?>
											<img src="<?php echo $image; ?>" id="<?php echo $field['id']; ?>" class="cctor_coupon_image" /><br />
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
						} 
						
						
						?>


	
					</td></tr>
			<?php	
				endif; //end if in section check
			
			} // end foreach ?>
				</table>	
		<?php
				echo ob_get_clean();
		}

		/***************************************************************************/
		
		/*
		* Load Meta Box Functions
		* @version 1.80  
		*/
		public static function metabox_options() {
		
			// Field Array  cctor_
			$prefix = 'cctor_';
			$coupon_creator_meta_fields = array(
				array(
					'label' => 'Discount',
					'desc' => 'Enter the discount amount  - 30%, Buy One Get One Free, etc...',
					'id' => $prefix . 'amount',
					'type'  => 'text',
					'section' => 'coupon_creator_meta_box'
				),
				array(
					'label' => 'Discount Background Color' ,
					'desc'  => 'Choose background color',
					'id' => $prefix . 'colordiscount',
					'type' => 'color', // color
					'value' => coupon_options('cctor_discount_bg_color'),
					'section' => 'coupon_creator_meta_box'
				),
				array(
					'label' => 'Discount Text Color',
					'desc'  => 'Choose color for discount text',
					'id' => $prefix . 'colorheader',
					'type' => 'color', // color
					'value' => coupon_options('cctor_discount_text_color'),
					'section' => 'coupon_creator_meta_box'
				),
				array(
					'label' => 'Border Color',
					'desc'  => 'Choose inside solid border color',
					'id' => $prefix . 'bordercolor',
					'type' => 'color', // color
					'value' => coupon_options('cctor_border_color'),
					'section' => 'coupon_creator_meta_box'
				),
				array(
					'label' => 'Terms:',
					'desc' => 'Enter the terms of the discount',
					'id' => $prefix . 'description',
					'type'  => 'textarea_w_tags',
					'section' => 'coupon_creator_meta_box'
				),
				array(
					'label' => 'Expiration Date:',
					'id' => $prefix . 'expiration',
					'desc' => 'The coupon will not display without the date and will not display on your site after the date',
					'type'  => 'date',
					'section' => 'coupon_creator_meta_box'
				),
				array(
					'label'=> 'Date Format',
					'desc'  => 'Check this to change date format to Day / Month / Year (default is Month / Day / Year)',
					'id'    => $prefix . 'date_format',
					'type'  => 'checkbox',
					'section' => 'coupon_creator_meta_box'
				),
				 array(
					'label'=> 'Ignore Expiration Date',
					'desc'  => 'Check this to ignore the expiration date',
					'id'    => $prefix . 'ignore_expiration',
					'type'  => 'checkbox',
					'section' => 'coupon_creator_meta_box'
				),
				array(
					'label'  => 'Image',
					'desc'  => 'Upload and insert an image as a coupon - Image Size 400 pixels by 200 pixels',
					'id'    => $prefix . 'image',
					'type'  => 'image',
					'section' => 'coupon_creator_meta_box'
				),			
				array(
					'label'  => 'Coupon Creator How to Videos:',
					'id'    => $prefix . 'cctor_videos',
					'type'  => 'cctor_videos',
					'section' => 'coupon_creator_meta_box'
				)
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
						if ($new && $new != $old) {
							update_post_meta($post_id, $key, $new);
						} elseif ('' == $new && $old) {
							delete_post_meta($post_id, $key, $old);
						}
				} 
			
			
		}
		
			/***************************************************************************/	
			
	} //end Coupon_Creator_Meta_Box Class
	
} // class_exists( 'Coupon_Creator_Meta_Box' )
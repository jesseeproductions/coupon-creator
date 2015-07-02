<?php
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

	/*
	* Coupon Creator Pro Inserter Class
	* @version 1.90
	*/
	class Coupon_Creator_Inserter {

		/*
		* Construct
		* @version 1.80
		*/
		public function __construct() {

			//Add Button for Coupons in Editor
			add_action('media_buttons_context', array( __CLASS__, 'add_cc_coupon_button' ));

		}

		/*
		* Add Coupon Inserter Button Above WordPress Editor
		* @version 1.90
		*/
		public static function add_cc_coupon_button($context) {

			$screen = get_current_screen();

			if ( 'cctor_coupon' != $screen->id && 'cctor_coupon_page_coupon-options' != $screen->id ) {

				//add Content for inline popup for Coupon Inserter
				add_action('admin_footer', array( __CLASS__, 'add_coupon_inline_popup' ));

				//path to coupon icon
				$img = CCTOR_URL . 'admin/images/coupon_creator.png';
				//the id of the container I want to show in the popup
				$container_id = 'coupon_container';
				//our popup's title
				$title = '<h3>' . __( 'Insert Coupon Creator Shortcode', 'coupon_creator' ) . '</h3>';

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
							#TB_title h3 {
								margin: 0;
								color: #2a5a8a;
								margin: 0;
								text-shadow: 0 1px 0 #fff;
							}
							.cctor-inserter-section {
								padding:15px;
								overflow:hidden;
							}
							.cctor-inserter-section label{
								font-weight: 700;
							}
							.cctor-inserter-section select{
								min-width: 120px;
							}
							#coupon-submit {
								float:right;
							}
						 </style>
							<a class='thickbox button cctor_insert_link' id='add_cctor_shortcode'  title='{$title}' href='#TB_inline?width=783&height=400&inlineId={$container_id}'><span class='cctor_insert_icon'></span>Add Coupon</a>";
			 }

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

								var coupon_orderby = jQuery("#coupon_orderby").val();
								var coupon_orderby = " couponorderby=\""+ coupon_orderby + "\" ";
							} else {
								var coupon_shortcode = "coupon";
								var coupon_category = "";
								var coupon_orderby = "";
							}
						var coupon_name = jQuery("#coupon_select option[value='" + coupon_id + "']").text().replace(/[\[\]]/g, '');
						var cctor_align = jQuery("#coupon_align").val();
						var coupon_align = jQuery("#coupon_align option[value='" + cctor_align + "']").text().replace(/[\[\]]/g, '');
						window.send_to_editor("[" + coupon_shortcode + " couponid=\"" + coupon_id + "\"" + coupon_category + coupon_orderby +" coupon_align=\"" + cctor_align + "\" name=\"" + coupon_name + "\"]");
					}

					//Toggle Category Input when Loop Selected
					function show_category() {
						var coupon_select = document.getElementById("coupon_select");
						var coupon_selection = coupon_select.options[coupon_select.selectedIndex].value;

						var category_select = document.getElementById("coupon_category_select_container");
						var orderby_select = document.getElementById("coupon_orderby_select_container");

						if (coupon_selection == "loop") {
							category_select.style.visibility = "visible";
							orderby_select.style.visibility = "visible";
						}
						else {
							category_select.style.visibility = "hidden";
							orderby_select.style.visibility = "hidden";
						}
					}
				</script>

				<style>
					#coupon_orderby_select_container,
					#coupon_category_select_container {
						visibility: hidden;
						margin-top: 15px;
					}
				</style>

			<!--Start Thickbox Popup -->
			<div id="coupon_container" style="display:none;">
				<?php
					 $querycoupon = new WP_Query( 'post_status=publish&post_type=cctor_coupon&posts_per_page=-1' );
					// The Coupon Loop
					if ($querycoupon->have_posts()) {
				?>
				<div class="cctor-inserter-section">
					<!--Create a Select Box with Coupon Titles -->
					<label for="coupon_select"><?php echo __( 'Select Loop or an Individual Coupon', 'coupon_creator' ); ?></label>
						<select name="coupon_select_box" id="coupon_select" onchange="show_category()">
							<option value="#" ></option>
							<option value="loop" ><?php echo __( 'Coupon Loop', 'coupon_creator' ); ?></option>
							<?php
							while ($querycoupon->have_posts()) {
							$querycoupon->the_post(); ?>
								<!--Adding the Value as ID for the Shortcode and the Title for Humans-->
								<option value="<?php the_ID(); ?>" ><?php the_title(); ?></option>

							<?php } ?>
						</select><br> <!--End Select Box Coupons-->

					<!--Create a Select Box for Categories -->
					<div id="coupon_category_select_container">
						<label for="coupon-categories"><?php echo __( 'Select a Coupon Category to use in the Loop', 'coupon_creator' ); ?></label>
							<select id="coupon_category_select" name="coupon_category_select">
							<option value="#" ></option>
							 <option value=""><?php echo __( 'All Categories', 'coupon_creator' ); ?></option>
							 <?php
								$cctor_cat_args = array(
								  'orderby' => 'name',
								  'order' => 'ASC',
								  'taxonomy' => 'cctor_coupon_category'
								 );
							  $categories = get_categories( $cctor_cat_args );


							  foreach ($categories as $category) {
								$option = '<option value="'.  esc_html( $category->name ) .'">';
								$option .=  esc_html( $category->cat_name );
								$option .= '</option>';
								echo $option;
							  }
							 ?>
							</select> <!--End Select Box Categories-->
					</div><br>
					<!--Create a Select Box for Align -->
					<label for="coupon_align"><?php echo __( 'Select How to Align the Coupon(s)', 'coupon_creator' ); ?></label>
						<select name="coupon_align_select_box" id="coupon_align">
							 <option value="cctor_alignnone"><?php echo __( 'None', 'coupon_creator' ); ?></option>
							 <option value="cctor_alignleft"><?php echo __( 'Align Left', 'coupon_creator' ); ?></option>
							 <option value="cctor_alignright"><?php echo __( 'Align Right', 'coupon_creator' ); ?></option>
							 <option value="cctor_aligncenter"><?php echo __( 'Align Center', 'coupon_creator' ); ?></option>
						</select><br> <!--End Select Box Align -->

					<!--Create a Select Box for Orderby -->
					<div id="coupon_orderby_select_container">
						<label for="coupon_orberby_select_box"><?php echo __( 'Select a Coupon Category to use in the Loop', 'coupon_creator' ); ?></label>
							<select id="coupon_orderby" name="coupon_orberby_select_box">
							 <option value="date"><?php echo __( 'Date (default)', 'coupon_creator' ); ?></option>
							 <option value="none"><?php echo __( 'None', 'coupon_creator' ); ?></option>
							 <option value="ID"><?php echo __( 'ID', 'coupon_creator' ); ?></option>
							 <option value="author"><?php echo __( 'Author', 'coupon_creator' ); ?></option>
							 <option value="title"><?php echo __( 'Coupon Post Title', 'coupon_creator' ); ?></option>
							 <option value="name"><?php echo __( 'Slug Name', 'coupon_creator' ); ?></option>
							 <option value="modified"><?php echo __( 'Last Modified', 'coupon_creator' ); ?></option>
							 <option value="rand"><?php echo __( 'Random', 'coupon_creator' ); ?></option>
						</select><br> <!--End Select Box Align -->
					</div>
				</div> <!--End Div -->

				<br/>

				<div class="cctor-inserter-section">
					<!--Insert into Editor Button that Calls Script-->
					<input type="button" id="coupon-submit" onclick="InsertCoupon();" class="button-primary" value="Insert Coupon" name="submit" />
				</div>

				<?php } else { ?>
					<h4><?php echo __( 'No Coupons are Published', 'coupon_creator' ); ?></h4>
				<?php } ?>
			</div> <!--End #coupon_container -->
		<?php }

	/***************************************************************************/

	} //end Coupon_Creator_Inserter Class

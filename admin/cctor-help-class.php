<?php
//If Direct Access Kill the Script
if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ ) {
	die( 'Access denied.' );
}
if ( class_exists( 'Coupon_Creator_Help_Class' ) ) {
	return;
}

class Coupon_Creator_Help_Class {

	/*
	* Option Fields
	* since 1.80
	*/
	protected $fields;


	public function __construct() {

		$this->set_help_fields();

	}

	protected function set_help_fields() {

		//Content
		$this->fields['header_video_guides_content'] = array(
			'section' => '',
			'tab'     => 'content',
			'text'    => 'Coupon Content',
			'type'    => 'heading'
		);
		$this->fields['video_creating_coupon']       = array(
			'section'  => '',
			'tab'      => 'content',
			'text'     => 'Creating a Coupon',
			'video_id' => 'tIau3ZNjoeI',
			'type'     => 'video'
		);
		$this->fields['video_creating_coupon_pro']   = array(
			'section'  => '',
			'tab'      => 'content',
			'text'     => 'Creating a Pro Coupon',
			'video_id' => 'FI218DxXnrY',
			'type'     => 'video'
		);
		$this->fields['video_pro_columns_rows']      = array(
			'section'  => '',
			'tab'      => 'content',
			'text'     => 'Using Columns and Rows in the Visual Editor (Pro)',
			'video_id' => 'w67yqCZXF6I',
			'type'     => 'video'
		);
		$this->fields['video_pro_view_shortcode']    = array(
			'section'  => '',
			'tab'      => 'content',
			'text'     => 'How to use the View Shortcodes and Deal Display Options (Pro)',
			'video_id' => 'h0YVXi2vq3g',
			'type'     => 'video'
		);
		$this->fields['video_inserting_coupon'] = array(
			'section'  => '',
			'tab'      => 'content',
			'text'     => 'Inserter and Aligning Coupons',
			'video_id' => 'sozW-J-g3Ts',
			'type'     => 'video'
		);
		$this->fields['video_end_list_content']      = array(
			'section' => '',
			'tab'     => 'content',
			'type'    => 'end_list'
		);

		//Style
		$this->fields['header_video_guides_style'] = array(
			'section' => '',
			'tab'     => 'style',
			'text'    => 'Style Guides',
			'type'    => 'heading'
		);
		$this->fields['video_pro_border_styles']   = array(
			'section'  => '',
			'tab'      => 'style',
			'text'     => 'How to use the Border Styles',
			'video_id' => 'EQRv8g2nmuE',
			'type'     => 'video'
		);
		$this->fields['video_pro_background_img']  = array(
			'section'  => '',
			'tab'      => 'style',
			'text'     => 'Using the Pro Background Image',
			'video_id' => 'vmViVkoQB0M?',
			'type'     => 'video'
		);
		$this->fields['video_end_list_style']      = array(
			'section' => '',
			'tab'     => 'style',
			'type'    => 'end_list'
		);

		//Expiration
		$this->fields['header_video_guides_expiration'] = array(
			'section' => '',
			'tab'     => 'expiration',
			'text'    => 'Expiration Options',
			'type'    => 'heading'
		);
		$this->fields['video_pro_recurring_expiration'] = array(
			'section'  => '',
			'tab'      => 'expiration',
			'text'     => 'How to use Recurring Expiration',
			'video_id' => 'JR4GA4lsOB0',
			'type'     => 'video'
		);
		$this->fields['video_pro_counter']              = array(
			'section'  => '',
			'tab'      => 'expiration',
			'text'     => 'Using the Pro Counter',
			'video_id' => 'aVkwq8cIgB0',
			'type'     => 'video'
		);
		$this->fields['video_end_list_expiration']      = array(
			'section' => '',
			'tab'     => 'expiration',
			'type'    => 'end_list'
		);

		//Image
		$this->fields['header_video_guides_img']         = array(
			'section' => '',
			'tab'     => 'image_coupon',
			'text'    => 'Image Coupons',
			'type'    => 'heading'
		);
		$this->fields['video_creating_image_coupon']     = array(
			'section'  => '',
			'tab'      => 'image_coupon',
			'text'     => 'Creating an Image Coupon',
			'video_id' => 'A1mULc_MyHs',
			'type'     => 'video'
		);
		$this->fields['video_creating_pro_image_coupon'] = array(
			'section'  => '',
			'tab'      => 'image_coupon',
			'text'     => 'Creating a Pro Image Coupon',
			'video_id' => 'SqAG3s1FniA',
			'type'     => 'video'
		);
		$this->fields['video_end_list_image']            = array(
			'section' => '',
			'tab'     => 'image_coupon',
			'type'    => 'end_list'
		);

		$this->fields['header_video_guides_links'] = array(
			'section' => '',
			'tab'     => 'links',
			'text'    => 'Popup Print View',
			'type'    => 'heading'
		);
		$this->fields['video_pro_popup']           = array(
			'section'  => '',
			'tab'      => 'links',
			'text'     => 'How to use the Popup Print View Feature',
			'video_id' => 'iThKkEgYBDE',
			'type'     => 'video'
		);
		$this->fields['video_end_list_links']      = array(
			'section' => '',
			'tab'     => 'links',
			'type'    => 'end_list'
		);

		//WooCommerce
		$this->fields['header_video_guides_woo'] = array(
			'section' => '',
			'tab'     => 'cctor_woocommerce',
			'text'    => 'WooCommerce Coupons',
			'type'    => 'heading'
		);
		$this->fields['video_pro_woocommerce']   = array(
			'section'  => '',
			'tab'      => 'cctor_woocommerce',
			'text'     => 'How to Create a WooCommerce Coupon',
			'video_id' => 'xH3GmKPzQKc?',
			'type'     => 'video'
		);
		$this->fields['video_end_list_woo']      = array(
			'section' => '',
			'tab'     => 'cctor_woocommerce',
			'type'    => 'end_list'
		);


		//Option Defaults
		$this->fields['header_video_guides_defaults'] = array(
			'section' => 'defaults',
			'tab'     => '',
			'text'    => 'Coupon Options',
			'type'    => 'heading'
		);
		$this->fields['video_coupon_defaults']  = array(
			'section'  => 'defaults',
			'tab'      => '',
			'text'     => 'Using the Coupon Creator Options',
			'video_id' => 'h3Zg8rxIDdc',
			'type'     => 'video'
		);
		$this->fields['video_pro_defaults']             = array(
			'section'  => 'defaults',
			'tab'      => '',
			'text'     => 'Using the Pro Options',
			'video_id' => '8L0JmSB_V-E',
			'type'     => 'video'
		);
		$this->fields['video_end_list_defaults'] = array(
			'section' => 'defaults',
			'tab'     => '',
			'type'    => 'end_list'
		);


		//Option Display
		$this->fields['header_video_guides_defaults'] = array(
			'section' => 'display',
			'tab'     => '',
			'text'    => 'Coupon Options',
			'type'    => 'heading'
		);
		$this->fields['video_pro_text-overrides']             = array(
			'section'  => 'display',
			'tab'      => '',
			'text'     => 'Using the Pro Text Overrides',
			'video_id' => 'pFnp5VsfwUE',
			'type'     => 'video'
		);
		$this->fields['video_end_list_defaults'] = array(
			'section' => 'display',
			'tab'     => '',
			'type'    => 'end_list'
		);

		//Option Templating
		$this->fields['header_video_guides_defaults'] = array(
			'section' => 'templating',
			'tab'     => '',
			'text'    => 'Coupon Options',
			'type'    => 'heading'
		);
		$this->fields['video_pro_dimension']             = array(
			'section'  => 'templating',
			'tab'      => '',
			'text'     => 'Using the Pro Dimension Options',
			'video_id' => 'b3cV8gVf4lU',
			'type'     => 'video'
		);
		$this->fields['video_pro_shortcode_filter_options']             = array(
			'section'  => 'templating',
			'tab'      => '',
			'text'     => 'Pro\'s couponloop shortcode, filter bar, and template system to manage coupons',
			'video_id' => 'L9uf9q9JRtc',
			'type'     => 'video'
		);
		$this->fields['video_end_list_defaults'] = array(
			'section' => 'templating',
			'tab'     => '',
			'type'    => 'end_list'
		);

		/*************************************/




		$this->fields['header_video_guides_resources'] = array(
			'section' => '',
			'tab'     => '',
			'text'    => 'Resources',
			'type'    => 'heading'
		);
		$this->fields['video_pro_themeresguide']             = array(
			'section'  => '',
			'tab'      => '',
			'text'     => 'Intro to Pro\'s Themer\'s Guide',
			'video_id' => 'xEOdVUMFqg8',
			'type'     => 'video'
		);
		$this->fields['video_pro_documentation']             = array(
			'section' => '',
			'tab'     => '',
			'text'    => 'Documentation - Overview of CSS Selectors, Actions, Filters, Capabilities, and Post Types',
			'link'    => 'http://cctor.link/EsQPX',
			'type'    => 'links'
		);
		$this->fields['video_faq']             = array(
			'section' => '',
			'tab'     => '',
			'text'    => 'Frequently Asked Question - Pre Sales, License, Requirements, and Setup Information',
			'link'    => 'http://cctor.link/UzIZB"',
			'type'    => 'links'
		);
		$this->fields['video_pro_troubleshooting']             = array(
			'section' => '',
			'tab'     => '',
			'text'    => 'Guides - User Guides and Troubleshooting Guides',
			'link'    => 'http://cctor.link/eQAEC',
			'type'    => 'links'
		);
		$this->fields['video_pro_tutorials']             = array(
			'section' => '',
			'tab'     => '',
			'text'    => 'Tutorials - Customization Tutorials and More',
			'link'    => 'http://cctor.link/loHtW',
			'type'    => 'links'
		);
		$this->fields['video_end_list_resources'] = array(
			'section' => '',
			'tab'     => '',
			'text'    => '',
			'type'    => 'end_list'
		);

	}

	public function get_options() {

		return $this->fields;

	}

	/**
	 * Find string in multidimensional array
	 *
	 * Thanks to jwueller http://stackoverflow.com/a/4128377
	 *
	 * @param            $needle
	 * @param            $haystack
	 * @param bool|false $strict
	 *
	 * @return bool
	 */
	public function in_array_r( $needle, $haystack, $strict = false ) {
		foreach ( $haystack as $item ) {
			if ( ( $strict ? $item === $needle : $item == $needle ) || ( is_array( $item ) && $this->in_array_r( $needle, $item, $strict ) ) ) {
				return true;
			}
		}

		return false;
	}


	public function display_help( $section = null ) {

		if ( ! $section ) {
			return;
		}

		if ( 'all' != $section && ! $this->in_array_r( $section, $this->fields )  ) {
			return;
		}

		$screen = get_current_screen();

		if ( 'all' != $section ) {

			if ( 'cctor_coupon_page_coupon-options' == $screen->id ) {
				echo '</td></tr><tr valign="top"><td colspan="2">';
			}

			echo '<div class="cctor-meta-field-wrap cctor-section-help-container">';


			echo '<button aria-expanded="false" class="cctor-section-help-container-toggle" type="button">
				<span class="dashicons-before dashicons-editor-help">Help</span>
				<span class="dashicons toggle__arrow dashicons-arrow-down"></span>
			</button>';

			echo '<div class="cctor-section-help-slideout" id="" >';
		}
		foreach ( $this->fields as $help_field ) {

			if ( $help_field['type'] && ( 'all' == $section || $section == $help_field['tab'] || $section == $help_field['section'] ) ) {

				switch ( $help_field['type'] ) {

					case 'heading':
						?>

						<h4 class="coupon-heading"><?php echo esc_html( $help_field['text'] ); ?></h4>
						<ul>
						<?php break;

					case 'end_list':
						?>
						</ul>
						<?php break;

					case 'video':
							$rel = '';
						if ( 'all' == $section ) {
							$rel = 'how_to_videos';
						}
						?>
						<li><a class="cctor-support youtube_colorbox" href="http://www.youtube.com/embed/<?php echo esc_html( $help_field['video_id'] ); ?>?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="<?php echo esc_attr( $rel ); ?>"><?php echo esc_html( $help_field['text'] ); ?></a></li>

						<?php break;

					case 'links':
						?>
						<li><a class="cctor-support" target="_blank" href="<?php echo esc_url( $help_field['link'] ); ?>"><?php echo esc_html( $help_field['text'] ); ?></a></li>

						<?php break;

				}
			}
		}
		if ( 'all' != $section ) {
			echo '</div></div>';

			if ( 'cctor_coupon_page_coupon-options' == $screen->id ) {
				echo '</td></tr>';
			}
		}

	}


	public static function get_cctor_support_core_contact() {


		if ( class_exists( 'Coupon_Creator_Pro_Plugin' ) ) {
			$support_html = '
				<h4 class="coupon-heading">How to Contact Support</h4>
					<ul>
						<li>For Coupon Creator Pro users please use the <a class="cctor-support" target="_blank" href="http://cctor.link/pro-support">Support Form on CouponCreatorPlugin.com</a> to get direct support.</li>

						<li><br>Before contacting support please try to narrow or solve your issue by using one or all of these troubleshooting guides:
							<ul>
							<li><br><a class="cctor-support" target="_blank" href="http://cctor.link/pro-404">Troubleshooting 404 Errors</a></li>
							<li><a class="cctor-support" target="_blank" href="http://cctor.link/pro-tc">Troubleshooting Conflicts</a></li>
							<li><a class="cctor-support" target="_blank" href="http://cctor.link/pro-jscon">Troubleshooting Javascript Errors</a></li>
							</ul>
						</li>

					</ul>';
		} else {
			$support_html = '
			<h4 class="coupon-heading">How to Contact Support</h4>
			<ul>
				<li>Please use the <a target="_blank" class="cctor-support" href="http://cctor.link/ZlQvh">WordPress.org Support Forum for the Coupon Creator</a>.</li>
				<li><br>Before contacting support please try to narrow or solve your issue by using one or all of these troubleshooting guides:
					<ul>
					<li><br><a class="cctor-support" target="_blank" href="http://cctor.link/RgewD">Troubleshooting 404 Errors</a></li>
					<li><a class="cctor-support" target="_blank" href="http://cctor.link/4rMqT">Troubleshooting Conflicts</a></li>
					<li><a class="cctor-support" target="_blank" href="http://cctor.link/R7KRa">Troubleshooting Javascript Errors</a></li>
					</ul>
				</li>

			</ul>';

		}

		return $support_html;
	}

}
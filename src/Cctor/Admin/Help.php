<?php
//If Direct Access Kill the Script
if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ ) {
	die( 'Access denied.' );
}
/*
* Admin Help Class
*
*/


class Cctor__Coupon__Admin__Help extends Pngx__Admin__Help {

	//Help Fields array()
	protected $fields = array();

	/**
	 * Set Fields on Class Initialize
	 */
	public function __construct() {
		$this->set_help_fields();
	}

	/**
	 * Array of All Help Fields
	 */
	protected function set_help_fields() {

		//Content
		$this->fields['header_video_guides_content']  = array(
			'section' => '',
			'tab'     => 'content',
			'text'    => 'Coupon Content',
			'type'    => 'heading'
		);
		$this->fields['video_creating_coupon']        = array(
			'section'  => '',
			'tab'      => 'content',
			'text'     => 'Overview of Creating a Coupon',
			'video_id' => 'I1v9HxdIsSE',
			'type'     => 'video'
		);
		$this->fields['video_pro_columns_rows']       = array(
			'section'  => '',
			'tab'      => 'content',
			'text'     => 'Using Columns and Rows in the Visual Editor',
			'video_id' => 'w67yqCZXF6I',
			'pro'      => 'Pro',
			'type'     => 'video'
		);
		$this->fields['video_pro_view_shortcode']     = array(
			'section'  => '',
			'tab'      => 'content',
			'text'     => 'How to use the View Shortcodes and Deal Display Options',
			'video_id' => 'h0YVXi2vq3g',
			'pro'      => 'Pro',
			'type'     => 'video'
		);
		$this->fields['video_inserting_coupon']       = array(
			'section'  => '',
			'tab'      => 'content',
			'text'     => 'Inserter and Aligning Coupons',
			'video_id' => 'sozW-J-g3Ts',
			'pro'      => 'Pro',
			'type'     => 'video'
		);
		$this->fields['link_pro_hide_deal']           = array(
			'section' => '',
			'tab'     => 'content',
			'text'    => 'How to Hide the Deal in any Coupon View',
			'link'    => 'http://cctor.link/Ihoro',
			'pro'     => 'Pro',
			'type'    => 'links'
		);
		$this->fields['link_pro_shortcode_placement'] = array(
			'section' => '',
			'tab'     => 'content',
			'text'    => 'Where does the shortcode go?',
			'link'    => 'http://cctor.link/YZ6mK',
			'type'    => 'links'
		);
		$this->fields['link_pro_shortcode_sidebar']   = array(
			'section' => '',
			'tab'     => 'content',
			'text'    => 'How can I display coupons using the shortcode in a sidebar text widget?',
			'link'    => 'http://cctor.link/CKv4w',
			'type'    => 'links'
		);
		$this->fields['video_end_list_content']       = array(
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
			'pro'      => 'Pro',
			'type'     => 'video'
		);
		$this->fields['video_pro_background_img']  = array(
			'section'  => '',
			'tab'      => 'style',
			'text'     => 'Using the Background Image',
			'video_id' => 'vmViVkoQB0M?',
			'pro'      => 'Pro',
			'type'     => 'video'
		);
		$this->fields['link_pro_border_styles']    = array(
			'section' => '',
			'tab'     => 'style',
			'text'    => 'How to use different Coupon Borders',
			'link'    => 'http://cctor.link/Ew7eZ',
			'pro'     => 'Pro',
			'type'    => 'links'
		);
		$this->fields['link_pro_background_image'] = array(
			'section' => '',
			'tab'     => 'style',
			'text'    => 'Using the Background Image',
			'link'    => 'http://cctor.link/ykQml',
			'pro'     => 'Pro',
			'type'    => 'links'
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
		$this->fields['video_expiration_features']      = array(
			'section'  => '',
			'tab'      => 'expiration',
			'text'     => 'How to use the Expiration and Counter Features',
			'video_id' => 'YugCWPVigH8',
			'type'     => 'video'
		);
		$this->fields['link_pro_recurring_expiration']  = array(
			'section' => '',
			'tab'     => 'expiration',
			'text'    => 'How to setup or troubleshoot the Recurring Expiration in Pro',
			'link'    => 'http://cctor.link/Ih8Uc',
			'pro'     => 'Pro',
			'type'    => 'links'
		);
		$this->fields['video_pro_counter']              = array(
			'section'  => '',
			'tab'      => 'expiration',
			'text'     => 'Using the Counter',
			'video_id' => 'aVkwq8cIgB0',
			'pro'      => 'Pro',
			'type'     => 'video'
		);
		$this->fields['link_pro_counter']               = array(
			'section' => '',
			'tab'     => 'expiration',
			'text'    => 'Using the Counter Guide',
			'link'    => 'http://cctor.link/BpJhV',
			'pro'     => 'Pro',
			'type'    => 'links'
		);
		$this->fields['video_expiration_bulk_edit']     = array(
			'section'  => '',
			'tab'      => 'expiration',
			'text'     => 'How to Bulk or Quick Edit the Expiration or Counter Fields',
			'video_id' => 'IZDV5Mv5iGM',
			'pro'      => 'Pro',
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
			'pro'      => 'Pro',
			'type'     => 'video'
		);
		$this->fields['link_pro_image_size']             = array(
			'section' => '',
			'tab'     => 'image_coupon',
			'text'    => 'What is the size of the image coupon?',
			'link'    => 'http://cctor.link/cYn4L',
			'type'    => 'links'
		);
		$this->fields['video_end_list_image']            = array(
			'section' => '',
			'tab'     => 'image_coupon',
			'type'    => 'end_list'
		);

		//Links
		$this->fields['header_video_guides_links'] = array(
			'section' => '',
			'tab'     => 'links',
			'text'    => 'Coupon Link Attributes',
			'type'    => 'heading'
		);
		$this->fields['video_pro_popup']           = array(
			'section'  => '',
			'tab'      => 'links',
			'text'     => 'How to use the Popup Print View Feature',
			'video_id' => 'iThKkEgYBDE',
			'pro'      => 'Pro',
			'type'     => 'video'
		);
		$this->fields['link_pro_popup']            = array(
			'section' => '',
			'tab'     => 'links',
			'text'    => 'How to Open the Print Template in a Pop Up Box',
			'link'    => 'http://cctor.link/aZexm',
			'pro'     => 'Pro',
			'type'    => 'links'
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
			'pro'      => 'Pro',
			'type'     => 'video'
		);
		$this->fields['video_end_list_woo']      = array(
			'section' => '',
			'tab'     => 'cctor_woocommerce',
			'type'    => 'end_list'
		);


		//Option Defaults
		$this->fields['header_video_guides_defaults']  = array(
			'section' => 'defaults',
			'tab'     => '',
			'text'    => 'Coupon Defaults',
			'type'    => 'heading'
		);
		$this->fields['video_coupon_options_overview'] = array(
			'section'  => 'defaults',
			'tab'      => '',
			'text'     => 'An Overview of Coupon Creator Options',
			'video_id' => 'zq2dUCY6yQk',
			'type'     => 'video'
		);
		$this->fields['video_coupon_defaults']         = array(
			'section'  => 'defaults',
			'tab'      => '',
			'text'     => 'An Overview of Default Options',
			'video_id' => 'jHAKJSzwFMA',
			'type'     => 'video'
		);
		$this->fields['video_defaults_bulk_edit']      = array(
			'section'  => 'defaults',
			'tab'      => '',
			'text'     => 'How to Bulk or Quick Edit the Expiration or Counter Fields',
			'video_id' => 'IZDV5Mv5iGM',
			'pro'      => 'Pro',
			'type'     => 'video'
		);
		$this->fields['link_option_defaults']          = array(
			'section' => 'defaults',
			'tab'     => '',
			'text'    => 'A Guide to the Default Options',
			'link'    => 'http://cctor.link/r1MzQ',
			'type'    => 'links'
		);
		$this->fields['video_end_list_defaults']       = array(
			'section' => 'defaults',
			'tab'     => '',
			'type'    => 'end_list'
		);

		//Option Links
		$this->fields['header_video_guides_permalinks'] = array(
			'section' => 'permalinks',
			'tab'     => '',
			'text'    => 'Links Attributes / Permalinks Options',
			'type'    => 'heading'
		);
		$this->fields['video_link_options']             = array(
			'section'  => 'permalinks',
			'tab'      => '',
			'text'     => 'An Overview of the Link Options',
			'video_id' => '0uuFEjUaKII',
			'type'     => 'video'
		);
		$this->fields['link_pro_google']                = array(
			'section' => 'permalinks',
			'tab'     => '',
			'text'    => 'Setup Google Analytics for Print View',
			'link'    => 'http://cctor.link/iq81i',
			'pro'     => 'Pro',
			'type'    => 'links'
		);
		$this->fields['video_end_list_permalinks']      = array(
			'section' => 'permalinks',
			'tab'     => '',
			'type'    => 'end_list'
		);

		//Option Display
		$this->fields['header_video_guides_display'] = array(
			'section' => 'display',
			'tab'     => '',
			'text'    => 'Display Options',
			'type'    => 'heading'
		);
		$this->fields['video_display_options']       = array(
			'section'  => 'display',
			'tab'      => '',
			'text'     => 'An Overview of the Display Options',
			'video_id' => 'H26w4NmCuSw',
			'type'     => 'video'
		);
		$this->fields['video_pro_text-overrides']    = array(
			'section'  => 'display',
			'tab'      => '',
			'text'     => 'Using the Text Overrides',
			'video_id' => 'pFnp5VsfwUE',
			'pro'      => 'Pro',
			'type'     => 'video'
		);
		$this->fields['link_pro_wpautop']            = array(
			'section' => 'display',
			'tab'     => '',
			'text'    => 'How to turn on wpautop in the Coupon Creator',
			'link'    => 'http://cctor.link/iZTss',
			'type'    => 'links'
		);
		$this->fields['link_pro_search_results']     = array(
			'section' => 'display',
			'tab'     => '',
			'text'    => 'How can I prevent coupons from appearing in a site search?',
			'link'    => 'http://cctor.link/xyCL8',
			'type'    => 'links'
		);
		$this->fields['video_end_list_display']      = array(
			'section' => 'display',
			'tab'     => '',
			'type'    => 'end_list'
		);

		//Option Templating
		$this->fields['header_video_guides_templating']     = array(
			'section' => 'templating',
			'tab'     => '',
			'text'    => 'Templating Options',
			'type'    => 'heading'
		);
		$this->fields['video_pro_tempalate_overview']       = array(
			'section'  => 'templating',
			'tab'      => '',
			'text'     => 'An Overview of Template Options ',
			'video_id' => 'U014Ok9_TTY',
			'pro'      => 'Pro',
			'type'     => 'video'
		);
		$this->fields['video_pro_dimension']                = array(
			'section'  => 'templating',
			'tab'      => '',
			'text'     => 'Using the Dimension Options',
			'video_id' => 'b3cV8gVf4lU',
			'pro'      => 'Pro',
			'type'     => 'video'
		);
		$this->fields['video_pro_shortcode_filter_options'] = array(
			'section'  => 'templating',
			'tab'      => '',
			'text'     => 'Pro\'s couponloop shortcode, filter bar, and template system to manage coupons',
			'video_id' => 'L9uf9q9JRtc',
			'pro'      => 'Pro',
			'type'     => 'video'
		);
		$this->fields['link_pro_themers_guide']             = array(
			'section' => 'templating',
			'tab'     => '',
			'text'    => 'Pro\'s Themer\'s Guide',
			'link'    => 'http://cctor.link/wudM6',
			'pro'     => 'Pro',
			'type'    => 'links'
		);
		$this->fields['link_pro_category_template']         = array(
			'section' => 'templating',
			'tab'     => '',
			'text'    => 'Pro\'s Coupon Category Templates',
			'link'    => 'http://cctor.link/NNAh1',
			'pro'     => 'Pro',
			'type'    => 'links'
		);
		$this->fields['video_end_list_templating']          = array(
			'section' => 'templating',
			'tab'     => '',
			'type'    => 'end_list'
		);

		//License
		$this->fields['header_video_guides_license'] = array(
			'section' => 'license',
			'tab'     => '',
			'text'    => 'License Options',
			'type'    => 'heading'
		);
		$this->fields['video_pro_license']           = array(
			'section'  => 'license',
			'tab'      => '',
			'text'     => 'How to Activate Your License',
			'video_id' => '7kjuob7OBCI',
			'pro'      => 'Pro',
			'type'     => 'video'
		);
		$this->fields['link_pro_where_license']      = array(
			'section' => 'license',
			'tab'     => '',
			'text'    => 'Where is my license key in my account?',
			'link'    => 'http://cctor.link/KSxc8',
			'pro'     => 'Pro',
			'type'    => 'links'
		);
		$this->fields['link_pro_add_license']        = array(
			'section' => 'license',
			'tab'     => '',
			'text'    => 'Where do I add my license key?',
			'link'    => 'http://cctor.link/jh2dR',
			'pro'     => 'Pro',
			'type'    => 'links'
		);
		$this->fields['link_pro_upgrade_license']    = array(
			'section' => 'license',
			'tab'     => '',
			'text'    => 'How to Upgrade Your License',
			'link'    => 'http://cctor.link/EJrIr',
			'pro'     => 'Pro',
			'type'    => 'links'
		);
		$this->fields['link_pro_renew_license']      = array(
			'section' => 'license',
			'tab'     => '',
			'text'    => 'How do I renew my license for the Coupon Creator Pro?',
			'link'    => 'http://cctor.link/HIzXP',
			'pro'     => 'Pro',
			'type'    => 'links'
		);
		$this->fields['link_pro_transfer_license']   = array(
			'section' => 'license',
			'tab'     => '',
			'text'    => 'How do I transfer my license key to another site?',
			'link'    => 'http://cctor.link/6ux1M',
			'pro'     => 'Pro',
			'type'    => 'links'
		);
		$this->fields['link_pro_manual_update']      = array(
			'section' => 'license',
			'tab'     => '',
			'text'    => 'How do I manually update or downgrade a plugin?',
			'link'    => 'http://cctor.link/gvPQf',
			'pro'     => 'Pro',
			'type'    => 'links'
		);
		$this->fields['video_end_list_license']      = array(
			'section' => 'license',
			'tab'     => '',
			'type'    => 'end_list'
		);

		//Resources
		$this->fields['header_video_guides_resources'] = array(
			'section' => '',
			'tab'     => '',
			'text'    => 'Resources',
			'type'    => 'heading'
		);
		$this->fields['video_pro_themeresguide']       = array(
			'section'  => '',
			'tab'      => '',
			'text'     => 'Intro to Pro\'s Themer\'s Guide',
			'video_id' => 'xEOdVUMFqg8',
			'type'     => 'video'
		);
		$this->fields['video_pro_documentation']       = array(
			'section' => '',
			'tab'     => '',
			'text'    => 'Documentation - Overview of CSS Selectors, Actions, Filters, Capabilities, and Post Types',
			'link'    => 'http://cctor.link/EsQPX',
			'type'    => 'links'
		);
		$this->fields['video_faq']                     = array(
			'section' => '',
			'tab'     => '',
			'text'    => 'Frequently Asked Question - Pre Sales, License, Requirements, and Setup Information',
			'link'    => 'http://cctor.link/UzIZB"',
			'type'    => 'links'
		);
		$this->fields['video_pro_troubleshooting']     = array(
			'section' => '',
			'tab'     => '',
			'text'    => 'Guides - User Guides and Troubleshooting Guides',
			'link'    => 'http://cctor.link/eQAEC',
			'type'    => 'links'
		);
		$this->fields['video_pro_tutorials']           = array(
			'section' => '',
			'tab'     => '',
			'text'    => 'Tutorials - Customization Tutorials and More',
			'link'    => 'http://cctor.link/loHtW',
			'type'    => 'links'
		);
		$this->fields['video_end_list_resources']      = array(
			'section' => '',
			'tab'     => '',
			'text'    => '',
			'type'    => 'end_list'
		);

	}

	/**
	 * Coupon Creator Help Tab Support Links
	 *
	 * @return string
	 */
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
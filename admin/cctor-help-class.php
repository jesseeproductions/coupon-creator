<?php
//If Direct Access Kill the Script
if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ ) {
	die( 'Access denied.' );
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


		$this->fields['header_defaults'] = array(
			'section' => 'defaults',
			'tab'     => 'content',
			'text'    => '',
			'type'    => 'message'
		);

		$this->fields['video_creating_coupon'] = array(
			'section'  => 'defaults',
			'tab'      => 'content',
			'text'     => 'Creating a Coupon',
			'video_id' => 'tIau3ZNjoeI',
			'type'     => 'video'
		);

	}

	public function get_options() {

		return $this->fields;

	}

	/**
	 * Find string in multidimensional array
	 *
	 * Thanks to jwueller http://stackoverflow.com/a/4128377
	 * @param            $needle
	 * @param            $haystack
	 * @param bool|false $strict
	 *
	 * @return bool
	 */
	public function in_array_r($needle, $haystack, $strict = false) {
	    foreach ($haystack as $item) {
	        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && $this->in_array_r($needle, $item, $strict))) {
	            return true;
	        }
	    }

	    return false;
	}

	public function display_help( $section=null ) {

		if ( !$section ) {
			return;
		}

		if ( ! $this->in_array_r( $section, $this->fields ) ) {
			return;
		}

		echo '<div class="cctor-meta-field-wrap cctor-section-help-container">';


		echo '<button aria-expanded="false" class="cctor-section-help-container-toggle" type="button">
				<span class="dashicons-before dashicons-editor-help">Help</span>
				<span class="dashicons toggle__arrow dashicons-arrow-down"></span>
			</button>';

		echo '<div class="cctor-section-help-slideout" id="" >';

		foreach ( $this->fields as $help_field ) {

			if ( $help_field['type'] && $section == $help_field['tab'] ) {

				switch ( $help_field['type'] ) {

					case 'heading':
						?>

						<h4 class="coupon-heading"><?php echo $help_field['text']; ?></h4>

						<?php break;

					case 'video':
						?>

						<a class="cctor-support youtube_colorbox"
						   href="http://www.youtube.com/embed/<?php echo $help_field['video_id']; ?>?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1"
						   rel="how_to_videos"><?php echo $help_field['text']; ?></a>

						<?php break;


				}

			}


		}

		echo '</div></div>';

		echo '<script>

				jQuery( function ( $ ) {

					var $help_container = $( ".cctor-section-help-container-toggle" );
					var $help_videos = $( ".cctor-section-help-slideout" );

					$help_container.on( "click", function ( event ) {
							event.preventDefault();
							$( $help_videos ).animate({
					            height: "toggle",
					            opacity: "toggle"
					        }, "slow");
					} );

				} );

			</script>';
	}

	/*
	* Get Support Information for Options and Meta Field
	* @version 2.0
	*/
	public static function get_cctor_support_core_infomation() {

		$support_html = '

		<h4 class="coupon-heading">Video Guides</h4>
		<ul>
			<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/tIau3ZNjoeI?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Creating a Coupon</a></li>
			<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/A1mULc_MyHs?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Creating an Image Coupon</a></li>
			<li><a  class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/sozW-J-g3Ts?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Inserter and Aligning Coupons</a></li>
			<li><a class="cctor-support youtube_colorbox" href="http://www.youtube.com/embed/h3Zg8rxIDdc?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Using the Coupon Creator Options</a></li>
		</ul>

		<h4 class="coupon-heading">Pro Coupons</h4>
		<ul>
			<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/FI218DxXnrY?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Creating a Pro Coupon</a></li>
			<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/SqAG3s1FniA?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Creating a Pro Image Coupon</a></li>
			<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/EQRv8g2nmuE?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">How to use the Border Styles</a></li>
			<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/JR4GA4lsOB0?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">How to use Recurring Expiration</a></li>
			<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/w67yqCZXF6I?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Using Columns and Rows in the Visual Editor</a></li>
		      <li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/iThKkEgYBDE?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">How to use the Popup Print View Feature</a></li>
                  <li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/h0YVXi2vq3g?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">How to use the View Shortcodes and Deal Display Options</a></li>
                  <li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/aVkwq8cIgB0?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Using the Pro Counter</a></li>
                  <li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/vmViVkoQB0M?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Using the Pro Background Image</a></li>
                   <li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/xH3GmKPzQKc?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">How to Create a WooCommerce Coupon</a></li>
		</ul>
		<h4 class="coupon-heading">Pro Options</h4>
		<ul>
			<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/8L0JmSB_V-E?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Using the Pro Options</a></li>
			<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/b3cV8gVf4lU?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Using the Pro Dimension Options</a></li>
			<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/pFnp5VsfwUE?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Using the Pro Text Overrides</a></li>
		</ul>
		<h4 class="coupon-heading">Pro Templates & Customizations</h4>
		<ul>
			<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/L9uf9q9JRtc?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Pro\'s couponloop shortcode, filter bar, and template system to manage coupons</a></li>
			<li><a class="cctor-support youtube_colorbox"  href="http://www.youtube.com/embed/xEOdVUMFqg8?hd=1&autohide=1&rel=0&showsearch=0&autoplay=1" rel="how_to_videos">Intro to Pro\'s Themer\'s Guide</a></li>
		</ul>

		<h4 class="coupon-heading">Resources</h4>
		<ul>
			<li><a class="cctor-support" target="_blank" href="http://cctor.link/EsQPX">Documentation</a> - Overview of CSS Selectors, Actions, Filters, Capabilities, and Post Types</li>
			<li><a class="cctor-support" target="_blank" href="http://cctor.link/UzIZB">Frequently Asked Question</a> - Pre Sales, License, Requirements, and Setup Information</li>
			<li><a class="cctor-support" target="_blank" href="http://cctor.link/eQAEC">Guides</a> - User Guides and Troubleshooting Guides</li>
			<li><a class="cctor-support" target="_blank" href="http://cctor.link/loHtW">Tutorials</a> - Customization Tutorials and More</li>
		</ul>';

		return $support_html;
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


	/***************************************************************************/

	/*
	* Get Support Information for Options and Meta Field
	* @version 2.0
	*/
	public static function get_cctor_support_pro_contact() {

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

		return $support_html;
	}


}
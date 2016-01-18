<?php
/**
 * Templating functionality for Tribe Events Calendar
 */

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( class_exists( 'CCTOR__Coupon__Templates' ) ) {
	return;
}


/**
 * Template Class
 */
class CCTOR__Coupon__Templates {

	/**
	 * Name of template being used
	 */
	protected static $template = false;

	/**
	 * @var bool Is wp_head complete?
	 */
	public static $wpHeadComplete = false;

	/**
	 * Template Init
	 */
	public static function init() {

		// Choose the wordpress theme template to use
		add_filter( 'template_include', array( __CLASS__, 'template_chooser' ) );

		add_action( 'wp_head', array( __CLASS__, 'wpHeadFinished' ), 999 );

	}

	/**
	 * Check for main loop
	 *
	 * @param WP_Query $query
	 *
	 * @return bool
	 */
	protected static function is_main_loop( $query ) {
		return $query->is_main_query();
	}

	/**
	 * Pick the correct template to include
	 *
	 * @param string $template Path to template
	 *
	 * @return string Path to template
	 */
	public static function template_chooser( $template ) {

		do_action( 'cctor_pro_template_chooser', $template );

		// not a coupon then return
		if ( ! cctor_is_coupon_query() ) {
			return $template;
		}

		// filter body class with custom name for template
		add_filter( 'body_class', array( __CLASS__, 'template_body_class' ) );

		// user has selected a page/custom page template

		$cctor_template = cctor_options( 'cctor_template', true, 'default_theme' );

		//echo $cctor_template . ' template choosen<br>';
		if ( $cctor_template != '' ) {

			if ( ! is_single() || ! post_password_required() ) {
				echo '<br>loading! ';
				add_action( 'loop_start', array( __CLASS__, 'setup_coupon_template' ) );
			}
			//echo '<br>template 1 ' . $template;
			$template = locate_template( $cctor_template == 'default_theme' ? 'page.php' : $cctor_template );
			//echo '<br>template 2 ' . $template;
			if ( $template == '' ) {
				$template = get_index_template();
				//echo '<br>template 4 ' . $template;
			}
		} else {
			$template = self::getTemplateHierarchy( 'taxonomy-cctor-coupon-category' );
			//echo '<br>template 5 ' . $template;
		}

		//Check if the taxonomy is being viewed
		//if ( is_tax( 'cctor_coupon_category' ) && ! self::wpse50201_is_template( $template ) ) {
		//$template = CCTOR_PRO_PATH . 'public/templates/taxonomy-cctor-coupon-category.php';
		//}

		self::$template = $template;
		//echo '<br>template 6 ' . $template;

		return $template;
	}

	protected function wpse50201_is_template( $template_path ) {

		//Get template name
		$template = basename( $template_path );

		//Check if template is taxonomy-cctor-coupon-category.php
		//Check if template is taxonomy-cctor_coupon_category-{term-slug}.php
		if ( 1 == preg_match( '/^taxonomy-cctor_coupon_category((-(\S*))?).php/', $template ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Determine when wp_head has been triggered.
	 */
	public static function wpHeadFinished() {
		self::$wpHeadComplete = true;
	}

	/**
	 * This adds our coding to the template
	 *
	 * @param WP_Query $query
	 */
	public static function setup_coupon_template( $query ) {
		echo 'did we start setup_cctor_template';
		if ( self::is_main_loop( $query ) && self::$wpHeadComplete ) {

			echo 'did we setup_cctor_template';
			// on the_content, load our events template
			add_filter( 'the_content', array( __CLASS__, 'load_coupon_into_page_template' ) );

			// do once, so remove
			remove_action( 'loop_start', array( __CLASS__, 'setup_coupon_template' ) );
		}
	}

	/**
	 * Loads the contents into the page template
	 *
	 * @return string Page content
	 */
	public static function load_coupon_into_page_template() {
		// only once or you will create endless amounts of support
		remove_filter( 'the_content', array( __CLASS__, 'load_coupon_into_page_template' ) );

		//self::restoreQuery();

		ob_start();

		//tribe_get_view();

		echo 'here';

		$contents = ob_get_contents();

		ob_end_clean();

		// make sure the loop ends after our template is included
		//if ( ! is_404() ) {
		//self::endQuery();
		//}

		return $contents;
	}

	/**
	 * Loads coupon template
	 * hierarchy:
	 * 1) child theme
	 * 2) parent template
	 * 3) plugin resources
	 *
	 * @param string $template template file to search for
	 * @param array  $args     additional arguments to affect the template path
	 *                         - namespace
	 *                         - plugin_path
	 *                         - disable_view_check - bypass the check to see if the view is enabled
	 *
	 * @return template path
	 **/
	public static function getTemplateHierarchy( $template, $args = array() ) {

		// append .php to file name
		if ( substr( $template, - 4 ) != '.php' ) {
			$template .= '.php';
		}

		// Allow base path for templates to be filtered
		$template_base_paths = apply_filters( 'cctor_coupon_template_paths', ( array ) CCTOR_PRO_PATH );

		$file = false;

		// check for overrides
		if ( locate_template( array( 'cctor-coupons/' ) ) ) {
			$overrides_exist = true;
		} else {
			$overrides_exist = false;
		}

		if ( $overrides_exist ) {
			// check the theme for template
			$file = locate_template( array( 'cctor-coupons/' . $template ), false, false );
		}

		// not found in theme then go to the plugin
		if ( ! $file ) {

			foreach ( $template_base_paths as $template_base_path ) {

				// make sure directories are trailingslashed
				$template_base_path = ! empty( $template_base_path ) ? trailingslashit( $template_base_path ) : $template_base_path;

				$file = $template_base_path . 'public/templates/' . $template;

				$file = apply_filters( 'cctor_coupon_template', $file, $template );

				// return the first one found
				if ( file_exists( $file ) ) {
					break;
				} else {
					$file = false;
				}
			}
		}

		return apply_filters( 'cctor_coupon_template_' . $template, $file );
	}

	/**
	 * Include page template body class
	 *
	 * @param array $classes List of includes to filter
	 *
	 * @return mixed
	 */
	public static function template_body_class( $classes ) {

		$template_filename = basename( self::$template );

		if ( $template_filename == 'taxonomy-cctor-coupon-category.php' ) {
			$classes[] = 'coupon-category-template';
		} else {
			$classes[] = 'page-template-' . sanitize_title( $template_filename );
		}

		return $classes;
	}

}

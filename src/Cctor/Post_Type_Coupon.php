<?php


/**
 * Class Cctor_Coupon_Post_Type_Coupon
 */
class Cctor__Coupon__Post_Type_Coupon {

	public $post_type;
	public $text_domain;
	public $taxonomy;

	public $singular_coupon_label;
	public $plural_coupon_label;
	public $singular_coupon_label_lowercase;
	public $plural_coupon_label_lowercase;
	public $singular_category_label;
	public $singular_category_label_lowercase;
	public $plural_category_label;
	public $plural_category_label_lowercase;

	/**
	 * Cctor__Coupon__Post_Type_Coupon constructor.
	 *
	 * @param $post_type
	 * @param $taxonomy
	 * @param $text_domain
	 */
	public function __construct( $post_type, $taxonomy, $text_domain ) {

		$this->post_type   = $post_type;
		$this->taxonomy    = $taxonomy;
		$this->text_domain = $text_domain;

		$this->singular_coupon_label           = $this->get_coupon_label_singular();
		$this->singular_coupon_label_lowercase = $this->get_coupon_label_singular_lowercase();
		$this->plural_coupon_label             = $this->get_coupon_label_plural();
		$this->plural_coupon_label_lowercase   = $this->get_coupon_label_plural_lowercase();

		$this->singular_category_label           = $this->get_coupon_category_label_singular();
		$this->singular_category_label_lowercase = $this->get_coupon_category_label_singular_lowercase();
		$this->plural_category_label             = $this->get_coupon_category_label_plural();
		$this->plural_category_label_lowercase   = $this->get_coupon_category_label_plural_lowercase();

	}

	/**
	 * Register Post Type and Taxonomy on Init
	 *
	 * @since 3.0
	 *
	 */
	public function register() {
		$this->register_post_types();
		$this->register_taxonomies();
	}

	/**
	 * Returns the string to be used as the taxonomy slug.
	 *
	 * @return string
	 */
	public function get_coupon_slug() {

		/**
		 * Provides an opportunity to modify the coupon slug.
		 *
		 * @var string
		 */
		return apply_filters( 'cctor_coupon_slug', sanitize_title( cctor_options( 'cctor_coupon_base', false, __( $this->post_type, 'slug', $this->text_domain ) ) ) );
	}

	/**
	 * Allow users to specify their own singular label for Coupons
	 *
	 * @since 3.0
	 *
	 * @return string
	 */
	public function get_coupon_label_singular() {
		return apply_filters( 'cctor_coupon_label_singular', esc_html__( 'Coupon', $this->text_domain ) );
	}

	/**
	 * Get Coupon Label Singular lowercase
	 *
	 * @since 3.0
	 *
	 * Returns the singular version of the Coupon Label
	 *
	 * @return string
	 */
	function get_coupon_label_singular_lowercase() {
		return apply_filters( 'cctor_coupon_label_singular_lowercase', esc_html__( 'coupon', $this->text_domain ) );
	}

	/**
	 * Allow users to specify their own plural label for Coupons
	 *
	 * @since 3.0
	 *
	 * @return string
	 */
	public function get_coupon_label_plural() {
		return apply_filters( 'cctor_coupon_label_plural', esc_html__( 'Coupons', $this->text_domain ) );
	}

	/**
	 * Get Coupon Label Plural lowercase
	 *
	 * @since 3.0
	 *
	 * Returns the plural version of the Coupon Label
	 *
	 * @return string
	 */
	function get_coupon_label_plural_lowercase() {
		return apply_filters( 'cctor_coupon_label_plural_lowercase', esc_html__( 'coupons', $this->text_domain ) );
	}

	/**
	 * Allow users to specify their own singular label for Coupon Category
	 *
	 * @since 3.0
	 *
	 * @return string
	 */
	public function get_coupon_category_label_singular() {
		return apply_filters( 'cctor_coupon_category_label_singular', esc_html__( 'Coupon Category', $this->text_domain ) );
	}

	/**
	 * Allow users to specify their own lowercase singular label for Coupon Category
	 *
	 * @return string
	 */
	public function get_coupon_category_label_singular_lowercase() {
		return apply_filters( 'cctor_coupon_category_label_singular_lowercase', esc_html__( 'coupon category', $this->text_domain ) );
	}

	/**
	 * Allow users to specify their own plural label for Coupon Categories
	 *
	 * @since 3.0
	 *
	 * @return string
	 */
	public function get_coupon_category_label_plural() {
		return apply_filters( 'cctor_coupon_category_plural', esc_html__( 'Coupon Categories', $this->text_domain ) );
	}

	/**
	 * Allow users to specify their own plural label for coupon categories
	 *
	 * @since 3.0
	 *
	 * @return string
	 */
	public function get_coupon_category_label_plural_lowercase() {
		return apply_filters( 'cctor_coupon_category_plural_lowercase', esc_html__( 'coupon categories', $this->text_domain ) );
	}


	/**
	 * Returns the string to be used as the taxonomy slug.
	 *
	 * @return string
	 */
	public function get_category_slug() {

		/**
		 * Provides an opportunity to modify the category slug.
		 *
		 * @var string
		 */
		return apply_filters( 'cctor_category_slug', sanitize_title( cctor_options( 'cctor_coupon_category_base', false, __( 'coupon-category', 'slug', $this->text_domain ) ) ) );
	}

	/**
	 * Get the coupon post type
	 *
	 * @return string
	 */
	public function get_coupon_post_type() {
		return $this->post_type;
	}

	/**
	 * Get the coupon taxonomy
	 *
	 * @return string
	 */
	public function get_coupon_taxonomy() {
		return $this->taxonomy;
	}

	/**
	 * Register the post types.
	 *
	 * @since 3.0
	 *
	 */
	public function register_post_types() {

		// Register Coupon Post Type and customize labels
		//  @formatter:off
			$labels = Pngx__Register_Post_Type::generate_post_type_labels(
				$this->singular_coupon_label,
				$this->plural_coupon_label,
				$this->singular_coupon_label_lowercase,
				$this->plural_coupon_label_lowercase,
				$this->text_domain
			);

			//$pngx_cpt = new Pngx__Register_Post_Type();

			pngx( Pngx__Register_Post_Type::class )->register_post_types(
				$this->post_type,
				$this->post_type,
				$this->singular_coupon_label,
				$labels,
				$this->get_coupon_slug(),
				$this->text_domain,
				array(
					'supports'  => array( 'title', 'coupon_creator_meta_box' ),
					//'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg width="20" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path fill="black" d="M1591 1448q56 89 21.5 152.5t-140.5 63.5h-1152q-106 0-140.5-63.5t21.5-152.5l503-793v-399h-64q-26 0-45-19t-19-45 19-45 45-19h512q26 0 45 19t19 45-19 45-45 19h-64v399zm-779-725l-272 429h712l-272-429-20-31v-436h-128v436z"/></svg>')
					'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode('
					<svg
					    version="1.1"
					    id="coupon-creator-admin-icon"
					    xmlns="http://www.w3.org/2000/svg"
					    x="0px"
					    y="0px"
					    viewBox="0 0 25 25"
					    width="20px"
					    height="20px"
					>
					<g  fill="black">
						<path d="M0,0v25h25V0H0z M3.5,9.8h2v5.3h-2V9.8z M7.5,21.5h-4v-4h2v2h2V21.5z M7.5,5.5h-2v2h-2v-4h4V5.5z M15.1,21.5H9.8v-2h5.3
							V21.5z M13,9.8c0-0.2-0.2-0.3-0.4-0.3c-0.3,0-0.4,0.1-0.5,0.3C12,10,12,10.2,12,10.6v3.9c0,0.3,0,0.6,0.1,0.8
							c0.1,0.2,0.3,0.3,0.5,0.3c0.3,0,0.4-0.1,0.5-0.3c0.1-0.2,0.1-0.4,0.1-0.8v-0.9H15v0.7c0,0.8-0.2,1.4-0.5,1.8
							c-0.4,0.4-1,0.7-1.9,0.7c-1,0-1.6-0.2-2-0.7C10.2,15.6,10,14.9,10,14v-3c0-0.9,0.2-1.6,0.6-2.1c0.4-0.5,1-0.7,1.9-0.7
							s1.5,0.2,1.9,0.6c0.4,0.4,0.5,1,0.5,1.8v0.7h-1.8v-0.8C13.1,10.2,13.1,9.9,13,9.8z M15.1,5.5H9.8v-2h5.3V5.5z M21.5,21.5h-4v-2h2
							v-2h2V21.5z M21.5,15.1h-2V9.8h2V15.1z M21.5,7.5h-2v-2h-2v-2h4V7.5z"/>
					</g>
					</svg>
					')
				)
			);

			new Pngx__Register_Post_Type(
				$this->post_type,
				__( 'Enter Coupon Admin Title', $this->text_domain )
			);
			// @formatter:on
	}

	/**
	 * Register the taxonomies.
	 */
	public function register_taxonomies() {

		// Register Coupon Taxonomu
		// @formatter:off
			$labels = Pngx__Register_Taxonomy::generate_taxonomy_labels(
				$this->singular_category_label,
				$this->singular_category_label_lowercase,
				$this->plural_category_label,
				$this->plural_category_label_lowercase,
				$this->text_domain
			);

			Pngx__Register_Taxonomy::register_taxonomy(
				$this->taxonomy,
				$this->post_type,
				$labels,
				$this->get_category_slug(),
				false
			);
			// @formatter:on
	}

}

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
	 * @since TBD
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
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_coupon_label_singular() {
		return apply_filters( 'cctor_coupon_label_singular', esc_html__( 'Coupon', $this->text_domain ) );
	}

	/**
	 * Get Coupon Label Singular lowercase
	 *
	 * @since TBD
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
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_coupon_label_plural() {
		return apply_filters( 'cctor_coupon_label_plural', esc_html__( 'Coupons', $this->text_domain ) );
	}

	/**
	 * Get Coupon Label Plural lowercase
	 *
	 * @since TBD
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
	 * @since TBD
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
	 * @since TBD
	 *
	 * @return string
	 */
	public function get_coupon_category_label_plural() {
		return apply_filters( 'cctor_coupon_category_plural', esc_html__( 'Coupon Categories', $this->text_domain ) );
	}

	/**
	 * Allow users to specify their own plural label for coupon categories
	 *
	 * @since TBD
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
	 * @since TBD
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

			Pngx__Register_Post_Type::register_post_types(
				$this->post_type,
				$this->post_type,
				$this->singular_coupon_label,
				$labels,
				$this->get_coupon_slug(),
				$this->text_domain,
				array(
					'supports'  => array( 'title', 'coupon_creator_meta_box' ),
					'menu_icon' => pngx( 'cctor' )->resource_url . 'images/cctor-icon.svg',
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

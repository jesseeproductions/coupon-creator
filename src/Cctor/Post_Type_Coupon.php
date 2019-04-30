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
					//'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg width="20" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path fill="black" d="M1591 1448q56 89 21.5 152.5t-140.5 63.5h-1152q-106 0-140.5-63.5t21.5-152.5l503-793v-399h-64q-26 0-45-19t-19-45 19-45 45-19h512q26 0 45 19t19 45-19 45-45 19h-64v399zm-779-725l-272 429h712l-272-429-20-31v-436h-128v436z"/></svg>')
					'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode('
					<svg
						version="1.1"
						id="coupon-creator-icon"
						xmlns="http://www.w3.org/2000/svg"
						xmlns:xlink="http://www.w3.org/1999/xlink"
						x="0px" 
						y="0px"
						viewBox="0 0 24 24"
						width="20px" 
						height="20px"
					>
					<g fill="black">
						<g>
							<g >
								<polygon points="21,21 17,21 17,19 19,19 19,17 21,17 			"/>
								<rect x="9.3" y="19" width="5.3" height="2"/>
								<polygon points="7,21 3,21 3,17 5,17 5,19 7,19 			"/>
								<rect x="3" y="9.3" width="2" height="5.3"/>
								<polygon points="5,7 3,7 3,3 7,3 7,5 5,5 			"/>
								<rect x="9.3" y="3" width="5.3" height="2"/>
								<polygon points="21,7 19,7 19,5 17,5 17,3 21,3 			"/>
								<rect x="19" y="9.3" width="2" height="5.3"/>
							</g>
						</g>
						<g>
							<path fill="black" d="M10.1,15.6c-0.4-0.5-0.6-1.2-0.6-2.1v-3c0-0.9,0.2-1.6,0.6-2.1c0.4-0.5,1-0.7,1.9-0.7c0.9,0,1.5,0.2,1.9,0.6
								c0.4,0.4,0.5,1,0.5,1.8v0.7h-1.8V10c0-0.3,0-0.6-0.1-0.7C12.5,9.1,12.3,9,12.1,9c-0.3,0-0.4,0.1-0.5,0.3c-0.1,0.2-0.1,0.4-0.1,0.8
								V14c0,0.3,0,0.6,0.1,0.8c0.1,0.2,0.3,0.3,0.5,0.3c0.3,0,0.4-0.1,0.5-0.3c0.1-0.2,0.1-0.4,0.1-0.8v-0.9h1.8v0.7
								c0,0.8-0.2,1.4-0.5,1.8c-0.4,0.4-1,0.7-1.9,0.7C11.1,16.3,10.5,16.1,10.1,15.6z"/>
						</g>
					</g>
					</svg>')
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

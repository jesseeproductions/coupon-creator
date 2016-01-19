<?php
/*
* Coupon Creator Custom Taxonomy
* @version 1.70
*/
//If Direct Access Kill the Script
if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ ) {
	die( 'Access denied.' );
}

if ( ! class_exists( 'Coupon_Creator_Taxonomy_Class' ) ) {
	/*
	* Coupon Creator Admin Class
	* @version 1.90
	*/
	class Coupon_Creator_Taxonomy_Class {

		public static function cctor_create_taxonomies() {
			/*
			* Create Custom Taxonomy
			* @version 1.40
			*/
			$slug = cctor_options( 'cctor_coupon_category_base', false, 'coupon-category' );

			$labels = array(
				'name'              => _x( 'Coupon Category', 'coupon-creator' ),
				'singular_name'     => _x( 'Coupon Categories', 'coupon-creator' ),
				'search_items'      => _x( 'Search Coupon Categories', 'coupon-creator' ),
				'all_items'         => _x( 'All Coupon Categories', 'coupon-creator' ),
				'parent_item'       => _x( 'Parent Coupon Categories', 'coupon-creator' ),
				'parent_item_colon' => _x( 'Parent Coupon Categories:', 'coupon-creator' ),
				'edit_item'         => _x( 'Edit Coupon Categories', 'coupon-creator' ),
				'update_item'       => _x( 'Update Coupon Categories', 'coupon-creator' ),
				'add_new_item'      => _x( 'Add New Coupon Category', 'coupon-creator' ),
				'new_item_name'     => _x( 'New Coupon Categories Name', 'coupon-creator' ),
				'menu_name'         => _x( 'Coupon Category', 'coupon-creator' ),
				'popular_items'              => _x( 'Popular Coupons', 'coupon-creator' ),
				'separate_items_with_commas' => _x( 'Separate coupons with commas', 'coupon-creator' ),
				'add_or_remove_items'        => _x( 'Add or remove coupons', 'coupon-creator' ),
				'choose_from_most_used'      => _x( 'Choose from the most used coupons', 'coupon-creator' ),
			);

			$args = array(
				'labels'            => $labels,
				'public'            => true,
				'show_in_nav_menus' => false,
				'show_ui'           => true,
				'show_tagcloud'     => false,
				'show_admin_column' => false,
				'hierarchical'      => true,
				'rewrite'           => array( 'slug' =>  $slug, 'with_front' => true ),
				'query_var'         => true
			);

			register_taxonomy( 'cctor_coupon_category', array( 'cctor_coupon' ), $args );

		} //End coupon_creator_create_taxonomies

	} //end Coupon_Creator_Taxonomy_Class Class

} // class_exists( 'Coupon_Creator_Taxonomy_Class' )
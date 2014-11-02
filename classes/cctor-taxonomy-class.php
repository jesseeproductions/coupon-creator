<?php
/*
* Coupon Creator Custom Taxonomy
* @version 1.70
*/
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

if ( ! class_exists( 'Coupon_Creator_Taxonomy_Class' ) ) {	
	/*
	* Coupon Creator Admin Class
	* @version 1.90
	*/
	class Coupon_Creator_Taxonomy_Class {
	
		/*
		* Coupon_Creator_Taxonomy_Class Construct
		* @version 1.90
		*/
		public function __construct() {
		
			add_action( 'init', array( __CLASS__, 'cctor_create_taxonomies' ) );
			
		}
	
		public static function cctor_create_taxonomies() {
			/*
			* Create Custom Taxonomy
			* @version 1.40
			*/
			$slug = get_option( 'cctor_coupon_category_base' );
			$slug = empty( $slug ) ? _x( 'cctor_coupon_category', 'slug', 'coupon_creator' ) : $slug;

			register_taxonomy( 'cctor_coupon_category', 'cctor_coupon', 
				array( 
					'hierarchical'		 => true,
					'public' 			 => true,
					'labels'			 => array(
						'name' => _x( 'Coupon Category', 'coupon_creator' ),
						'singular_name' => _x( 'Coupon Categories', 'coupon_creator' ),
						'search_items' => _x( 'Search Coupon Categories', 'coupon_creator' ),
						'all_items' => _x( 'All Coupon Categories', 'coupon_creator' ),
						'parent_item' => _x( 'Parent Coupon Categories', 'coupon_creator' ),
						'parent_item_colon' => _x( 'Parent Coupon Categories:', 'coupon_creator' ),
						'edit_item' => _x( 'Edit Coupon Categories', 'coupon_creator' ),
						'update_item' => _x( 'Update Coupon Categories', 'coupon_creator' ),
						'add_new_item' => _x( 'Add New Coupon Category', 'coupon_creator' ),
						'new_item_name' => _x( 'New Coupon Categories Name', 'coupon_creator' ),
						'menu_name' => _x( 'Coupon Category', 'coupon_creator' ),				
					),
					'show_ui'     		 => true, 
					'show_in_nav_menus'	 => false,
					'show_tagcloud' 	 => false,
					'query_var'  		 => true, 
					'rewrite'    		 => array( 'slug' => $slug ),
				)
		);
	
} //End coupon_creator_create_taxonomies

	} //end Coupon_Creator_Taxonomy_Class Class
	
} // class_exists( 'Coupon_Creator_Taxonomy_Class' )
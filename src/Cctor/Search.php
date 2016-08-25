<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * Modify WordPress Search
 *
 *
 */
class Cctor__Coupon__Search {

	public static function remove_coupon_from_search( $query ) {

		$search = cctor_options( 'coupon-search' );

		if ( ! $search && $query->is_search && ! empty( $query->query['s'] ) && ! is_admin() ) {

			/**
			 * Fires on search results
			 *
			 * The hook includes the $query global and fires on
			 * search results, but can be used to disable the coupon creator
			 * from specifying the post types for search
			 *
			 * @since 2.3.0
			 */
			do_action( 'cctor_inside_remove_coupons_from _search', $query );

			//bbpress is exclude_from_search so add this check to prevent coupon creator from interfering
			if ( class_exists( 'bbPress' ) && ( is_bbpress() || bbp_is_search_results() ) ) {
				return $query;
			}

			$post_types = get_post_types( array( 'public' => true, 'exclude_from_search' => false ), 'objects' );

			$searchable_cpt = array();
			// Add available post types, but remove coupons
			if ( $post_types ) {
				foreach ( $post_types as $type ) {
					if ( 'cctor_coupon' != $type->name ) {
						$searchable_cpt[] = $type->name;
					}
				}
			}

			/**
			 * Filter the searchable custom post types
			 *
			 * @param array $searchable_cpt an array of post types to include in search
			 *
			 */
			apply_filters( 'cctor_filter_searchable_post_types', $searchable_cpt );

			$query->set( 'post_type', $searchable_cpt );

		}

		return $query;
	}

}
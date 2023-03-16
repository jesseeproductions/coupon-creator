<?php
/**
 * Coupon Creator Uninstall
 *
 * Uninstalling Coupon Creator Options, Coupons, Roles, Etc...
 *
 * @package Cctor\Uninstaller
 *
 * @since 3.4.0
 */

defined( 'CCTOR_UNINSTALL_PLUGIN' ) || exit;

global $wpdb, $wp_version;

// Include the Plugin Engine Uninstall.
require_once dirname(__FILE__, 4 ) . '/plugin-engine/src/Pngx/Install/uninstall.php';

/*
 * Remove only if CCTOR_REMOVE_ALL_DATA is set in the wp-config.php.
 */
if ( defined( 'CCTOR_REMOVE_ALL_DATA' ) && true === CCTOR_REMOVE_ALL_DATA ) {

	// Remove caps.
	pngx( Pngx__Add_Capabilities::class )->remove_capabilities( \Cctor__Coupon__Main::POSTTYPE );

	// Delete options.
	$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'cctor\_%';" );
	$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'coupon_creator\_%';" );

	// Delete usermeta.
	$wpdb->query( "DELETE FROM $wpdb->usermeta WHERE meta_key LIKE 'cctor\_%';" );

	// Delete posts + data.
	$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_type IN ( \Cctor__Coupon__Main::POSTTYPE );" );
	$wpdb->query( "DELETE meta FROM {$wpdb->postmeta} meta LEFT JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id WHERE posts.ID IS NULL;" );

	// Delete term taxonomies.
	foreach ( array( 'cctor_coupon_category', 'coupon-location', 'coupon-vendor' ) as $_taxonomy ) {
		$wpdb->delete(
			$wpdb->term_taxonomy,
			array(
				'taxonomy' => $_taxonomy,
			)
		);
	}

	// Delete orphan relationships.
	$wpdb->query( "DELETE tr FROM {$wpdb->term_relationships} tr LEFT JOIN {$wpdb->posts} posts ON posts.ID = tr.object_id WHERE posts.ID IS NULL;" );

	// Delete orphan terms.
	$wpdb->query( "DELETE t FROM {$wpdb->terms} t LEFT JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id WHERE tt.term_id IS NULL;" );

	// Delete orphan term meta.
	if ( ! empty( $wpdb->termmeta ) ) {
		$wpdb->query( "DELETE tm FROM {$wpdb->termmeta} tm LEFT JOIN {$wpdb->term_taxonomy} tt ON tm.term_id = tt.term_id WHERE tt.term_id IS NULL;" );
	}

	// Clear any cached data that has been removed.
	wp_cache_flush();
}

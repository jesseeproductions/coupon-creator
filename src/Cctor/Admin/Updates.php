<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Plugin Version Update and Data Updater
 *
 *
 */
class Cctor__Coupon__Admin__Updates {

	/*
	* Update Version Number Check
	*
	*/
	public function admin_upgrade_version() {
		//Update Version Number
		if ( get_option( Cctor__Coupon__Main::VERSION_KEY ) != Cctor__Coupon__Main::VERSION_NUM ) {

			// Then update the version value
			update_option( 'coupon_update_version', date( 'l jS \of F Y h:i:s A' ) );

			$this->update_templates();

			$this->update_expiration_option();

			$this->update_image_fields();

			update_option( Cctor__Coupon__Main::VERSION_KEY, Cctor__Coupon__Main::VERSION_NUM );

			update_option( 'pngx_permalink_change', true );
		}
	}

	/*
	* Update to new template system on 2.5
	*
	*/
	public function update_templates() {
		update_option( 'coupon_update_templates', date( 'l jS \of F Y h:i:s A' ) );

		$args = array(
			'posts_per_page' => 1000,
			'post_type'      => 'cctor_coupon',
			'meta_query'     => array(
				array(
					'key'     => 'cctor_coupon_type',
					'compare' => 'NOT EXISTS'
				),
			)
		);

		$cctor_tempaltes = new WP_Query( $args );

		if ( $cctor_tempaltes ) {
			while ( $cctor_tempaltes->have_posts() ) : $cctor_tempaltes->the_post();

				//if image in coupon set as image template
				$image = get_post_meta( $cctor_tempaltes->post->ID, 'cctor_image', true );
				if ( $image ) {
					update_post_meta( $cctor_tempaltes->post->ID, 'cctor_coupon_type', 'image' );
				} else {
					update_post_meta( $cctor_tempaltes->post->ID, 'cctor_coupon_type', 'default' );
				}

			endwhile;
		}

		wp_reset_postdata();
	}

	/**
	 * Update Coupons with new Expiration Options in 2.3
	 */
	public function update_expiration_option() {
		//Run this script once
		if ( get_option( 'coupon_update_expiration_type' ) ) {
			return;
		}
		$args = array(
			'posts_per_page' => 2000,
			'post_type'      => 'cctor_coupon',
			'post_status'    => 'publish',
		);

		$cctor_exp_option = new WP_Query( $args );

		if ( $cctor_exp_option ) {

			while ( $cctor_exp_option->have_posts() ) : $cctor_exp_option->the_post();

				//If there is an Expiration Option Skip this Coupon
				$cctor_expiration_option = get_post_meta( $cctor_exp_option->post->ID, 'cctor_expiration_option', true );
				if ( $cctor_expiration_option ) {
					continue;
				}

				$cctor_ignore_expiration          = get_post_meta( $cctor_exp_option->post->ID, 'cctor_ignore_expiration', true );
				$cctor_expiration                 = get_post_meta( $cctor_exp_option->post->ID, 'cctor_expiration', true );
				$cctor_recurring_expiration_limit = get_post_meta( $cctor_exp_option->post->ID, 'cctor_recurring_expiration_limit', true );
				$cctor_recurring_expiration       = get_post_meta( $cctor_exp_option->post->ID, 'cctor_recurring_expiration', true );
				$cctor_x_days_expiration          = get_post_meta( $cctor_exp_option->post->ID, 'cctor_x_days_expiration', true );

				if ( 1 == $cctor_ignore_expiration ) {
					update_post_meta( $cctor_exp_option->post->ID, 'cctor_expiration_option', 1 );
				} elseif ( $cctor_expiration && ! $cctor_recurring_expiration_limit && ! $cctor_recurring_expiration ) {
					update_post_meta( $cctor_exp_option->post->ID, 'cctor_expiration_option', 2 );
				} elseif ( $cctor_expiration && $cctor_recurring_expiration_limit && $cctor_recurring_expiration ) {
					update_post_meta( $cctor_exp_option->post->ID, 'cctor_expiration_option', 3 );
				} elseif ( $cctor_x_days_expiration ) {
					update_post_meta( $cctor_exp_option->post->ID, 'cctor_expiration_option', 4 );
				}

			endwhile;
		}


		wp_reset_postdata();

		update_option( 'coupon_update_expiration_type', date( 'l jS \of F Y h:i:s A' ) );
	}

	/*
	* On Update Query Coupons and update cctor_outer_radius to cctor_img_outer_radius value and delete
	* This if for Image Coupons made prior to 2.1
	*
	*/
	public function update_image_fields() {
		update_option( 'coupon_update_image_border_meta', date( 'l jS \of F Y h:i:s A' ) );

		$args = array(
			'posts_per_page' => 1000,
			'post_type'      => 'cctor_coupon',
			'post_status'    => 'publish',
			'meta_key'       => 'cctor_img_outer_radius'
		);

		$cctor_ignore_exp = new WP_Query( $args );

		if ( $cctor_ignore_exp ) {
			while ( $cctor_ignore_exp->have_posts() ) : $cctor_ignore_exp->the_post();

				update_post_meta( $cctor_ignore_exp->post->ID, 'cctor_outer_radius', get_post_meta( $cctor_ignore_exp->post->ID, 'cctor_img_outer_radius', true ) );

				delete_post_meta( $cctor_ignore_exp->post->ID, 'cctor_img_outer_radius' );

			endwhile;
		}

		wp_reset_postdata();
	}
}
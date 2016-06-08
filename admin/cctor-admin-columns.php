<?php
//If Direct Access Kill the Script
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );


if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


/**
 * Class Coupon_Admin_Columns
 * Coupon Column Methods for the Coupon CPT
 *
 * @since 2.3
 *
 */
class Coupon_Admin_Columns extends WP_List_Table {


	function __construct() {

		// Remove Coupon Row Actions
		add_filter( 'post_row_actions', array( __CLASS__, 'cctor_remove_coupon_row_actions' ), 10, 2 );

		// Add Columns
		add_filter( 'manage_edit-cctor_coupon_columns', array( __CLASS__, 'cctor_list_columns' ) );

		//Custom Column Cases
		add_action( 'manage_posts_custom_column', array( __CLASS__, 'cctor_column_cases' ), 10, 2 );

	}

	/***************************************************************************/

	/*
	* Remove Coupon Row Actions if user does not have permision to manage
	* @version 1.90
	* @param array $actions, $post
	*/
	public static function cctor_remove_coupon_row_actions( $actions, $post ) {
		global $current_screen, $current_user;

		if ( is_object( $current_screen ) && $current_screen->post_type != 'cctor_coupon' ) {
			return $actions;
		}

		if ( ! current_user_can( 'edit_others_cctor_coupons', $post->ID ) && ( $post->post_author != $current_user->ID ) ) {
			unset( $actions['edit'] );
			unset( $actions['view'] );
			unset( $actions['trash'] );
			unset( $actions['inline hide-if-no-js'] );
		}

		return $actions;
	}

	/***************************************************************************/

	/*
	* Setup Custom Columns
	* @version 2.0
	* @param array $columns
	*/
	public static function cctor_list_columns( $columns ) {
		$cctor_columns = array();

		if ( isset( $columns['cb'] ) ) {
			$cctor_columns['cb'] = $columns['cb'];
		}

		if ( isset( $columns['title'] ) ) {
			$cctor_columns['title'] = __( 'Coupon Title', 'coupon-creator' );
		}

		if ( isset( $columns['author'] ) ) {
			$cctor_columns['author'] = $columns['author'];
		}

		$cctor_columns['cctor_showing'] = __( 'Coupon is ', 'coupon-creator' );

		$cctor_columns['cctor_shortcode'] = __( 'Shortcode', 'coupon-creator' );

		$cctor_columns['cctor_ignore_expiration'] = __( 'Ignore Expiration', 'coupon-creator' );

		$cctor_columns['cctor_expiration_date'] = __( 'Expiration Date', 'coupon-creator' );


		if ( isset( $columns['date'] ) ) {
			$cctor_columns['date'] = $columns['date'];
		}

		//Filter Columns
		if ( has_filter( 'cctor_filter_coupon_list_columns' ) ) {

			/**
			 * Filter the Admin Coupon List Columns Headers
			 *
			 * @param array $cctor_columns an array of column headers.
			 *
			 */
			$cctor_columns = apply_filters( 'cctor_filter_coupon_list_columns', $cctor_columns, $columns );
		}

		return $cctor_columns;
	}

	/**
	 * Add Custom Meta Data to Columns
	 *
	 * @since 2.0
	 *
	 * @param $column
	 * @param $post_id
	 */
	public static function cctor_column_cases( $column, $post_id ) {

		if ( class_exists( 'CCtor_Pro_Expiration_Class' ) ) {
			$coupon_expiration = new CCtor_Pro_Expiration_Class();
		} else {
			$coupon_expiration = new CCtor_Expiration_Class();
		}

		switch ( $column ) {
			case 'cctor_showing':

				echo $coupon_expiration->get_admin_list_coupon_showing();

				break;
			case 'cctor_shortcode':

				echo "<code>[coupon couponid='" . $post_id . "' name='" . get_the_title( $post_id ) . "']</code>";

				break;
			case 'cctor_expiration_date':

				echo $coupon_expiration->get_display_expiration();

				break;
			case 'cctor_ignore_expiration':

				if ( 1 == $coupon_expiration->get_expiration_option() ) {
					echo "<p style='padding-left:40px;'>" . __( 'Yes', 'coupon-creator' ) . "</p>";
				}
				break;
		}

		if ( has_filter( 'cctor_filter_column_cases' ) ) {

			/**
			 * Filter the Admin Coupon List Columns Information per Coupon
			 *
			 * @since 1.80
			 *
			 * @param string $column            a string of data to display in the admin columns.
			 * @param int    $post_id           an integer of the coupon post
			 * @param object $coupon_expiration the expiration object.
			 *
			 */
			apply_filters( 'cctor_filter_column_cases', $column, $post_id, $coupon_expiration );
		}
	}

	/***************************************************************************/
}

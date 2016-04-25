<?php


class CCtor_Expiration_Class {


	public static function meta_info() {
		add_action( 'cctor_meta_message', array(  __CLASS__, 'get_coupon_status' ), 15 );
		add_action( 'cctor_meta_message', array(  __CLASS__, 'get_coupon_status_msg' ), 20 );
	}

	public static function check_expiration( $coupon_id ) {

		//Ignore Expiration Value
		$ignore_expiration = get_post_meta( $coupon_id, 'cctor_ignore_expiration', true );
		/**
		 * Filter the ignore expiration per coupon
		 *
		 * @param bool $ignore_expiration a boolean value
		 * @param int  $coupon_id         an integer
		 *
		 */
		$ignore_expiration = apply_filters( 'cctor_filter_ignore_expiration', $ignore_expiration, $coupon_id );

		//Return If Not Passed Expiration Date
		$expiration = CCtor_Expiration_Class::is_coupon_before_expiration( $coupon_id );
		/**
		 * Filter if the coupon is expired or not
		 *
		 * @param bool $expiration a boolean value
		 * @param int  $coupon_id  an integer
		 *
		 */
		$expiration = apply_filters( 'cctor_filter_expiration', $expiration, $coupon_id );

		//Enable Filter to stop coupon from showing
		$show_coupon_check = false;

		/**
		 * Filter the the Coupon in the Meta if it should Display
		 *
		 *
		 * @param boolean $show_coupon_check true or false a coupon should show.
		 *
		 */
		$show_coupon_check = apply_filters( 'cctor_filter_meta_show_coupon_check', $show_coupon_check, $coupon_id );

		if ( ( $expiration || $ignore_expiration == 1 ) && ! $show_coupon_check ) {

			return true;

		} else {

			return false;

		}
	}
	/***************************************************************************/


	public static function get_coupon_status( $coupon_id ) {

		if ( CCtor_Expiration_Class::check_expiration( $coupon_id ) ) {

			echo '<div class="cctor-meta-bg cctor-message"><div>' . __( 'This Coupon is Showing', 'coupon-creator' ) . '</div></div>';


		} else {

			echo '<div class="cctor-meta-bg cctor-error"><p>' . __( 'This Coupon is not Showing', 'coupon-creator' ) . '</p></div>';

		}

	}
	/***************************************************************************/

	/**
	 * Coupon's Status Message
	 *
	 * @param $coupon_id
	 *
	 * @return array
	 */
	public static function get_coupon_status_msg( $coupon_id ) {

		$exp_option = get_post_meta( $coupon_id, 'cctor_expiration_option', true );

		$exp_msg = '';

		if ( 1 == $exp_option ) {

			$exp_msg = __( 'Ignore Coupon Expiration is On', 'coupon-creator' );

		} elseif ( 2 == $exp_option ) {

			$expiration = CCtor_Expiration_Class::get_coupon_expiration_date( $coupon_id );

			if ( $expiration['exp_unix'] >= $expiration['today'] ) {
				$exp_msg = '<div>' . __( 'This Coupon Expires On ', 'coupon-creator' ) .  $expiration['exp_date'] . '</div>';
			} else {
				$exp_msg = '<div>' . __( 'This Coupon Expired On ', 'coupon-creator' ) .  $expiration['exp_date'] . '</div>';
			}

		} elseif ( 3 == $exp_option ) {


		} elseif ( 4 == $exp_option ) {


		}

		$exp_msg = '<div class="cctor-meta-bg cctor-message">' . $exp_msg . '' .  '</div>';

		return $exp_msg;

	}

	/***************************************************************************/

	/**
	 * Get Coupon Expiration Date, Unix Time, and Today
	 *
	 * @param $coupon_id
	 *
	 * @return mixed
	 */
	public static function get_coupon_expiration_date( $coupon_id ) {

		$exp             = get_post_meta( $coupon_id, 'cctor_expiration', true );
		$expiration['exp_unix'] = strtotime( $exp );

		//Date Format
		if ( $exp ) { // Only Display Expiration if Date

			$daymonth_date_format = get_post_meta( $coupon_id, 'cctor_date_format', true ); //Date Format

			if ( $daymonth_date_format == 1 ) { //Change to Day - Month Style

				$expiration['exp_date'] = date( "d/m/Y", $expiration['exp_unix'] );

			}
		}

		//Blog Time According to WordPress
		$cc_blogtime = current_time( 'mysql' );
		list( $today_year, $today_month, $today_day ) = preg_split( '([^0-9])', $cc_blogtime );
		$expiration['today'] = strtotime( $today_month . "/" . $today_day . "/" . $today_year );

		return $expiration;
	}
	/***************************************************************************/

	/**
	 * Check if a Coupon is Expired
	 *
	 * @param $coupon_id
	 *
	 * @return bool
	 */
	public static function is_coupon_before_expiration( $coupon_id ) {

		$expiration = CCtor_Expiration_Class::get_coupon_expiration_date( $coupon_id );

		if ( $expiration['exp_unix'] >= $expiration['today'] ) {
			return true;
		} else {
			return false;
		}

	}

	/***************************************************************************/
}
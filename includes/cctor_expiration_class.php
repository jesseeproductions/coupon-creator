<?php


class CCtor_Expiration_Class {

	/**
	 * @var static
	 */
	protected static $instance;

	/**
	 * @var int
	 */
	protected $coupon_id;

	/**
	 * @var int
	 */
	protected $expiration_option;

	/**
	 * @var string
	 */
	protected $exp_msg;

	/**
	 * @var string
	 */
	protected $date_format;

	/**
	 * @var string
	 */
	protected $expiration;

	/**
	 * @var string
	 */
	protected $show_coupon;

	/**
	 * @var string
	 */
	protected $coupon_hidden;

	/**
	 * @var string
	 */
	//protected $ignore_expiration;

	public function __construct( $coupon_id = null ) {

		$this->coupon_id = $coupon_id;
		if ( ! $this->coupon_id ) {
			$this->coupon_id  = get_the_id();
		}

		$this->expiration_option = get_post_meta( $this->coupon_id, 'cctor_expiration_option', true );

		if ( 1 != $this->expiration_option ) {
			$this->date_format = get_post_meta( $this->coupon_id, 'cctor_date_format', true );
			$this->expiration  = get_post_meta( $this->coupon_id, 'cctor_expiration', true );
		}

		$this->show_coupon = true;
		$this->coupon_hidden = false;
		//$this->ignore_expiration = get_post_meta( $coupon_id, 'cctor_ignore_expiration', true );
	}

	public function check_expiration( ) {


		if ( 1 != $this->expiration_option ) {

			$this->show_coupon = self::is_coupon_current();

		}

		/**
		 * Filter whether a coupon is expired
		 *
		 * @param bool $ignore_expiration a boolean value
		 * @param int  $coupon_id         an integer
		 *
		 */
		$this->show_coupon = apply_filters( 'cctor_filter_ignore_expiration', $this->show_coupon, $this->coupon_id );

		/**
		 * Filter Additional Restriction on whether the coupon should show
		 *
		 *
		 * @param boolean $show_coupon_check true or false a coupon should show.
		 *
		 */
		$this->coupon_hidden = apply_filters( 'cctor_filter_meta_show_coupon_check', $this->coupon_hidden, $this->coupon_id );

		if ( ( $this->show_coupon ) && ! $this->coupon_hidden ) {

			return true;

		} else {

			return false;

		}
	}
	/***************************************************************************/


	public function get_coupon_status() {

		if ( self::check_expiration() ) {

			echo '<div class="cctor-meta-bg cctor-message"><div>' . __( 'This Coupon is Showing.', 'coupon-creator' ) . '</div></div>';


		} else {

			echo '<div class="cctor-meta-bg cctor-error"><p>' . __( 'This Coupon is not Showing, please select an expiration option.', 'coupon-creator' ) . '</p></div>';

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
	public function get_coupon_status_msg() {

		if ( 1 == $this->expiration_option ) {

			$this->exp_msg = __( 'Ignore Coupon Expiration is On', 'coupon-creator' );

		} elseif ( 2 == $this->expiration_option ) {

			$expiration = self::get_coupon_expiration_dates();
			if ( ! isset( $expiration['exp_date'] ) ) {
				$this->exp_msg = '<div>' . __( 'There is no Coupon Expiration Date', 'coupon-creator' ) . '</div>';
			} elseif ( $expiration['exp_unix'] >= $expiration['today'] ) {
				$this->exp_msg = '<div>' . __( 'This Coupon Expires On ', 'coupon-creator' ) .  $expiration['exp_date'] . '</div>';
			} else {
				$this->exp_msg = '<div>' . __( 'This Coupon Expired On ', 'coupon-creator' ) .  $expiration['exp_date'] . '</div>';
			}

		} elseif ( 3 == $this->expiration_option ) {


		} elseif ( 4 == $this->expiration_option ) {


		}

		$this->exp_msg = '<div class="cctor-meta-bg cctor-message">' . $this->exp_msg . '' .  '</div>';

		echo $this->exp_msg;

	}

	/***************************************************************************/

	/**
	 * Get Coupon Expiration Date, Unix Time, and Today
	 *
	 * @param $coupon_id
	 *
	 * @return mixed
	 */
	public function get_coupon_expiration_dates() {

		$expiration['exp_unix'] = strtotime( $this->expiration );
		$expiration['exp_date'] = $this->expiration;

		//Date Format
		if ( $this->expiration ) { // Only Display Expiration if Date

			if ( $this->date_format == 1 ) { //Change to Day - Month Style

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
	public function is_coupon_current() {

		$expiration = self::get_coupon_expiration_dates();
		log_me( $expiration );
		if ( $expiration['exp_unix'] >= $expiration['today'] ) {
			log_me( 'unix greater' );
			return true;
		}

			log_me( 'end' );
			return false;

	}

	/***************************************************************************/
}
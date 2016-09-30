<?php
//If Direct Access Kill the Script
if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ ) {
	die( 'Access denied.' );
}


/**
 * Class Cctor__Coupon__Expiration
 *
 * @since 2.3
 *
 * Expiration Class to determine if a coupon should display and process all expiration information
 */
class Cctor__Coupon__Expiration {

	/**
	 * @var int
	 */
	protected $coupon_id;

	/**
	 * @var int
	 */
	public $expiration_option;

	/**
	 * @var date
	 */
	protected $start;

	/**
	 * @var date
	 */
	protected $expiration;

	/**
	 * @var string
	 */
	protected $date_format;

	/**
	 * @var string
	 */
	protected $display_start_date;

	/**
	 * @var string
	 */
	protected $display_date;

	/**
	 * @var string
	 */
	protected $date_start_unix;

	/**
	 * @var string
	 */
	protected $date_unix;

	/**
	 * @var string
	 */
	protected $today_unix;

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
	protected $exp_msg;

	/**
	 * @var string
	 */
	protected $exp_class;

	/*
	 * Contruct Coupon Expiration Class
	 */
	public function __construct( $coupon_id = null ) {

		$this->coupon_id = $coupon_id;
		if ( ! $this->coupon_id ) {
			$this->coupon_id = get_the_id();

			if ( is_object( $this->coupon_id ) ) {
				echo 'object!';
			}
		}
		$this->expiration_option = get_post_meta( $this->coupon_id, 'cctor_expiration_option', true );

		$this->show_coupon = true;

		if ( 1 != $this->expiration_option ) {
			$this->date_format = get_post_meta( $this->coupon_id, 'cctor_date_format', true );
			$this->start_date  = get_post_meta( $this->coupon_id, 'cctor_start_date', true );
			$this->expiration  = get_post_meta( $this->coupon_id, 'cctor_expiration', true );
			self::set_coupon_expiration_dates();
			$this->show_coupon = self::is_coupon_current();
		}

		if ( is_admin() ) {
			self::set_coupon_status_msg();
		}
	}

	/**
	 *  Check whether a coupon should show or not
	 *
	 * @return bool
	 */
	public function check_expiration() {

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

	/**
	 *  On Individual Coupon Editor Display Showing or Not Message
	 *
	 */
	public function get_coupon_status() {
		if ( self::check_expiration() ) {
			echo '<div class="pngx-meta-bg pngx-message"><div>' . __( 'This Coupon is Showing.', 'coupon-creator' ) . '</div></div>';
		} else {
			echo '<div class="pngx-meta-bg pngx-error"><div>' . __( 'This Coupon is not Showing.', 'coupon-creator' ) . '</div></div>';
		}
	}

	/***************************************************************************/

	/**
	 * Get the coupon status message
	 */
	public function get_coupon_status_msg() {
		if ( $this->exp_msg ) {
			$this->exp_msg = '<div class="pngx-meta-bg ' . esc_attr( $this->exp_class ) . '">' . $this->exp_msg . '' . '</div>';
		}

		return $this->exp_msg;
	}

	/**
	 * Display the coupon status message
	 */
	public function the_coupon_status_msg() {
		echo self::get_coupon_status_msg();
	}

	/***************************************************************************/

	/**
	 * Set Coupon Meta Status Message
	 */
	public function set_coupon_status_msg() {

		$this->exp_class = 'pngx-message';

		if ( 1 == $this->expiration_option ) {

			$this->exp_msg = __( 'Ignore Coupon Expiration is On', 'coupon-creator' );

		} elseif ( 2 == $this->expiration_option ) {

			if ( ! isset( $this->display_date ) ) {
				$this->exp_msg = '<div>' . __( 'There is no Coupon Expiration Date', 'coupon-creator' ) . '</div>';
			} elseif ( $this->date_unix >= $this->today_unix ) {
				$this->exp_msg = '<div>' . __( 'This Coupon Expires On ', 'coupon-creator' ) . $this->display_date . '</div>';
			} else {
				$this->exp_msg   = '<div>' . __( 'This Coupon Expired On ', 'coupon-creator' ) . $this->display_date . '</div>';
				$this->exp_class = 'pngx-error';
			}

		}
	}

	/***************************************************************************/

	/**
	 * Get the formatted expiration date
	 */
	public function get_display_start() {
		if ( $this->display_start_date ) {
			return $this->display_start_date;
		}

		return false;
	}

	/**
	 * Get the formatted expiration date
	 */
	public function get_display_expiration() {
		if ( $this->display_date ) {
			return $this->display_date;
		}

		return false;
	}

	/**
	 * Display the formatted expiration date
	 */
	public function the_display_expiration() {
		echo self::get_display_expiration();
	}

	/**
	 *  Get the Expiration Date Format
	 *
	 * @return bool|mixed|string
	 */
	public function get_date_format() {
		if ( $this->date_format ) {
			return $this->date_format;
		}

		return false;
	}

	/**
	 * Set Coupon Expiration Date, Unix Time, and Today
	 *
	 */
	public function set_coupon_expiration_dates() {

		if ( $this->start_date ) {

			$this->date_start_unix = strtotime( $this->start_date );

			//Display Date with Formatting
			$this->display_start_date = $this->start_date;
			if ( $this->date_format == 1 ) {
				$this->display_start_date = date( "d/m/Y", $this->date_start_unix );
			}

		}

		if ( $this->expiration ) {

			$this->date_unix = strtotime( $this->expiration );

			//Display Date with Formatting
			$this->display_date = $this->expiration;
			if ( $this->date_format == 1 ) {
				$this->display_date = date( "d/m/Y", $this->date_unix );
			}
		}

		$this->today_unix = strtotime( Pngx__Date::display_date( 0 ) );

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

		if ( $this->date_unix >= $this->today_unix ) {

			return true;
		}

		return false;

	}

	/***************************************************************************/

	/**
	 * Show Message in Admin List if the Coupon is Showing Or Not
	 *
	 * @return string
	 */
	public function get_admin_list_coupon_showing() {

		if ( self::check_expiration() ) {
			return "<p style='color: #048c7f; padding-left:5px;'>" . __( 'Showing', 'coupon-creator' ) . "</p>";
		} else {
			return "<p style='color: #dd3d36; padding-left:5px;'>" . __( 'Not Showing', 'coupon-creator' ) . "</p>";
		}

	}

	/***************************************************************************/

	/**
	 * Get Coupons Current Option
	 *
	 * @return int|mixed
	 */
	public function get_expiration_option() {

		return $this->expiration_option;

	}

	/***************************************************************************/
}
<?php
//If Direct Access Kill the Script
if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ ) {
	die( 'Access denied.' );
}

/**
 * Display Expiration Date
 *
 * @param null $coupon_expiration
 *
 * @return bool/string
 */
function cctor_show_expiration( $coupon_id, $coupon_expiration = null ) {

	if ( ! is_object( $coupon_expiration ) ) {
		return false;
	}

	$expiration_date = $coupon_expiration->get_display_expiration();

	if ( ! empty( $expiration_date ) ) {
		?>
		<div class="cctor_expiration core"><?php echo __( 'Expires on:', 'coupon-creator' ); ?>
			&nbsp;<?php echo esc_html( $expiration_date ); ?></div>
		<?php
	}
}

/**
 * Add expiration date to html comment for expired coupon
 *
 * @param $coupon_id
 * @param $coupon_expiration
 *
 * @return bool/string
 */
function cctor_show_no_coupon_comment( $coupon_id, $coupon_expiration ) {

	if ( ! is_object( $coupon_expiration ) ) {
		return false;
	}

	$expiration_date = $coupon_expiration->get_display_expiration();

	if ( ! empty( $expiration_date ) ) {
		?><!-- Coupon "<?php echo get_the_title( $coupon_id ); ?>" Has Expired on <?php echo esc_html( $expiration_date ); ?>--><?php
	}

}	
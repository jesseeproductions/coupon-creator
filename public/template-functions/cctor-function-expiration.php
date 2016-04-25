<?php
//If Direct Access Kill the Script
if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ ) {
	die( 'Access denied.' );
}

/**
 * Coupon Creator Print Template Expiration
 *
 * @since 1.90
 *
 * @param $coupon_id
 */
function cctor_show_expiration( $coupon_id ) {
	//Coupon Expiration Date
	$expirationco = get_post_meta( $coupon_id, 'cctor_expiration', true );

	$cc_expiration_date = strtotime( $expirationco );

	if ( $expirationco ) { // Only Display Expiration if Date
		$daymonth_date_format = get_post_meta( $coupon_id, 'cctor_date_format', true ); //Date Format

		if ( $daymonth_date_format == 1 ) { //Change to Day - Month Style
			$expirationco = date( "d/m/Y", $cc_expiration_date );
		} ?>

		<div class="cctor_expiration core"><?php echo __( 'Expires on:', 'coupon-creator' ); ?>
			&nbsp;<?php echo esc_html( $expirationco ); ?></div>

	<?php }
}

/**
 * Coupon Creator Print Template No Show Coupon
 *
 * @since 1.90
 *
 * @param $coupon_id
 */
function cctor_show_no_coupon_comment( $coupon_id ) {
	//Coupon Expiration Date
	$expirationco = get_post_meta( $coupon_id, 'cctor_expiration', true );

	?><!-- Coupon "<?php echo get_the_title( $coupon_id ); ?>" Has Expired on <?php echo esc_html( $expirationco ); ?>--><?php

}	
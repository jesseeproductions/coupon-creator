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

	$coupon_expiration = new CCtor_Expiration_Class( $coupon_id );

	$expiration_date = $coupon_expiration->get_coupon_expiration_dates();

	if ( isset( $expiration_date['exp_date'] ) ) {
		?>
		<div class="cctor_expiration core"><?php echo __( 'Expires on:', 'coupon-creator' ); ?>
			&nbsp;<?php echo esc_html( $expiration_date['exp_date'] ); ?></div>
		<?php
	}
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
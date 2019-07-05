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

		?><!--<?php echo sprintf( '%1s %2s %3s %4s', __( 'Coupon', 'coupon-creator' ), get_the_title( $coupon_id ), __( 'expired on', 'coupon-creator' ), esc_html( $expiration_date ) ) ?>--><?php

	}

}

/**
 * Add expiration date and coupon name to notice in Gutenberg
 *
 * @since 3.0
 *
 * @param $coupon_id
 * @param $coupon_expiration
 *
 * @return bool/string
 */
function cctor_show_no_coupon_notice_admin( $coupon_id, $coupon_expiration, $coupon_align = null ) {

	if ( ! is_object( $coupon_expiration ) ) {
		return false;
	}

	if ( ! is_admin() ) {
		return false;
	}

	$expiration_date = $coupon_expiration->get_display_expiration();

	if ( ! empty( $expiration_date ) ) {
		?>
		<div id="coupon_creator_<?php echo absint( $coupon_id ); ?>" class="coupon-creator-<?php echo absint( $coupon_id ); ?> type-cctor_coupon cctor_coupon_container coupon-border <?php echo esc_html( $coupon_align ); ?>">
			<div class="cctor_coupon cctor-coupon">
				<div class="cctor_coupon_content cctor-coupon-content" style="border-color:#dd3333">
					<h3 class="cctor-deal" style="background-color:#dd3333; color:#000000;">
						<?php echo __( 'Coupon Expired', 'coupon-creator' ); ?>
					</h3>
					<div class="cctor-terms">
						<p style="font-size: 14px;">
							<?php echo sprintf( '%1s %2s %3s %4s', get_the_title( $coupon_id ), __( ' expired on ', 'coupon-creator' ), esc_html( $expiration_date ), __( ' and is not showing to your visitors. ', 'coupon-creator' ) ); ?>
						</p>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

}
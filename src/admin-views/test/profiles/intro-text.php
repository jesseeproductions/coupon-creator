<?php
/**
 * View: Integration Profiles Intro Text.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/pngx/admin-views/test/profiles/intro-text.php
 *
 * See more documentation about our views templating system.
 *
 * @since 0.1.0
 *
 * @version TBD
 *
 * @link    https://pngx.ink/RYIOh
 *
 * @var array $allowed_html Which HTML elements are used for wp_kses.
 */

?>
<p class="cctor-coupon-test-integration__description">
	<?php
	echo sprintf(
		'%1$s',
			esc_html_x(
				'Please add a Test Profile to enable them.',
				'Options help text for Test.',
				'cctor-test'
			),
	);
	?>
</p>
<p class="cctor-coupon-test-integration__description">
	<?php
	$url = 'https://google.com';
	echo sprintf(
		'<a href="%1$s" target="_blank">%2$s</a>',
			esc_url( $url ),
			esc_html_x(
			'Read more about test profiles.',
			'Settings link text for Test.',
			'cctor-test'
			)
	);
	?>
</p>
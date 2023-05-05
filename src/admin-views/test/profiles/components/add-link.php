<?php
/**
 * View: Integration add new profile fields.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/pngx/admin-views/test/profiles/components/add-link
 *
 * See more documentation about our views templating system.
 *
 * @since 0.1.0
 *
 * @version 0.1.0
 *
 * @link    https://pngx.ink/RYIOh
 *
 * @var Api $api An instance of the Test API handler.
 * @var Url $url An instance of the URL handler.
 */

$add_link      = $url->to_add_profile_link();
$connect_label = _x( 'Add Profile', 'Label to add new profile.', 'cctor-test' );

$classes = [
	'button'                                        => true,
	'pngx-engine-options__add-profile-button'    => true,
	'pngx-engine-options-test__add-profile-button' => true,
];
?>
<a
	href="<?php echo esc_url( $add_link ); ?>"
	<?php pngx_classes( $classes ); ?>
>
	<span class="dashicons dashicons-plus"></span>
	<?php echo esc_html( $connect_label ); ?>
</a>

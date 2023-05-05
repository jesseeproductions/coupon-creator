<?php
/**
 * View: Integration - Update Button.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/pngx/admin-views/test/profiles/components/update-button.php
 *
 * See more documentation about our views templating system.
 *
 * @since   0.1.0
 *
 * @version 0.1.0
 *
 * @link    https://pngx.ink/RYIOh
 *
 * @var string              $unique_id    The unique key for the API key.
 * @var array<string|mixed> $profile_data The Profile data.
 * @var Url                 $url          An instance of the URL handler.
 */

$update_link  = $url->to_update_profile( $unique_id );
$update_label = _x( 'Update', 'Update an Test Profile.', 'cctor-test' );
?>
<button
	class="pngx-engine-options-details-action__update pngx-engine-options-test-details-action__update"
	type="button"
	data-ajax-update-url="<?php echo $update_link; ?>"
>
	<span>
		<?php echo esc_html( $update_label ); ?>
	</span>
</button>
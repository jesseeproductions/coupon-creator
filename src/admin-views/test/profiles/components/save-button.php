<?php
/**
 * View: Save Profile Button.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/pngx/admin-views/test/profiles/components/save-button.php
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

$save_link  = $url->to_save_access_profile( $unique_id );
$save_label = _x( 'Save', 'Save an profile for an API.', 'cctor-test' );
?>
<button
	class="pngx-engine-options-details-action__save pngx-engine-options-test-details-action__save"
	type="button"
	data-ajax-save-url="<?php echo $save_link; ?>"
	data-profile-error="<?php echo _x( 'Description or primary field missing. Please add a description or a API Key before saving an profile.', 'An error message that the description or primary is missing when saving.', 'cctor-test' ); ?>"
>
	<span>
		<?php echo esc_html( $save_label ); ?>
	</span>
</button>
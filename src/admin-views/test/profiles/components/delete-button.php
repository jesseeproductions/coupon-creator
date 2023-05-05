<?php
/**
 * View: Integration - Delete Button.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/pngx/admin-views/test/profiles/components/delete-button.php
 *
 * See more documentation about our views templating system.
 *
 * @since   0.1.0
 *
 * @version 0.1.0
 *
 * @link    https://pngx.ink/RYIOh
 *
 * @var Api                 $api          An instance of the Test API handler.
 * @var string              $unique_id    The unique key for the API key.
 * @var array<string|mixed> $profile_data The Profile data..
 * @var URL                 $url          An instance of the URL handler.
 */

$delete_link  = $url->to_delete_profile_link( $unique_id );
$delete_label = _x( 'Delete', 'Removes a profile from the integration profile list.', 'cctor-test' )
?>
<button
	class="dashicons dashicons-trash pngx-engine-options-details-action__delete pngx-test-details-action__delete"
	type="button"
	data-ajax-delete-url="<?php echo $delete_link; ?>"
	data-confirmation="<?php echo $api->get_confirmation_to_delete_profile(); ?>"
>
	<span class="screen-reader-text">
		<?php echo esc_html( $delete_label ); ?>
	</span>
</button>

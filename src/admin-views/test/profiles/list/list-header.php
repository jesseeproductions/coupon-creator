<?php
/**
 * View: Integration - Profiles List Header.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/pngx/admin-views/test/profiles/list/list-header.php
 *
 * See more documentation about our views templating system.
 *
 * @since 0.1.0
 *
 * @version 0.1.0
 *
 * @link    https://pngx.ink/RYIOh
 */
?>
<div class="pngx-engine-options-details__container pngx-engine-options-test-api-key-details__container pngx-engine-options-test-api-key-details__container-header pngx-profiles-grid pngx-profiles-grid-header">
	<div class="pngx-profiles-grid-item pngx-engine-options-details__row">
		<?php echo esc_html_x( 'Name', 'Name header label for the settings listing of Test Profile.', 'cctor-test' ) ?>
	</div>
	<div class="pngx-profiles-grid-item pngx-engine-options-test-details-profiles__primary-wrap">
		<?php echo esc_html_x( 'Model', 'Model header label for the settings listing of Test Profile.', 'cctor-test' ) ?>
	</div>
	<div class="pngx-profiles-grid-item pngx-engine-options-test-details-profiles__last-access-wrap">
		<?php echo esc_html_x( 'Last Access', 'Last Access header label for the settings listing of Test Profile.', 'cctor-test' ) ?>
	</div>
	<div class="pngx-profiles-grid-item pngx-engine-options-details-action__delete-wrap pngx-test-details-action__delete-wrap">
		<?php echo esc_html_x( 'Actions', 'Actions header label for the settings listing of Test Profile.', 'cctor-test' ) ?>
	</div>
</div>
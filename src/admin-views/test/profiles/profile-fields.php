<?php
/**
 * View: Integration Profile Fields.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/pngx/admin-views/test/profiles/profile-fields.php
 *
 * See more documentation about our views templating system.
 *
 * @since 0.1.0
 *
 * @version 0.1.0
 *
 * @link    https://pngx.ink/RYIOh
 *
 * @var Api                 $api     An instance of the Test API handler.
 * @var Url                 $url     An instance of the URL handler.
 * @var string              $message A message to display above the API Key list on loading.
 */

$profiles = $api->get_list_of_profiles( true );
?>
<fieldset id="cctor-coupon-test-field-test_profiles" class="cctor-coupon-test-options">
	<legend class="pngx-field-label"><?php esc_html_e( 'Test Profiles', 'cctor-test' ); ?></legend>
	<div class="pngx-engine-options-message__wrap cctor-coupon-test-profiles-messages">
		<?php
		$this->template( 'components/message', [
			'message' => $message,
			'type'    => 'standard',
		] );
		?>
	</div>
	<div class="pngx-engine-options-items__wrap cctor-coupon-test-api-keys-wrap">
		<?php
		$this->template( 'test/profiles/list/list', [
			'api'      => $api,
			'url'      => $url,
			'profiles' => $profiles,
		] );
		?>
	</div>
	<div class="cctor-coupon-test-integration-profile-add-wrap cctor-coupon-test-add-wrap">
		<?php
		$this->template( 'test/profiles/components/add-link', [
			'api' => $api,
			'url' => $url,
		] );
		?>
	</div>
</fieldset>

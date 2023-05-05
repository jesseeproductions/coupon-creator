<?php
/**
 * View: Test Integration Profile Panel.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/cctor/test/admin-views/test/profiles/components/panel.php
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
 * @var string              $api_id       The API ID.
 * @var string              $api_name     The API Name.
 * @var string              $unique_id    The unique key for the API key.
 * @var array<string|mixed> $profile_data The Profile data.
 */

?>
<div class="cctor-coupon-test-accordion__content">
	<?php
	$fields = $api->get_panel_fields( $profile_data );
	foreach( $fields as $field ) {
		$field_name       = $field['id'];
		$template_args = [
			'classes_wrap'  => [ "pngx-engine-options-{$api_id}-details-profiles__{$field_name}-wrap" ],
			'classes_label' => [ 'screen-reader-text', "pngx-engine-options-{$api_id}-details-profiles__{$field_name}-label" ],
			'classes_input' => [
				'pngx-engine-options-profile-defaults__input',
				'pngx-engine-options-details__input',
				" 'pngx-engine-options-details__{$field_name}-input'",
				"pngx-engine-options-{$api_id}-details-profiles__{$field_name}-input"
			],
			'label'         => $field['label'],
			'id'            => "cctor_coupon_test{$api_id}_{$field_name}_" . $unique_id,
			'name'          => "cctor_coupon_test{$api_id}[]['defaults']['{$field_name}']",
		];
		$template_args = array_merge( $template_args, $field['template_args'] );

		$this->template( 'components/field', [
				'classes_wrap'  => [ "pngx-engine-options-{$api_id}-details-profiles__{$field_name}-wrap" ],
				'label'         => $field['label'],
				'tooltip'       => $field['tooltip'],
				'template_name' => $field['template'],
				'template_args' => $template_args,
			]
		);
	}
	?>
</div>
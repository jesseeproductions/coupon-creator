<?php
/**
 * View: Integration Saved Profile.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/cctor/test/admin-views/test/profiles/list/profile-saved.php
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
 * @var array<string|mixed> $profile_data The Profile data.
 * @var URL                 $url          An instance of the URL handler.
 */
$api_id       = 'test';
$api_name     = 'Test';
$wrap_classes = [
	'pngx-profiles-grid',
	'pngx-engine-grid-row',
	'pngx-engine-options-details__container',
	"pngx-engine-options-{$api_id}-api-key-details__container",
];
?>
<div
	<?php pngx_classes( $wrap_classes ); ?>
	data-unique-id="<?php echo esc_attr( $unique_id ); ?>"
>
	<?php
	$field_name = 'name';
	$this->template( 'components/read-only', [
		'classes_wrap'  => [ 'pngx-profiles-grid-item', "pngx-engine-options-{$api_id}-details-profiles__{$field_name}-wrap" ],
		'label'         => _x( 'Description', "Label for the {$field_name} of the Profile for {$api_name}.", 'cctor-test' ),
		'screen_reader' => _x( "The {$field_name} for the {$api_name} Profile.", "The screen reader text of the label for the {$api_name} Profile {$field_name}.", 'cctor-test' ),
		'id'            => "cctor_coupon_test{$api_id}_{$field_name}_" . $unique_id,
		'name'          => "cctor_coupon_test{$api_id}[]['{$field_name}']",
		'value'         => $profile_data[$field_name],
	] );
	?>

	<?php
	$field_name = 'primary';
	$models           = [
		[
			"text"     => "Posts",
			"id"       => "post",
			"selected" => false
		],
		[
			"text"     => "Pages",
			"id"       => "page",
			"selected" => false
		],
		[
			"text"     => "Products",
			"id"       => "product",
			"selected" => false
		],
		[
			"text"     => "Coupons",
			"id"       => "cctor_coupon",
			"selected" => false
		]
	];
	$this->template( 'components/read-only', [
		'classes_wrap'  => [ 'pngx-profiles-grid-item', "pngx-engine-options-{$api_id}-details-profiles__{$field_name}-wrap" ],
		'label'         => _x( 'Description', "Label for the {$field_name} of the Profile for {$api_name}.", 'cctor-test' ),
		'screen_reader' => _x( "The {$field_name} for the {$api_name} Profile.", "The screen reader text of the label for the {$api_name} Profile {$field_name}.", 'cctor-test' ),
		'id'            => "cctor_coupon_test{$api_id}_{$field_name}_" . $unique_id,
		'name'          => "cctor_coupon_test{$api_id}[]['{$field_name}']",
		'value'         => isset( $models[ $profile_data[ $field_name ] ]['name'] ) ? $models[ $profile_data[ $field_name ] ]['name'] : _x( 'Model not found.', "Model not found value for test profile.", 'cctor-test' ),
	] );
	?>

	<?php
	$field_name = 'last-access';
	$this->template( 'components/read-only', [
		'classes_wrap'  => [ 'pngx-profiles-grid-item', "pngx-engine-options-{$api_id}-details-profiles__{$field_name}-wrap" ],
		'label'         => _x( 'Description', "Label for the {$field_name} of the Profile for {$api_name}.", 'cctor-test' ),
		'screen_reader' => _x( "The {$field_name} for the {$api_name} Profile.", "The screen reader text of the label for the {$api_name} Profile {$field_name}.", 'cctor-test' ),
		'id'            => "cctor_coupon_test{$api_id}_{$field_name}_" . $unique_id,
		'name'          => "cctor_coupon_test{$api_id}[]['{$field_name}']",
		'value'         => $profile_data[$field_name],
	] );
	?>

	<div
		<?php
		$wrap_classes = [
			'pngx-profiles-grid-item',
			"pngx-engine-options-profile-action__buttons-wrap",
			"pngx-engine-options-profile-{$api_id}-action__buttons-wrap",
			'pngx-engine-options-details__container',
		];

		pngx_classes( $wrap_classes );

		?>
	>
		<?php
		$this->template( $api_id . '/profiles/components/update-button', [
			'unique_id'    => $unique_id,
			'url'     => $url,
		] );

		$this->template( $api_id . '/profiles/components/delete-button', [
			'unique_id'    => $unique_id,
			'profile_data' => $profile_data,
			'url'          => $url,
		] );
		?>
	</div>

	<div class="pngx-engine-grid-full-width">
		<div class="pngx-engine-options-profile-panel__wrap">
			<?php
			$args = [
				'label'        => _x( 'Defaults', 'The title of the default settings accordion for an API profile.', 'cctor-test' ),
				'id'           => 'cctor-coupon-test-options__' . uniqid(),
				'classes_wrap' => [ 'cctor-coupon-test-options__accordion-wrapper' ],
				'panel'        => $this->template( '/test/profiles/components/panel', [ 'api' => $api, 'api_id' => $api_id, 'api_name' => $api_name,  'unique_id' => $unique_id, 'profile_data' => $profile_data ], false ),
				'expanded'     => false,
			];
			$this->template( 'components/accordion', $args );
			?>
		</div>
	</div>
</div>
<?php
/**
 * View: Integration New Profile.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/cctor/test/admin-views/test/profiles/list/profile-new.php
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
 * @var string              $unique_id    The unique_id for the Profile.
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
	$field_name       = 'name';
	$field_name_label = 'Name';
	$this->template( 'components/text', [
		'classes_wrap'  => [ 'pngx-profiles-grid-item', "pngx-engine-options-{$api_id}-details-profiles__{$field_name}-wrap" ],
		'classes_label' => [ 'screen-reader-text', "pngx-engine-options-{$api_id}-details-profiles__{$field_name}-label" ],
		'classes_input' => [
			'pngx-engine-options-details__input',
			" 'pngx-engine-options-details__{$field_name}-input'",
			"pngx-engine-options-{$api_id}-details-profiles__{$field_name}-input"
		],
		'label'         => _x( $field_name_label, "Label for the {$field_name} of the Profile for {$api_name}.", 'cctor-test' ),
		'id'            => "cctor_coupon_test{$api_id}_{$field_name}_" . $unique_id,
		'name'          => "cctor_coupon_test{$api_id}[]['{$field_name}']",
		'placeholder'   => _x( 'Enter a description...', "The placeholder for the {$api_name} Profile name.", 'cctor-test' ),
		'screen_reader' => _x( 'Enter a description.', "The screen reader text of the label for the {$api_name} Profile {$field_name}.", 'cctor-test' ),
		'value'         => '',
		'attrs'         => [],
	] );
	?>

	<?php
	$field_name       = 'primary';
	$field_name_label = 'Post Type';
	$selected_model   = 'post';
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
	$this->template( 'components/dropdown', [
		'classes_wrap'    => [ 'pngx-profiles-grid-item', "pngx-engine-options-{$api_id}-details-profiles__{$field_name}-wrap" ],
		'classes_label'   => [ 'screen-reader-text', "pngx-engine-options-{$api_id}-details-profiles__{$field_name}-label" ],
		'classes_select'  => [
			'pngx-engine-options-details__input',
			" 'pngx-engine-options-details__{$field_name}-input'",
			"pngx-engine-options-{$api_id}-details-profiles__{$field_name}-input"
		],
		'label'           => _x( $field_name_label, "Label for the {$field_name} of the Profile for {$api_name}.", 'cctor-test' ),
		'id'              => "cctor_coupon_test{$api_id}_{$field_name}_" . $unique_id,
		'name'            => "cctor_coupon_test{$api_id}[]['{$field_name}']",
		'placeholder'     => _x( 'Select the test model...', "The placeholder for the {$api_name} Profile name.", 'cctor-test' ),
		'screen_reader'   => _x( 'Select the test model.', "The screen reader text of the label for the {$api_name} Profile {$field_name}.", 'cctor-test' ),
		'selected'        => $selected_model,
		'selected_option' => [],
		'options'         => $models,
		'attrs'           => [
			'data-placeholder'        => _x( 'Select default test model.', 'The placeholder for a dropdown.', 'cctor-test' ),
			'data-prevent-clear'      => true,
			'data-force-search'       => true,
			'data-dropdown-css-width' => '0',
			'data-selected'           => $selected_model,
			'data-options'            => json_encode( $models ),
			'style'                   => 'width: 100%;',
		],
	] );
	?>

	<div class="pngx-profiles-grid-item"></div>

	<div
		<?php
		$wrap_classes = [
			'pngx-profiles-grid-item',
			"pngx-engine-options-{$api_id}-details__actions",
			'pngx-engine-options-details__container',
			"pngx-engine-options-{$api_id}-details__api-key-save",
		];

		pngx_classes( $wrap_classes );

		?>
	>
		<?php
		$this->template( $api_id . '/profiles/components/save-button', [
			'unique_id' => $unique_id,
			'url'       => $url,
		] );
		?>
	</div>

	<div class="pngx-engine-grid-full-width">
		<div class="pngx-engine-options-api-key__wrap">
			<?php
			$args = [
				'label'        => _x( 'Defaults', 'The title of the default settings accordion for an API profile.', 'cctor-test' ),
				'id'           => 'cctor-coupon-test-options__' . uniqid(),
				'classes_wrap' => [ 'cctor-coupon-test-options__accordion-wrapper' ],
				'panel'        => $this->template( '/test/profiles/components/panel', [ 'api'          => $api,
																						'api_id'       => $api_id,
																						'api_name'     => $api_name,
																						'unique_id'    => $unique_id,
																						'profile_data' => $profile_data
				], false ),
				'expanded'     => true,
			];
			$this->template( 'components/accordion', $args );
			?>
		</div>
	</div>
</div>
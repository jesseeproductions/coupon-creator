<?php
/**
 * View: Test - Create Button.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/pngx/admin-views/test/components/create-button.php
 *
 * See more documentation about our views templating system.
 *
 * @since   0.1.0
 *
 * @version 0.1.0
 *
 * @link    https://pngx.ink/RYIOh
 *
 * @var Url $url An instance of the URL handler.
 */

$save_link  = $url->to_create_test_link();
$save_label = _x( 'Create', 'Create test button from content.', 'cctor-test' );
$field_wrap_classes = [ 'pngx-field', 'pngx-field-create-wrap' ];
if ( ! empty( $classes_wrap ) ) {
	$field_wrap_classes = array_merge( $field_wrap_classes, $classes_wrap );
}
?>
<fieldset
	<?php pngx_classes( $field_wrap_classes ); ?>
>
	<div
	>
		<button
			class="cctor-coupon-test-pro-options-details-action__create cctor-coupon-test-options-test-details-action__create button-primary"
			type="button"
			data-ajax-create-url="<?php echo $save_link; ?>"
		>
			<span>
				<?php echo esc_html( $save_label ); ?>
			</span>
		</button>
	</div>
</fieldset>
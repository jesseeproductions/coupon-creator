<?php
/**
 * Class to manage Test API access.
 *
 * @since 0.1.0
 *
 * @package Cctor\Coupon\Test
 */

namespace Cctor\Coupon\Test;

use Pngx\Traits\With_AJAX;
use Cctor\Coupon\Test\Access_Profiles\Abstract_Access_Profiles_AJAX;
use Pngx__Utils__Array as Arr;


/**
 * Class Api
 *
 * @since 0.1.0
 *
 * @package Cctor\Coupon\Test
 */
class Api extends Abstract_Access_Profiles_AJAX {
	use With_AJAX;

	/**
	 * @inheritdoc
	 */
	public static $api_name = 'Test';

	/**
	 * @inheritdoc
	 */
	public static $api_id = 'test';

	/**
	 * @inerhitDoc
	 */
	protected $all_profiles = 'cctor_coupon_test_profiles';

	/**
	 * @inerhitDoc
	 */
	protected $single_profile_prefix = 'cctor_coupon_test_profile_';

	/**
	 * The custom meta test saved key.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	public static $saved_test_key = '_test_indexed';

	/**
	 * API constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param Actions                $actions                An instance of the Actions name handler.
	 * @param Template_Modifications $template_modifications An instance of the Template_Modifications.
	 */
	public function __construct( Actions $actions, Template_Modifications $template_modifications ) {
		$this->actions                = $actions;
		$this->template_modifications = $template_modifications;
	}

	/**
	 * @inerhitDoc
	 */
	public function get_unique_id() {
		return uniqid( "cctor_test_", true );
	}

	/**
	 * @inheritDoc
	 */
	public function save_profile( $unique_id, $primary, $name ) {
		$defaults     = Arr::escape_multidimensional_array( pngx_get_request_var( 'defaults', [] ) );
		$profile_data = [
			'primary'     => $primary,
			'name'        => esc_html( stripslashes( $name ) ),
			'last-access' => '-',
			'defaults'    => $defaults,
		];
		$this->set_profile_by_id( $unique_id, $profile_data );

		$message = _x(
			'Test Profile saved.',
			'Test Profile saved message.',
			'cctor-test'
		);
		$this->template_modifications->print_settings_message_template( $message );

		// Add saved fields template.
		$this->template_modifications->get_profile_fields(
			$this,
			$unique_id,
			$profile_data,
			'saved'
		);

		return;
	}

	/**
	 * @inheritDoc
	 */
	public static function get_confirmation_to_delete_profile() {
		return sprintf(
			_x(
				'Are you sure you want to delete this profile?',
				'',
				'cctor-test'
			),
		);
	}

	/**
	 * Save the test meta.
	 *
	 * @since 0.1.0
	 *
	 * @param int $test_id The test post id.
	 */
	public function save_test_meta( $test_id ) {
		update_post_meta( $test_id, static::$saved_test_key, true );
	}

	/**
	 * Gets the default fields for the profiles api parameters.
	 *
	 * @since 0.1.0
	 *
	 * @param array<string|mixed> $profile_data The profile data.
	 *
	 * @return array<string|mixed> The array of defaults for the panel.
	 */
	public function get_panel_fields( $profile_data = [] ) {
		$panel_fields = [];

		$overlap_tooltip = _x( 'Tooltip.', 'Information for Test.', 'coupon-test' );

		$panel_fields['overlap'] = [
			'id'            => 'overlap',
			'label'         => _x( 'Overlap', "Label for Test.", 'coupon-test' ),
			'tooltip'       => $overlap_tooltip,
			'template'      => 'number',
			'template_args' => [
				'attrs'         => [],
				'placeholder'   => _x( 'Set.', "The placeholder for the Test overlap.", 'coupon-test' ),
				'screen_reader' => _x( 'Set the overlap.', "The screen reader text of the label for the Test overlap.", 'coupon-test' ),
				'value'         => $profile_data['defaults']['overlap'] ?? 200,
				'min'           => 0,
				'max'           => 9999999,
				'step'          => 1,
			]
		];

		return $panel_fields;
	}
}

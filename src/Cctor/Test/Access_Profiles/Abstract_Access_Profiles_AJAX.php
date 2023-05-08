<?php
/**
 * Abstract Class to AJAX of Access Profiles.
 *
 * @since 0.1.0
 *
 * @package Cctor\Coupon\Test\Access_Profiles
 */

namespace Cctor\Coupon\Test\Access_Profiles;

use Pngx\Traits\With_AJAX;
use Pngx__Utils__Array as Arr;

/**
 * Class Abstract_Access_Profiles
 *
 * @since 0.1.0
 *
 * @package Cctor\Coupon\Test\Access_Profiles
 */
abstract class Abstract_Access_Profiles_AJAX extends Abstract_Access_Profiles {
	use With_AJAX;

	/**
	 * Add an Profile fields using ajax.
	 *
	 * @since 0.1.0
	 *
	 * @param string $nonce The add action nonce to check.
	 *
	 * @return string An html message for success or failure and the html of the Profile fields.
	 */
	public function ajax_add_profile( $nonce ) {
		if ( ! $this->check_ajax_nonce( $this->actions::$add_profile_action, $nonce ) ) {
			return false;
		}

		// Add empty fields template
		$this->template_modifications->get_profile_fields(
			$this,
			$this->get_unique_id(),
			[
				'name'         => '',
				'api-key'      => '',
			]
		);

		wp_die();
	}

	/**
	 * Save an Profile.
	 *
	 * @since 0.1.0
	 *
	 * @param string $nonce The add action nonce to check.
	 *
	 * @return string An html message for success or failure and the html of the Profile fields.
	 */
	public function ajax_save( $nonce ) {
		if ( ! $this->check_ajax_nonce( $this->actions::$save_action, $nonce ) ) {
			return false;
		}

		$unique_id = pngx_get_request_var( 'unique_id' );
		if ( empty( $unique_id ) ) {
			$message = _x(
				'An Access Profile unique id is missing, please reload and try to save again.',
				'Access Profile missing unique id.',
				'cctor-test'
			);
			$this->template_modifications->print_settings_message_template( $message, 'error' );

			wp_die();
		}

		$primary = pngx_get_request_var( 'primary' );
		if ( empty( $primary ) ) {
			$message = _x(
				'API Key or primary field missing, please add or select one before saving.',
				'API Key or primary field missing message.',
				'cctor-test'
			);
			$this->template_modifications->print_settings_message_template( $message, 'error' );

			wp_die();
		}

		$name = pngx_get_request_var( 'name' );
		if ( empty( $name ) ) {
			$message = _x(
				'Access Profile is missing a name.',
				'Access Profile is missing a name message.',
				'cctor-test'
			);
			$this->template_modifications->print_settings_message_template( $message, 'error' );

			wp_die();
		}

		$this->save_profile( $unique_id, $primary, $name );

		wp_die();
	}

	/**
	 * Save the Profile.
	 *
	 * @since 0.1.0
	 *
	 * @param string $unique_id The unique id of the profile.
	 * @param string $primary   The primary field of the profile.
	 * @param string $name      The name of the profile.
	 *
	 * @return string An html message for success or failure and the html of the Profile fields.
	 */
	abstract public function save_profile( $unique_id, $primary, $name );

	/**
	 * Update an Profile.
	 *
	 * @since 0.1.0
	 *
	 * @param string $nonce The add action nonce to check.
	 *
	 * @return string An html message for success or failure and the html of the Profile fields.
	 */
	public function ajax_update( $nonce ) {
		if ( ! $this->check_ajax_nonce( $this->actions::$update_action, $nonce ) ) {
			return false;
		}

		$unique_id = pngx_get_request_var( 'unique_id' );
		if ( empty( $unique_id ) ) {
			$message = _x(
				'Access Profile unique id is missing, please reload and try to save again.',
				'Access Profile is missing unique id.',
				'cctor-test'
			);
			$this->template_modifications->print_settings_message_template( $message, 'error' );

			wp_die();
		}

		$default_fields = $this->get_panel_fields();
		$defaults = Arr::escape_multidimensional_array( pngx_get_request_var( 'defaults', [] ) );
		foreach ( $default_fields as $field ) {
			$profile_defaults[ $field['id'] ] = isset( $defaults[ $field['id'] ] ) ? $defaults[ $field['id'] ] : $field['template_args']['value'];
		}
		$this->set_profile_defaults( $unique_id, $profile_defaults );

		$message = _x(
			'Access Profile saved.',
			'Access Profile saved message.',
			'cctor-test'
		);
		$this->template_modifications->print_settings_message_template( $message );


		wp_die();
	}

	/**
	 * Handles the request to delete an Access Profile.
	 *
	 * @since 0.1.0
	 *
	 * @param string|null $nonce The nonce that should accompany the request.
	 *
	 * @return bool Whether the request was handled or not.
	 */
	public function ajax_delete( $nonce = null ) {
		if ( ! $this->check_ajax_nonce( $this->actions::$delete_action, $nonce ) ) {
			return false;
		}

		$unique_id = pngx_get_request_var( 'unique_id' );
		if ( empty( $unique_id ) ) {
			$message = _x(
				'Access Profile unique id is missing, please reload and try to save again.',
				'Access Profile is missing unique id.',
				'cctor-test'
			);
			$this->template_modifications->print_settings_message_template( $message, 'error' );

			wp_die();
		}

		$success = $this->delete_profile_by_id( $unique_id );
		if ( $success ) {
			$message = _x(
				'Access Profile was successfully deleted',
				'Access Profile has been deleted success message.',
				'cctor-test'
			);

			$this->template_modifications->print_settings_message_template( $message );

			wp_die();
		}

		$error_message = _x(
			'Access Profile was not deleted',
			'Access Profile could not be deleted failure message.',
			'cctor-test'
		);

		$this->template_modifications->print_settings_message_template( $error_message, 'error' );

		wp_die();
	}
}

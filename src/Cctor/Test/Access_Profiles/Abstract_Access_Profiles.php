<?php
/**
 * Abstract Class to Manage Multiple Access Profiles.
 *
 * @since 0.1.0
 *
 * @package Cctor\Coupon\Test\Access_Profiles
 */

namespace Cctor\Coupon\Test\Access_Profiles;

use Pngx__Utils__Array as Arr;
use WP_Error;

/**
 * Class Abstract_Access_Profiles
 *
 * @since 0.1.0
 *
 * @package Cctor\Coupon\Test\Access_Profiles
 */
abstract class Abstract_Access_Profiles {

	/**
	 * The name of the API
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	public static $api_name;

	/**
	 * The id of the API
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	public static $api_id;

	/**
	 * Whether a profile has been loaded for the API to use.
	 *
	 * @since 0.1.0
	 *
	 * @var boolean
	 */
	protected $profile_loaded = false;

	/**
	 * The name of the loaded profile.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	public $loaded_profile_name = '';

	/**
	 * The key to get the option with a list of all profiles.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $all_profiles;

	/**
	 * The prefix to save all single profiles.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $single_profile_prefix;

	/**
	 * The primary field of the loaded profile, typically an API key.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $primary = '';

	/**
	 * The defaults for the profile.
	 *
	 * @since 0.1.0
	 *
	 * @var array<string|mixed>
	 */
	protected array $defaults = [];

	/**
	 * The last access of the profile.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $last_access = '';

	/**
	 * An instance of the Template_Modifications.
	 *
	 * @since 0.1.0
	 *
	 * @var Template_Modifications
	 */
	protected $template_modifications;

	/**
	 * The Actions name handler.
	 *
	 * @since 0.1.0
	 *
	 * @var Actions
	 */
	protected $actions;

	/**
	 * Get a unique id to the the save key too.
	 * Does not need to truely be unique.
	 *
	 * @since 0.1.0
	 *
	 * @return string The generated unique id.
	 */
	public abstract function get_unique_id();

	/**
	 * Gets the loaded primary field.
	 *
	 * @since 0.1.0
	 *
	 * @return string the loaded primary property.
	 */
	public function get_primary() {
		return $this->primary;
	}

	/**
	 * Gets the loaded profile defaults.
	 *
	 * @since 0.1.0
	 *
	 * @return array<string|mixed> the loaded profile defaults.
	 */
	public function get_defaults() {
		return $this->defaults;
	}

	/**
	 * Checks whether the current API is ready to use.
	 *
	 * @since 0.1.0
	 *
	 * @return bool Whether the current API has a loaded primary field.
	 */
	public function is_ready() {
		return ! empty( $this->profile_loaded );
	}

	/**
	 * Load a specific profile.
	 *
	 * @since 0.1.0
	 *
	 * @param string               $unique_id    The unique ID for the profile.
	 * @param array<string|string> $profile_data Profile fields data.
	 *
	 * @return bool|WP_Error Return true if loaded or WP_Error otherwise.
	 */
	public function load_profile( $unique_id, array $profile_data ) {
		$valid = $this->is_valid_profile( $profile_data );
		if ( is_wp_error( $valid ) ) {
			return $valid;
		}

		$this->init_profile( $unique_id, $profile_data );

		return true;
	}

	/**
	 * Load a specific profile by the id.
	 *
	 * @since 0.1.0
	 *
	 * @param string $unique_id The unique ID for the profile.
	 *
	 * @return bool|WP_Error Whether the page is loaded or a WP_Error code.
	 */
	public function load_profile_by_id( $unique_id ) {
		$profile_data = $this->get_profile_by_id( $unique_id );

		// Return false if no profile data.
		if ( empty( $profile_data ) ) {
			$error_msg = _x(
				'Access Profile failed to load, please check the value and try again.',
				'Access Profile failure message.',
				'cctor-test'
			);

			return new WP_Error( 'profile_not_found', $error_msg, [ 'status' => 400 ] );
		}

		return $this->load_profile( $unique_id, $profile_data );
	}

	/**
	 * Check if a profile has all the information to be valid.
	 *
	 * @since 0.1.0
	 *
	 * @param array<string|string> $profile_date Profile fields for an Integration.
	 *
	 * @return bool|WP_Error Return true if loaded or WP_Error otherwise.
	 */
	protected function is_valid_profile( $profile_date ) {
		if ( empty( $profile_date['primary'] ) ) {
			$error = _x(
				'Primary field is required.',
				'Access Profile authorization error for no primary field.',
				'cctor-test'
			);

			return new WP_Error( 'profile_no_primary', $error, [ 'status' => 400 ] );
		}

		if ( empty( $profile_date['name'] ) ) {
			$error = _x(
				'Access Profile is missing a name.',
				'Access Profile authorization error for no name.',
				'cctor-test'
			);

			return new WP_Error( 'profile_no_name', $error, [ 'status' => 400 ] );
		}

		return true;
	}

	/**
	 * Initialize an Profile to use for the API.
	 *
	 * @since 0.1.0
	 *
	 * @param array<string|string> $profile_date A profile of data for an integration.
	 */
	protected function init_profile( $unique_id, $profile_date ) {
		$this->unique_id           = $unique_id;
		$this->primary             = $profile_date['primary'];
		$this->last_access         = $profile_date['last-access'];
		$this->profile_loaded      = true;
		$this->loaded_profile_name = $profile_date['name'];
		$this->defaults            = $profile_date['defaults'];
	}

	/**
	 * Get the listing of Profiles.
	 *
	 * @since 0.1.0
	 *
	 * @param boolean $all_data Whether to return all profile data, default is only name and status.
	 *
	 * @return array<string|string> $list_of_profiles An array of all the Profiles.
	 */
	public function get_list_of_profiles( $all_data = false ) {
		$list_of_profiles = get_option( $this->all_profiles, [] );
		if ( ! is_array( $list_of_profiles ) ) {
			return [];
		}

		foreach ( $list_of_profiles as $unique_id => $profile_base_data ) {
			if ( empty( $profile_base_data['name'] ) ) {
				continue;
			}
			$list_of_profiles[ $unique_id ]['name'] = $profile_base_data['name'];

			// If false (default ) skip getting all the profile data.
			if ( empty( $all_data ) ) {
				continue;
			}
			$profile_data = $this->get_profile_by_id( $unique_id );

			$list_of_profiles[ $unique_id ] = $profile_data;
		}

		return $list_of_profiles;
	}

	/**
	 * Update the list of profiles with provided profile.
	 *
	 * @since 0.1.0
	 *
	 * @param string               $unique_id    The unique ID for the profile.
	 * @param array<string|string> $profile_data The array of data for a profile to add to the list.
	 */
	protected function update_list_of_profiles( $unique_id, $profile_data ) {
		$profiles = $this->get_list_of_profiles();

		/**
		 * Fires after before the profile list is updated for an API.
		 *
		 * @since 0.1.0
		 *
		 * @param array<string,mixed>  An array of Profiles formatted for options dropdown.
		 * @param array<string|string> $profile_data The array of data for a profile to add to the list.
		 * @param string               $api_id       The id of the API in use.
		 */
		do_action( 'cctor_coupon_test_before_update_accunts_profiless', $profiles, $profile_data, static::$api_id );

		$profiles[ esc_attr( $unique_id ) ] = [
			'name' => esc_attr( $profile_data['name'] ),
		];

		update_option( $this->all_profiles, $profiles );
	}

	/**
	 * Delete from the list of Profiles with the provided profile id.
	 *
	 * @since 0.1.0
	 *
	 * @param string $unique_id The unique ID for the profile.
	 */
	protected function delete_from_list_of_profiles( $unique_id ) {
		$profiles = $this->get_list_of_profiles();
		unset( $profiles[ $unique_id ] );

		update_option( $this->all_profiles, $profiles );
	}

	/**
	 * Get a Single Profile by id.
	 *
	 * @since 0.1.0
	 *
	 * @param string $unique_id The unique ID for the profile.
	 *
	 * @return array<string|string> $profile The integration profile data.
	 */
	public function get_profile_by_id( $unique_id ) {
		return get_option( $this->single_profile_prefix . $unique_id, [] );
	}

	/**
	 * Set an profile with the provided id.
	 *
	 * @since 0.1.0
	 *
	 * @param string               $unique_id    The unique ID for the profile.
	 * @param array<string|string> $profile_data A specific profile to save.
	 */
	public function set_profile_by_id( $unique_id, array $profile_data ) {
		update_option( $this->single_profile_prefix . $unique_id, $profile_data, false );

		$this->update_list_of_profiles( $unique_id, $profile_data );
	}

	/**
	 * Updates the last access valid access of the profile.
	 *
	 * @since 0.1.0
	 *
	 * @param string $unique_id  The Profile unique id.
	 * @param string $app_name The optional app name used with this Profile pair.
	 */
	public function set_profile_last_access( $unique_id, $app_name = '' ) {
		$profile_data                = $this->get_profile_by_id( $unique_id );
		$profile_data['last-access'] = $this->get_last_access( $app_name );

		update_option( $this->single_profile_prefix . $unique_id, $profile_data, false );
	}

	/**
	 * Updates the defaults of an Profile.
	 *
	 * @since 0.1.0
	 *
	 * @param string $unique_id  The unique id of the profile.
	 */
	public function set_profile_defaults( $unique_id, $defaults ) {
		$profile_data                = $this->get_profile_by_id( $unique_id );
		$profile_data['defaults'] = $defaults;

		update_option( $this->single_profile_prefix . $unique_id, $profile_data, false );
	}

	/**
	 * Delete a profile by ID.
	 *
	 * @since 0.1.0
	 *
	 * @param string $unique_id The unique ID for the profile.
	 *
	 * @return bool Whether the profile has been deleted.
	 */
	public function delete_profile_by_id( $unique_id ) {
		delete_option( $this->single_profile_prefix . $unique_id );

		$this->delete_from_list_of_profiles( $unique_id );

		return true;
	}

	/**
	 * Get the last access with provided app name.
	 *
	 * @since 0.1.0
	 *
	 * @param string $app_name The optional app name used with this profile.
	 */
	public function get_last_access( $app_name = '' ) {
		/**
		 * Filters the last access app name.
		 *
		 * @since 0.1.0
		 *
		 * @param string $app_name The app name to display in last access, default is none.
		 */
		$app_name = apply_filters( 'cctor_coupon_test_last_access_app_name', $app_name );

		if ( $app_name ) {
			$app_name = $app_name . '|';
		}
		// Get the current date and time as a DateTime object, adjusted for the site's timezone.
		$local_time = current_datetime();

		return $app_name . $local_time->format( 'Y-m-d H:i:s' );
	}

	/**
	 * Get list of profiles formatted for dropdown for post type.
	 *
	 * @since 0.1.0
	 *
	 * @param int    $post_id       The post id for the meta.
	 * @param string $post_meta_key The post meta key.
	 *
	 * @return array<string,mixed>  An array of profiles formatted for dropdown.
	 */
	public function get_profiles_options_list( $post_id, $post_meta_key ) {
		$available_profiles = $this->get_list_of_profiles();
		$current_key = get_post_meta( $post_id, $post_meta_key, true );

		if ( empty( $available_profiles ) ) {
			return [];
		}

		$profiles = [];
		foreach ( $available_profiles as $key => $profile ) {
			if ( empty( $profile['name'] ) ) {
				continue;
			}

			$profiles[ (string) $key ] = [
				'text'     => (string) trim( $profile['name'] ),
				'sort'     => (string) trim( $profile['name'] ),
				'id'       => (string) $key,
				'value'    => (string) $key,
				'selected' => $current_key === (string ) $key ? true : false,
			];
		}

		// Sort the users array by text(email).
		$sort_arr = array_column( $profiles, 'sort' );
		array_multisort( $sort_arr, SORT_ASC, $profiles );

		return $profiles;
	}

	/**
	 * Get list of profiles formatted for options dropdown.
	 *
	 * @since 0.1.0
	 *
	 * @param boolean $all_data Whether to return only active profiles or not.
	 *
	 * @return array<string,mixed>  An array of Profiles formatted for options dropdown.
	 */
	public function get_formatted_profile_list() {
		$available_profiles = $this->get_list_of_profiles( true );
		if ( empty( $available_profiles ) ) {
			return [];
		}

		$profiles = [];
		foreach ( $available_profiles as $key => $profile ) {
			$name    = Arr::get( $profile, 'name', '' );
			$primary = Arr::get( $profile, 'primary', '' );

			if ( empty( $name ) || empty( $key ) || empty( $primary ) ) {
				continue;
			}

			$profiles[] = [
				'text'    => (string) $name,
				'id'      => (string) $key,
				'sort'    => (string) trim( $name ),
				'primary' => (string) $primary,
			];
		}

		// Sort the users array by name.
		$sort_arr = array_column( $profiles, 'sort' );
		array_multisort( $sort_arr, SORT_ASC, $profiles );

		return $profiles;
	}
}

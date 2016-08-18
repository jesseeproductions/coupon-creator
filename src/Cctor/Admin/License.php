<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


/**
 * License Methods
 *
 */
class Cctor__Coupon__Admin__License {

	private static $default_update_url = 'https://couponcreatorplugin.com/edd-sl-api/';
	private $update_url = '';

	public function __construct() {

		$this->set_update_url();

	}

	/**
	 * Get the PUE update API endpoint url
	 *
	 * @return string
	 */
	public function get_update_url() {
		return apply_filters( 'pngx_get_update_url', $this->update_url );
	}

	/**
	 * Set the Plugin update URL
	 *
	 * This can be overridden using the global constant 'COUPON_CREATOR_STORE_URL'
	 *
	 */
	private function set_update_url() {
		$this->update_url = ( defined( 'COUPON_CREATOR_STORE_URL' ) ) ? trailingslashit( COUPON_CREATOR_STORE_URL ) : trailingslashit( $this->default_update_url );
	}

	/*
	* Register and Enqueue Style and Scripts on Coupon Edit Screens
	*
	*/
	public static function activate_license() {

		// listen for our activate button to be clicked
		if ( isset( $_POST['pngx_license_activate'] ) ) {

			// run a quick security check
			if ( ! check_admin_referer( 'pngx_license_nonce', 'pngx_license_nonce' ) ) {
				return false; // get out if we didn't click the Activate button
			}

			//Set WordPress Option Name
			$license_option_name = esc_attr( $_POST['pngx_license_key'] );

			// retrieve the license from the database
			$license_info = get_option( $license_option_name );

			//Check if the License has changed and deactivate
			if ( $_POST['coupon_creator_options'][ $license_option_name ] != $license_info['key'] ) {

				$license_info['key'] = esc_attr( trim( $_POST['coupon_creator_options'][ $license_option_name ] ) );

				delete_option( $license_option_name );

				update_option( $license_option_name, $license_info );

			}

			// data to send in our API request
			$api_params = array(
				'edd_action' => 'activate_license',
				'license'    => esc_attr( trim( $license_info['key'] ) ),
				'item_name'  => urlencode( esc_attr( $_POST['pngx_license_name'] ) ), // the name of our product in EDD
				'url'        => home_url()
			);

			// Call the custom API.
			$response = wp_remote_get( esc_url_raw( add_query_arg( $api_params, self::get_update_url() ) ), array( 'timeout'   => 15,
			                                                                                                         'sslverify' => false
			) );

			// make sure the response came back okay
			if ( is_wp_error( $response ) ) {
				return false;
			}
			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			//Remove Current Expiration
			$license_info['status'] = "nostatus";

			//Get Status of Key
			$license_info['status'] = esc_html( $license_data->license );

			//Remove Current Expiration
			unset( $license_info['expires'] );

			//Set Expiration Date  for This License
			$license_info['expires'] = esc_html( $license_data->expires );

			//if Expired Add that to the option.
			if ( isset( $license_data->error ) && $license_data->error == "expired" ) {
				$license_info['expired'] = esc_html( $license_data->error );
			}

			//if Expired Add that to the option.
			if ( isset( $license_data->error ) && $license_data->error == "missing" ) {
				unset( $license_info['expires'] );
				unset( $license_info['expired'] );
				$license_info['status'] = esc_html( $license_data->error );
			}

			//Update License Object
			update_option( $license_option_name, $license_info );

		}

		return true;
	}

	/*
	* Deactivate a license key.
	*
	*/
	public static function deactivate_license() {

		// listen for our activate button to be clicked
		if ( isset( $_POST['pngx_license_deactivate'] ) ) {

			// run a quick security check
			if ( ! check_admin_referer( 'pngx_license_nonce', 'pngx_license_nonce' ) ) {
				return false; // get out if we didn't click the Activate button
			}

			$license_option_name = esc_attr( $_POST['pngx_license_key'] );

			// retrieve the license from the database
			$license_info = get_option( $license_option_name );

			// data to send in our API request
			$api_params = array(
				'edd_action' => 'deactivate_license',
				'license'    => esc_attr( trim( $license_info['key'] ) ),
				'item_name'  => urlencode( esc_attr( $_POST['pngx_license_name'] ) ),
				// the name of our product in EDD
				'url'        => home_url()
			);

			// Call the custom API.
			$response = wp_remote_get( esc_url_raw( add_query_arg( $api_params,  self::get_update_url() ) ), array(
				'timeout'   => 15,
				'sslverify' => false
			) );

			// make sure the response came back okay
			if ( is_wp_error( $response ) ) {
				return false;
			}

			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "deactivated" or "failed"
			if ( $license_data->license == 'deactivated' || $license_data->license == 'failed' ) {

				unset( $license_info['status'] );
				unset( $license_info['expires'] );

				//Update License Object
				update_option( $license_option_name, $license_info );
			}

		}

		return true;
	}
}
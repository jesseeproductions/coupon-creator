<?php
/**
 * Admin template modifications class.
 *
 * @since 0.1.0
 *
 * @package Cctor\Coupon\Test
 */

namespace Cctor\Coupon\Test;

use Cctor\Coupon\Templates\Admin_Template;
use Cctor\Coupon\Test\Access_Profiles\Abstract_Access_Profiles;

/**
 * Class Template_Modifications
 *
 * @since 0.1.0
 *
 * @package Cctor\Coupon\Test
 */
class Template_Modifications {

	/**
	 * @inheritdoc
	 */
	public static $api_name = 'Test';

	/**
	 * @inheritdoc
	 */
	public static $api_id = 'test';

	/**
	 * The prefix, in the context of pngx options, of each setting for these options.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	public static $option_prefix;

	/**
	 * Template_Modifications constructor.
	 *
	 * @since 0.1.0
	 *
	 * @param Admin_Template $template An instance of the backend template handler.
	 * @param Url            $Url      An instance of the URl handler.
	 */
	public function __construct( Admin_Template $admin_template, Url $url ) {
		$this->admin_template = $admin_template;
		$this->url            = $url;
		self::$api_id         = 'test';
		self::$option_prefix  = Options::$option_prefix;
	}

	/**
	 * Get the message template to display on user changes for an API.
	 *
	 * @since 0.1.0
	 *
	 * @param string $message The message to display.
	 * @param string $type    The type of message, either updated or error.
	 * @param bool   $echo    Whether to echo or return the template.
	 *
	 * @return string The message with html to display
	 */
	public function get_settings_message_template( $message, $type = 'updated', $echo = false ) {
		return $this->admin_template->template( 'components/message', [
			'message' => $message,
			'type'    => $type,
			$echo
		] );
	}

	/**
	 * Print the message template to display on user changes for an API.
	 *
	 * @since TBD
	 *
	 * @param string $message The message to display.
	 * @param string $type    The type of message, either updated or error.
	 * @param bool   $echo    Whether to echo or return the template.
	 *
	 * @return string The message with html to display
	 */
	public function print_settings_message_template( $message, $type = 'updated', $echo = true ) {
		return $this->get_settings_message_template( $message, $type, $echo );
	}

	/**
	 * Get the saved test template when an test is saved.
	 *
	 * @since 0.1.0
	 *
	 * @param int  $test_id The message to display.
	 * @param bool $echo          Whether to echo or return the template.
	 *
	 * @return string The html of the template.
	 */
	public function get_test_saved_template( $test_id, $echo = false ) {
		return $this->admin_template->template( '/test/components/saved', [
			'post_id' => $test_id,
			'url'           => '',
			$echo
		] );
	}

	/**
	 * Print the saved test template when an test is saved.
	 *
	 * @since 0.1.0
	 *
	 * @param int  $test_id The message to display.
	 * @param bool $echo          Whether to echo or return the template.
	 *
	 * @return string The html of the template.
	 */
	public function print_test_saved_template( $test_id, $echo = true ) {
		return $this->get_test_saved_template( $test_id, $echo );
	}

	/**
	 * @inheritDoc
	 */
	public function get_option_obj() {
		return pngx( Options::class );
	}

	/**
	 * Get intro text for an API Settings.
	 *
	 * @since 0.1.0
	 *
	 * @return string HTML for the intro text.
	 */
	public function get_intro_text() {
		$args = [
			'allowed_html' => [
				'a' => [
					'href'   => [],
					'target' => [],
				],
			],
		];

		return $this->admin_template->template( static::$api_id . '/profiles/intro-text', $args, false );
	}

	/**
	 * Adds an profile authorize fields.
	 *
	 * @since 0.1.0
	 *
	 * @param Abstract_Access_Profiles $api An instance of an API handler.
	 * @param Url      $url The URLs handler for the integration.
	 *
	 * @return string HTML for the profile fields.
	 */
	public function get_profile_authorize_fields( Abstract_Access_Profiles $api, Url $url ) {
		/** @var \Pngx_cache $cache */
		$cache   = pngx( 'cache' );
		$message = $cache->get_transient( static::$option_prefix . '_api_message' );
		if ( $message ) {
			$cache->delete_transient( static::$option_prefix . '_api_message' );
		}

		$args = [
			'api'     => $api,
			'url'     => $url,
			'message' => $message,
		];

		return $this->admin_template->template( static::$api_id . '/profiles/profile-fields', $args, false );
	}

	/**
	 * Get the Profile field row.
	 *
	 * @since 0.1.0
	 *
	 * @param Abstract_Access_Profiles $api          An instance of an API handler.
	 * @param string                   $unique_id    The unique ID for the profile.
	 * @param array<string|mixed>      $profile_data The API Key data.
	 * @param string                   $type         A string of the type of fields to load ( new and generated ).
	 *
	 * @return string The profile field row html.
	 */
	public function get_profile_fields( Abstract_Access_Profiles $api, $unique_id, $profile_data, $type = 'new' ) {
		return $this->admin_template->template( static::$api_id . '/profiles/list/profile-' . $type, [
			'api'          => $api,
			'unique_id'    => $unique_id,
			'profile_data' => $profile_data,
			'url'          => $this->url,
		] );
	}
}

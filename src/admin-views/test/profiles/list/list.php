<?php
/**
 * View: Integration Profile List.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/pngx/admin-views/test/profiles/list/list.php
 *
 * See more documentation about our views templating system.
 *
 * @since 0.1.0
 *
 * @version 0.1.0
 *
 * @link    https://pngx.ink/RYIOh
 *
 * @var Api                 $api   An instance of the Test API handler.
 * @var Url                 $url   An instance of the URL handler.
 * @var array<string|mixed> $profiles  An array of profiles to authenticate with Test.
 * @var array<string|mixed> $users An array of WordPress users to create an Profile for.
 */

$this->template( 'test/profiles/list/list-header', [] );

if ( empty( $profiles ) ) {
	$this->template( 'test/profiles/list/profile-new', [
		'api'          => $api,
		'unique_id'    => $api->get_unique_id(),
		'profile_data' => [
			'primary' => '',
			'name'    => '',
		],
		'url'          => $url,
	] );

	return;
}
?>
<?php foreach ( $profiles as $unique_id => $profile_data ) : ?>
	<?php
	$this->template( 'test/profiles/list/profile-saved', [
		'api'          => $api,
		'unique_id'    => $unique_id,
		'profile_data' => $profile_data,
		'url'          => $url,
	] );
	?>
<?php endforeach; ?>

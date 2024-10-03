<?php
/**
 * App environment variables/settings
 * Self explanatory file :)
 *
 * @package Onyx Theme
 */

$onyx_local = (wp_get_environment_type() === 'local');

return [
	'version' => '1.0',
	'local'   => $onyx_local,
	'theme'   => get_template(),
	'dir_uri' => get_template_directory_uri(),
	'dir'     => get_template_directory(),
	'desc'    => get_bloginfo( 'description' ),
	'name'    => get_bloginfo( 'name' ),
	'url'     => get_home_url(),
	'user'    => wp_get_current_user()->user_email,
	'token'   => defined( 'ONYX_TOKEN' ) ? ONYX_TOKEN : null,
	'devs'    => defined( 'ONYX_DEVELOPERS' ) ? ONYX_DEVELOPERS : [ '' ],
	'timber'  => [
		'cache'       => $onyx_local ? false : WP_CONTENT_DIR . '/cache/timber',
		'auto_reload' => $onyx_local,
		'autoscape'   => false,
	],
	'uploads' => [
		'max_file_size' => defined( 'ONYX_MAX_FILE_SIZE' ) ? ONYX_MAX_FILE_SIZE : 5000,
		'unset_types'   => defined( 'ONYX_UNSET_TYPES' ) ? ONYX_UNSET_TYPES : [
			'mp4|m4v',
			'mov|qt',
			'wmv',
			'avi',
			'mpeg|mpg|mpe',
			'3gp|3gpp',
			'3g2|3gp2',
			'asf|asx',
			'wmx',
			'wm',
			'divx',
			'flv',
			'ogv',
			'webm',
			'mkv',
		],
	],
];

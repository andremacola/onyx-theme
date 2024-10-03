<?php
/**
 * App environment variables/settings
 * Self explanatory file :)
 *
 * @package Onyx Theme
 */

return [
	'version'    => '1.0',
	'static'     => false,
	'livereload' => true,
	'theme'      => get_template(),
	'dir_uri'    => get_template_directory_uri(),
	'dir'        => get_template_directory(),
	'desc'       => get_bloginfo( 'description' ),
	'name'       => get_bloginfo( 'name' ),
	'url'        => get_home_url(),
	'user'       => wp_get_current_user()->user_email,
	'token'      => defined( 'ONYX_TOKEN' ) ? ONYX_TOKEN : '',
	'devs'       => defined( 'ONYX_DEVELOPERS' ) ? ONYX_DEVELOPERS : [ 'dev@domain.tld' ],
	'timber'     => [
		'cache'       => defined( 'ONYX_TIMBER_CACHE' ) ? ONYX_TIMBER_CACHE : WP_CONTENT_DIR . '/cache/timber',
		'auto_reload' => false,
		'autoscape'   => false,
		'debug'       => false,
	],
	'uploads'    => [
		'max_file_size' => 5000,
		'unset_types'   => [
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

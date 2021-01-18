<?php
/**
 * App environment variables/settings
 * Self explanatory file :)
 *
 * @package Onyx Theme
 */

return [
	'version' => '1.0',
	'static'  => false,
	'theme'   => get_template(),
	'dir_uri' => get_template_directory_uri(),
	'dir'     => get_template_directory(),
	'desc'    => get_bloginfo( 'description' ),
	'name'    => get_bloginfo( 'name' ),
	'url'     => get_home_url(),
	'rest'    => 'api',
	'user'    => wp_get_current_user()->user_email,
	'devs'    => [ 'dev@domain.tld' ],
	'timber'  => [
		'cache_dir' => WP_CONTENT_DIR . '/cache/timber',
	],
	'uploads' => [
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

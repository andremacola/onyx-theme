<?php
/**
 * Edit this file to register standard WordPress and Plugins filters and actions
 * To add custom hooks please assign to a function previous declared or add to a closure
 * Please do not add functions in this file. Use core/includes/hook-functions.php instead
 *
 * @package Onyx Theme
 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference
 * @see https://codex.wordpress.org/Plugin_API/Action_Reference
 *
 * @param string the action or filter HOOK [required]
 * @param string function to remove|add [required]
 * @param int    priority [optional]
 * @param int    accepted args [optional]
 */

return [

	/*
	|--------------------------------------------------------------------------
	| FILTERS (after_setup_theme)
	|--------------------------------------------------------------------------
	*/
	'filters' => [
		'remove' => [
			[ 'the_excerpt', 'wpautop', 10 ], // remove <p> from excerpt.

			// ----------------------------------------------------------
			// PLUGINS (remove:filters)
			// ----------------------------------------------------------

		],
		'add'    => [
			[ 'xmlrpc_enabled', '__return_false', 10 ], // disable xmlrpc
			[ 'use_default_gallery_style', '__return_false', 10 ], // disable classic gallery style
			[ 'the_generator', '__return_false', 10 ], // remove meta tag from wp feed.

			// ----------------------------------------------------------
			// PLUGINS (add:filters)
			// ----------------------------------------------------------

			// ----------------------------------------------------------
			// ADMIN (add:filters)
			// ----------------------------------------------------------

			// ----------------------------------------------------------
			// EDITOR CLASSIC (add:filters)
			// ----------------------------------------------------------
			// [ 'use_widgets_block_editor', '__return_false' ],

			// ----------------------------------------------------------
			// WP QUERIES
			// ----------------------------------------------------------

		],
		'apply'  => [],
	],

	/*
	|--------------------------------------------------------------------------
	| ACTIONS (after_setup_theme)
	|--------------------------------------------------------------------------
	*/
	'actions' => [
		'remove' => [
			[ 'wp_print_styles', 'print_emoji_styles', 10 ], // emoji styles from wp
			[ 'wp_head', 'wp_shortlink_wp_head', 10 ], // shortlink
			[ 'wp_head', 'wp_resource_hints', 2 ], // dns prefetch s.w.org
			[ 'wp_head', 'wp_oembed_add_discovery_links', 10 ], // oembed links
			[ 'wp_head', 'wp_generator', 10 ], // wp meta tag generator (deprecated)
			[ 'wp_head', 'wlwmanifest_link', 10 ], // Windows Live Writer
			[ 'wp_head', 'rsd_link', 10 ], // RSD Link
			[ 'wp_head', 'rest_output_link_wp_head', 10 ], // json api links from head
			[ 'wp_head', 'rel_canonical', 10 ], // canonical url
			[ 'wp_head', 'print_emoji_detection_script', 7 ], // emoji script from wp
			[ 'wp_head', 'feed_links', 2 ], // feeds (posts and comments)
			[ 'wp_head', 'feed_links_extra', 3 ], // extra feeds links (ex: categories)
			[ 'welcome_panel', 'wp_welcome_panel', 10 ], // welcome panel from dashboard
			[ 'template_redirect', 'rest_output_link_header', 11 ], // json api link from http header
		],
		'add'    => [],
	],
];

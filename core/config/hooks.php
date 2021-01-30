<?php
/**
 * Edit this file to register WordPress filters and actions
 * To add custom hooks please assign to a function previous declared or add to a closure
 * This file is related to core/includes/hook-functions.php
 * Please do not add functions in this file except closures
 *
 * Closure ex:
 *
 * ['wp_enqueue_scripts', function() {
 *     print_r('My Clojure');
 * }],
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
			// [ 'the_seo_framework_seobox_output', '__return_false' ],
			// [ 'the_seo_framework_indicator', '__return_false' ],
		],
		'add'    => [
			[ 'xmlrpc_enabled', '__return_false', 10 ], // disable xmlrpc
			[ 'use_default_gallery_style', '__return_false', 10 ], // disable classic gallery style
			[ 'the_generator', '__return_false', 10 ], // remove meta tag from wp feed.
			[ 'the_content', 'onyx_remove_empty_p', 20, 1 ],
			// [ 'single_template', 'onyx_single_cat_template' ],
			[ 'protected_title_format', 'onyx_remove_private_title' ],
			[ 'private_title_format', 'onyx_remove_private_title' ],
			[ 'img_caption_shortcode', 'onyx_better_img_caption', 10, 3 ],
			[ 'body_class', 'onyx_body_classes' ],

			// ----------------------------------------------------------
			// TIMBER(add:filters)
			// ----------------------------------------------------------
			[ 'timber/cache/location', 'onyx_timber_cache_folder' ],

			// ----------------------------------------------------------
			// REST API (add:filters)
			// ----------------------------------------------------------
			[ 'rest_url_prefix', 'onyx_rest_url_prefix' ],

			// ----------------------------------------------------------
			// PLUGINS (add:filters)
			// ----------------------------------------------------------
			[ 'acf/settings/show_admin', 'onyx_acf_show_admin' ],
			[ 'acf/init', 'onyx_acf_rename' ],
			// [ 'acf/fields/post_object/result', 'onyx_acf_object_result', 10, 4 ],
			// [ 'acf/fields/post_object/query', 'onyx_acf_post_object_query', 10, 3 ],
			// [ 'acp/search/is_active', '__return_false' ],
			// [ 'ac/suppress_site_wide_notices', '__return_true' ],

			// ----------------------------------------------------------
			// ADMIN (add:filters)
			// ----------------------------------------------------------
			[ 'upload_size_limit', 'onyx_upload_limit' ],
			[ 'upload_mimes', 'onyx_remove_mime_types' ],
			[ 'admin_footer_text', 'onyx_change_footer_text_admin' ],

			// ----------------------------------------------------------
			// EDITOR CLASSIC (add:filters)
			// ----------------------------------------------------------
			[ 'mce_buttons', 'onyx_editor_page_break' ],

			// ----------------------------------------------------------
			// WP QUERIES
			// ----------------------------------------------------------
			[ 'posts_request', 'onyx_supress_main_query', 10, 2 ],
		],
		'apply'  => [
			[ 'default_hidden_meta_boxes', 'onyx_show_hidden_excerpt', 10, 2 ],
		],
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
		'add'    => [
			[ 'wp_enqueue_scripts', 'onyx_enqueue_assets' ],
			// [ 'phpmailer_init', 'onyx_smtp_config' ],
			// [ 'wp_enqueue_scripts', 'onyx_remove_wp_jquery' ],
			// [ 'wp_enqueue_scripts', 'onyx_remove_wp_embed' ],
			// [ 'wp_enqueue_scripts', 'onyx_header_footer_scripts' ],
			// [ 'wp_enqueue_scripts', 'onyx_load_styles' ],
			// [ 'wp_enqueue_scripts', 'onyx_load_javascripts' ],

			// ----------------------------------------------------------
			// ADMIN (add:actions)
			// ----------------------------------------------------------
			[ 'wp_dashboard_setup', 'onyx_dashboard_widgets' ],
			[ 'wp_before_admin_bar_render', 'onyx_customize_admin_bar' ],
			[ 'login_enqueue_scripts', 'onyx_admin_scripts' ],
			[ 'enqueue_block_editor_assets', 'onyx_gutenberg_js' ],
			[ 'admin_init', 'onyx_gutenberg_style' ],
			[ 'admin_enqueue_scripts', 'onyx_admin_scripts' ],
			// [ 'admin_menu', 'onyx_disable_comments_trackbacks' ],
		],
	],
];

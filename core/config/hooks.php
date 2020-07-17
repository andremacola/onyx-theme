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
 * @param string      the action or filter HOOK [required]
 * @param string      function to remove|add [required]
 * @param int         priority [optional]
 * @param int         accepted args [optional]
 */

return [

	/*
	|--------------------------------------------------------------------------
	| FILTERS (after_setup_theme)
	|--------------------------------------------------------------------------
	*/
	'filters' => [
		'remove' => [
			// remove <p> from excerpt.
			[ 'the_excerpt', 'wpautop', 10 ],

			// ADMIN: The SEO FrameWork
			// [ 'the_seo_framework_seobox_output', '__return_false' ],
			// [ 'the_seo_framework_indicator', '__return_false' ],
		],
		'add'    => [
			// remove meta tag from wp feed.
			[ 'the_generator', '__return_false', 10 ],

			// disable xmlrpc
			[ 'xmlrpc_enabled', '__return_false', 10 ],

			// disable default gallery style (todo: verify with gutenberg).
			[ 'use_default_gallery_style', '__return_false', 10 ],

			// add custom classes to body_class.
			[ 'body_class', 'onyx_body_classes' ],

			// remove empty paragraphs.
			[ 'the_content', 'onyx_remove_empty_p', 20, 1 ],

			// add single templates by categories.
			[ 'single_template', 'onyx_single_cat_template' ],

			// adjust a better wp-caption without <p> and removing the additional 10px.
			[ 'img_caption_shortcode', 'onyx_better_img_caption', 10, 3 ],

			// remove private|protected prefix from title.
			[ 'private_title_format', 'onyx_remove_private_title' ],
			[ 'protected_title_format', 'onyx_remove_private_title' ],

			// ADMIN: ACF
			[ 'acf/init', 'onyx_acf_rename' ],
			[ 'acf/settings/show_admin', 'onyx_acf_show_admin' ],
			// [ 'acf/fields/post_object/result', 'onyx_acf_object_result', 10, 4 ],
			// [ 'acf/fields/post_object/query', 'onyx_acf_post_object_query', 10, 3 ],

			// ADMIN: COLUMNS PRO
			// [ 'acp/search/is_active', '__return_false' ],
			// [ 'ac/suppress_site_wide_notices', '__return_true' ],

			// ADMIN: customize admin footer text label.
			[ 'admin_footer_text', 'onyx_change_footer_text_admin' ],

			// ADMIN: Upload filters.
			[ 'upload_mimes', 'onyx_remove_mime_types' ],
			[ 'upload_size_limit', 'onyx_upload_limit' ],

			// EDITOR: add nextpage/pagebreak button to mce editor (deprecated).
			[ 'mce_buttons', 'onyx_editor_page_break' ],

			// QUERY: Supress main query on front/home page for performance.
			[ 'posts_request', 'onyx_supress_main_query', 10, 2 ],
		],
		'apply'  => [
			// show excerpt by default.
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
			// remove emoji script from wp.
			[ 'wp_head', 'print_emoji_detection_script', 7 ],

			// remove dns prefetch s.w.org.
			[ 'wp_head', 'wp_resource_hints', 2 ],

			// remove canonical url.
			[ 'wp_head', 'rel_canonical', 10 ],

			// remove RSD Link.
			[ 'wp_head', 'rsd_link', 10 ],

			// remove Windows Live Writer.
			[ 'wp_head', 'wlwmanifest_link', 10 ],

			// remove wp meta tag generator (deprecated).
			[ 'wp_head', 'wp_generator', 10 ],

			// remove extra feeds links (ex: categories).
			[ 'wp_head', 'feed_links_extra', 3 ],

			// remove feeds (posts and comments).
			[ 'wp_head', 'feed_links', 2 ],

			// remove shortlink.
			[ 'wp_head', 'wp_shortlink_wp_head', 10 ],

			// remove json api links from head.
			[ 'wp_head', 'rest_output_link_wp_head', 10 ],

			// remove oembed links.
			[ 'wp_head', 'wp_oembed_add_discovery_links', 10 ],

			// remove welcome panel from dashboard.
			[ 'welcome_panel', 'wp_welcome_panel', 10 ],

			// remove emoji styles from wp.
			[ 'wp_print_styles', 'print_emoji_styles', 10 ],

			// remove json api link from http header.
			[ 'template_redirect', 'rest_output_link_header', 11 ],
		],
		'add'    => [
			// set smtp email configuration. (disable for local development)
			// [ 'phpmailer_init', 'onyx_smtp_config' ],

			// disable WordPress jQuery on front-end
			[ 'wp_enqueue_scripts', 'onyx_remove_wp_jquery' ],

			// load onyx styles.
			[ 'wp_head', 'onyx_load_styles' ],

			// load onyx javascripts.
			[ 'wp_footer', 'onyx_load_javascripts' ],

			// load live reload on development enviroment.
			[ 'wp_footer', 'onyx_load_livereload' ],

			// reallocate javascripts from header to footer and remove wp jquery.
			[ 'wp_enqueue_scripts', 'onyx_header_footer_scripts' ],

			// ADMIN: customize styles and scripts
			[ 'login_enqueue_scripts', 'onyx_admin_scripts' ],
			[ 'admin_enqueue_scripts', 'onyx_admin_scripts' ],

			// ADMIN: customize admin bar
			[ 'wp_before_admin_bar_render', 'onyx_customize_admin_bar' ],

			// ADMIN: customize panel dashboard
			[ 'wp_dashboard_setup', 'onyx_dashboard_widgets' ],

			// ADMIN: disable comments and trackbacks
			// [ 'admin_menu', 'onyx_disable_comments_trackbacks' ],

			// GUTENBEG: add style and script
			[ 'enqueue_block_editor_assets', 'onyx_gutenberg_js' ],
			[ 'admin_init', 'onyx_gutenberg_style' ],
		],
	],
];

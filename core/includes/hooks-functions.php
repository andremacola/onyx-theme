<?php
/**
 * Onyx custom filter or action functions to alter the WordPress behavior
 * To register a hook, see config/hooks.php
 * Please, do not register any hook here
 *
 * @package Onyx Theme
 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference
 * @see https://codex.wordpress.org/Plugin_API/Action_Reference
 */

use Onyx\Helpers as O;

/**
 * Remove WordPress frontend jquery.
 *
 * @return void
 */
function onyx_remove_wp_jquery() {
	if ( ! is_admin() ) {
		wp_deregister_script( 'jquery' );
		wp_enqueue_script( 'jquery' );
	}
}
// add_action( 'wp_enqueue_scripts', 'onyx_remove_wp_jquery' );

/**
 * Remove WordPress frontend wp-embed javascript
 *
 * @return void
 */
function onyx_remove_wp_embed() {
	if ( ! is_admin() ) {
		wp_deregister_script( 'wp-embed' );
	}
}
// add_action( 'wp_enqueue_scripts', 'onyx_remove_wp_embed' );

/**
 * Reallocate js from header to footer.
 *
 * @return void
 */
function onyx_header_footer_scripts() {
	remove_action( 'wp_head', 'wp_print_scripts' );
	remove_action( 'wp_head', 'wp_print_head_scripts', 9 );
	remove_action( 'wp_head', 'wp_enqueue_scripts', 1 );
	add_action( 'wp_footer', 'wp_print_scripts', 5 );
	add_action( 'wp_footer', 'wp_enqueue_scripts', 5 );
	add_action( 'wp_footer', 'wp_print_head_scripts', 5 );
}
// add_action( 'wp_enqueue_scripts', 'onyx_header_footer_scripts' );

/**
 * Add custom internal and slug classes to body_class.
 *
 * @param array $classes Classes received from body_class filter.
 * @return array
 */
function onyx_body_classes( $classes ) {
	global$post;
	if ( ! is_home() ) {
		$classes[] = 'int';
	}
	if ( isset( $post ) && is_page() ) {
		$classes[] = $post->post_type . '-' . $post->post_name;
	}
	return $classes;
}
add_filter( 'body_class', 'onyx_body_classes' );

/**
 * Remove empty paragraphs.
 *
 * @param array $content Content received from the_content filter.
 * @return mixed
 */
function onyx_remove_empty_p( $content ) {
	if ( ! O::is_amp() ) {
		$content = force_balance_tags( $content );
		$content = preg_replace( '#<p>(\s|&nbsp;)*+(<br\s*/*>)?(\s|&nbsp;)*</p>#i', '', $content );
	}
	return $content;
}
add_filter( 'the_content', 'onyx_remove_empty_p' );

/**
 * Add single templates by categories.
 * single-catslug.php
 *
 * @deprecated since Timber
 * @param string $t Template name received from single_template filter.
 * @return string
 */
function onyx_single_cat_template( $t ) {
	$template_dir = get_template_directory();
	foreach ( get_the_category() as $cat ) :
		if ( file_exists( $template_dir . "/single-cat-{$cat->slug}.php" ) ) {
			return $template_dir . "/single-cat-{$cat->slug}.php";
		}
		if ( $cat->parent ) {
			$cat = get_category( $cat->parent );
			if ( file_exists( $template_dir . "/single-cat-{$cat->slug}.php" ) ) {
				return $template_dir . "/single-cat-{$cat->slug}.php";
			}
		}
	endforeach;
	return $t;
}
// add_filter( 'single_template', 'onyx_single_cat_template' );

/**
 * Action to add Onyx Theme Styles.
 *
 * @return void
 */
function onyx_load_styles() {
	$assets = O::conf( 'assets' )->css;
	foreach ( $assets as $handler => $css ) {
		$src   = O::static_path( $css[0] );
		$home  = ( isset( $css[1] ) ) ? $css[1] : false;
		$deps  = ( isset( $css[2] ) ) ? $css[2] : [];
		$ver   = ( isset( $css[3] ) ) ? $css[3] : ONYX_THEME_VERSION;
		$media = ( isset( $css[4] ) ) ? $css[4] : false;

		if ( ! $home ) :
			wp_enqueue_style( $handler, $src, $deps, $ver, $media );
		elseif ( is_home() ) :
				wp_enqueue_style( $handler, $src, $deps, $ver, $media );
		endif;
	}
}
// add_action( 'wp_enqueue_scripts', 'onyx_load_styles' );

/**
 * Action to add Onyx Theme Javascripts.
 *
 * @return void
 */
function onyx_load_javascripts() {
	$assets = O::conf( 'assets' )->js;
	foreach ( $assets as $handler => $js ) {
		$src       = O::static_path( $js[0] );
		$home      = ( isset( $js[1] ) ) ? $js[1] : false;
		$deps      = ( isset( $js[2] ) ) ? $js[2] : [];
		$ver       = ( isset( $js[3] ) ) ? $js[3] : ONYX_THEME_VERSION;
		$in_footer = ( isset( $js[4] ) ) ? $js[4] : false;

		if ( ! $home ) :
			wp_enqueue_script( $handler, $src, $deps, $ver, $in_footer );
		elseif ( is_home() ) :
				wp_enqueue_script( $handler, $src, $deps, $ver, $in_footer );
		endif;
	}
}
// add_action( 'wp_enqueue_scripts', 'onyx_load_javascripts' );

/**
 * Enqueue all styles and scripts
 *
 * @see config/assets.php
 * @return void
 */
function onyx_enqueue_assets() {
	onyx_header_footer_scripts();
	onyx_load_styles();
	onyx_load_javascripts();
}
add_action( 'wp_enqueue_scripts', 'onyx_enqueue_assets' );

/**
 * Action to configure WordPress send an email via SMTP.
 *
 * @see config/mail.php
 * @param object $phpmailer PHPMailer Object received from phpmailer_init action.
 * @return void
 */
function onyx_smtp_config( $phpmailer ) {
	$mail = O::conf( 'mail' );
	// phpcs:disable
	$phpmailer->isSMTP();
	$phpmailer->From       = $mail->from;
	$phpmailer->FromName   = $mail->name;
	$phpmailer->Host       = $mail->host;
	$phpmailer->Port       = $mail->port;
	$phpmailer->SMTPSecure = $mail->secure;
	$phpmailer->SMTPAuth   = $mail->auth;
	$phpmailer->Username   = $mail->user;
	$phpmailer->Password   = $mail->pass;
	// phpcs:enable
}
// add_action( 'phpmailer_init', 'onyx_smtp_config' );

/**
 * Remove Private or Protected prefix from title.
 *
 * @deprecated
 * @param string $title Received from hooks.
 * @return string
 */
function onyx_remove_private_title( $title ) {
	return '%s';
}
add_filter( 'private_title_format', 'onyx_remove_private_title' );
add_filter( 'protected_title_format', 'onyx_remove_private_title' );


/**
 * Show excerpt by default;
 * Not needed for Gutenberg.
 *
 * @deprecated
 * @param string[] $hidden An array of IDs of meta boxes hidden by default.
 * @param object   $screen Object of the current screen.
 * @return bool
 */
function onyx_show_hidden_excerpt( $hidden, $screen ) {
	if ( 'post' === $screen->base ) {
		foreach ( $hidden as $key => $value ) {
			if ( 'postexcerpt' === $value ) {
				unset( $hidden[ $key ] );
				break;
			}
		}
	}
	return $hidden;
}
apply_filters( 'default_hidden_meta_boxes', 'onyx_show_hidden_excerpt', 10, 2 );

/**
 * Adjust a better wp-caption without <p>
 * and removing the additional 10px.
 *
 * @deprecated
 * @param string $output The caption output. Default empty.
 * @param array  $attr Attributes of the caption shortcode.
 * @param string $content The image element, possibly wrapped in a hyperlink.
 * @return mixed
 */
function onyx_better_img_caption( $output, $attr, $content ) {
	// skip caption if in feed.
	if ( is_feed() ) {
		return $output;
	}

	// default argument settings.
	$defaults = array(
		'id'      => '',
		'align'   => 'alignnone',
		'width'   => '',
		'caption' => '',
	);

	// combine arguments with user input.
	$attr = shortcode_atts( $defaults, $attr );

	// if the width is less than 1, there is no caption, return the image normally.
	if ( 1 > $attr['width'] || empty( $attr['caption'] ) ) {
		return $content;
	}

	// set the caption div attributes.
	$attributes  = ( ! empty( $attr['id'] ) ? ' id="' . esc_attr( $attr['id'] ) . '"' : '' );
	$attributes .= ' class="wp-caption ' . esc_attr( $attr['align'] ) . '"';
	$attributes .= ' style="width: ' . esc_attr( $attr['width'] ) . 'px"';

	// open a caption div.
	$output = '<div' . $attributes . '>';
	// allow shortcodes.
	$output .= do_shortcode( $content );
	// add caption text.
	$output .= '<span class="wp-caption-text">' . $attr['caption'] . '</span>';
	// close the caption div.
	$output .= '</div>';

	// return caption formatted correctly.
	return $output;
}
add_filter( 'img_caption_shortcode', 'onyx_better_img_caption', 10, 3 );

/* ---------------------------------------------------------------
| TIMBER
--------------------------------------------------------------- */

/**
 * Set Timber Global Context
 *
 * @param mixed $context Received from `timber/context` filter
 */
function onyx_set_timber_global_context( $context ) {
		$add_context = O::load( 'contexts', false );
		$context     = array_merge( $context, $add_context );
		return $context;
}
add_filter( 'timber/context', 'onyx_set_timber_global_context' );

/**
 * Set Timber cache Folder
 *
 * @see https://timber.github.io/docs/v2/guides/performance/
 *
 * @param array $options Timber Environment Options
 * @return $options
 */
function onyx_timber_environment_options( $options ) {
	$options['cache']       = O::conf( 'env' )->timber['cache'];
	$options['auto_reload'] = O::conf( 'env' )->timber['auto_reload'];
	$options['autoscape']   = O::conf( 'env' )->timber['autoscape'];

	return $options;
}
add_filter( 'timber/twig/environment/options', 'onyx_timber_environment_options' );


/* ---------------------------------------------------------------
| ACF CUSTOM FILTERS
--------------------------------------------------------------- */

/**
 * Show ACF in admin menu only for developers.
 *
 * @return bool
 */
function onyx_acf_show_admin() {
	return ( O::is_dev() ) ? true : false;
}
add_filter( 'acf/settings/show_admin', 'onyx_acf_show_admin' );

/**
 * Customize html return in the post object field of the blocks.
 *
 * @param string $title object field title
 * @param object $post WP_Post object
 * @param array  $field The field array containing all settings
 * @param int    $post_id The current post ID being edited
 * @return string
 */
function onyx_acf_object_result( $title, $post, $field, $post_id ) {
	$title = "<span class='id ref'>[$post->ID]</span> / <span class='title'>$title</span>";
	return $title;
}
// add_filter( 'acf/fields/post_object/result', 'onyx_acf_object_result', 10, 4 );

/**
 * Customize post query from object field.
 *
 * @param array      $args The query args. See WP_Query for available args.
 * @param array      $field The field array containing all settings.
 * @param int|string $post_id he current post ID being edited.
 * @return array
 */
function onyx_acf_post_object_query( $args, $field, $post_id ) {
	$args['order']   = 'DESC';
	$args['orderby'] = 'ID';

	// buscar post por ID
	$search = ( ! empty( $args['s'] ) ) ? $args['s'] : false;
	if ( $search && is_numeric( $search ) ) {
		$args['post__in'] = array( $search );
		unset( $args['s'] );
	}

	return $args;
}
// add_filter( 'acf/fields/post_object/query', 'onyx_acf_post_object_query', 10, 3 );

/*
|--------------------------------------------------------------------------
| ADMIN CUSTOM FILTERS
|--------------------------------------------------------------------------
*/

/**
 * Customize styles and scripts from admin;
 * Add custom favicon.
 *
 * @return void
 */
function onyx_admin_scripts() {
	$env = O::conf( 'env' );
	wp_enqueue_style( 'onyx-admin-style', $env->dir_uri . '/assets/css/style.admin.css', [], $env->version );
}
add_action( 'admin_enqueue_scripts', 'onyx_admin_scripts' );

/**
 * Customize admin footer text label.
 *
 * @return string
 */
function onyx_change_footer_text_admin() {
	return O::conf( 'env' )->name;
}
add_filter( 'admin_footer_text', 'onyx_change_footer_text_admin' );

/**
 * Customize admin bar.
 *
 * @return void
 */
function onyx_customize_admin_bar() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu( 'wp-logo' );
}
add_action( 'wp_before_admin_bar_render', 'onyx_customize_admin_bar' );

/**
 * Customize admin dashboard widgets.
 *
 * @return void
 */
function onyx_dashboard_widgets() {
	global $wp_meta_boxes;
	remove_action( 'welcome_panel', 'wp_welcome_panel' );
	unset( $wp_meta_boxes['dashboard']['normal']['high']['dashboard_browser_nag'] ); // browse happy
	// unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'] );   // quick draft
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] );       // wordpress.com
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'] );     // WordPress news
}
add_action( 'wp_dashboard_setup', 'onyx_dashboard_widgets' );

/**
 * Disable comments and trackbacks.
 *
 * @return void
 */
function onyx_disable_comments_trackbacks() {
	global $post_types;

	if ( is_array( $post_types ) ) {
		foreach ( $post_types as $post_type ) {
			if ( post_type_supports( $post_type, 'comments' ) ) {
				remove_post_type_support( $post_type, 'comments' );
				remove_post_type_support( $post_type, 'trackbacks' );
			}
		}
	}

	add_action('admin_menu',
		function() {
			global $pagenow;
			remove_menu_page( 'edit-comments.php' );
			if ( 'edit-comments.php' === $pagenow ) {
				wp_safe_redirect( admin_url() );
				exit;
			}
		}
	);

	add_action('wp_before_admin_bar_render',
		function() {
			global $wp_admin_bar;
			$wp_admin_bar->remove_menu( 'comments' );
		}
	);
}
// add_action( 'after_setup_theme', 'onyx_disable_comments_trackbacks' );

/**
 * Filter allowed mime types to upload.
 *
 * @param array $existing_mimes Mime types array to return
 * @return array
 */
function onyx_remove_mime_types( $existing_mimes = [] ) {
	$uploads = O::conf( 'env' )->uploads;

	foreach ( $uploads['unset_types'] as $type ) {
		unset( $existing_mimes[$type] );
	}

	return $existing_mimes;
}
add_filter( 'upload_mimes', 'onyx_remove_mime_types' );

/**
 * Limit upload file size inside admin.
 *
 * @return int
 */
function onyx_upload_limit() {
	$max_size = O::conf( 'env' )->uploads['max_file_size'];
	return $max_size * 1024;
}
add_filter( 'upload_size_limit', 'onyx_upload_limit' );

/**
 * Add nextpage/pagebreak button to mce editor.
 *
 * @deprecated
 * @param array $mce_buttons Tinymce buttons to filter
 * @return int
 */
function onyx_editor_page_break( $mce_buttons ) {
	$pos = array_search( 'wp_more', $mce_buttons, true );
	if ( false === $pos ) {
		$buttons     = array_slice( $mce_buttons, 0, $pos + 1 );
		$buttons[]   = 'wp_page';
		$mce_buttons = array_merge( $buttons, array_slice( $mce_buttons, $pos + 1 ) );
	}
	return $mce_buttons;
}
add_filter( 'mce_buttons', 'onyx_editor_page_break' );

/* ---------------------------------------------------------------
| GUTENBERG
--------------------------------------------------------------- */

/**
 * Add editor style.
 *
 * @return void
 */
function onyx_gutenberg_style() {
	add_editor_style( 'assets/css/style.editor.css' );
}
add_action( 'admin_init', 'onyx_gutenberg_style' );

/**
 * Add editor custom javascript.
 *
 * @return void
 */
function onyx_gutenberg_js() {
	$env = O::conf( 'env' );
	wp_enqueue_script(
		'onyx-gutenberg',
		$env->dir_uri . '/assets/js/admin/onyx.min.js',
		array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ),
		$env->version,
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'onyx_gutenberg_js' );

/* ---------------------------------------------------------------
| QUERIES
--------------------------------------------------------------- */

/**
 * Supress main query on front/home page for performance. Always use custom query.
 *
 * @param string    $request The complete SQL query
 * @param \WP_Query $query The WP_Query instance (passed by reference)
 * @return mixed
 */
function onyx_supress_main_query( $request, $query ) {
	if ( is_home() && $query->is_main_query() && ! $query->is_admin ) {
		return false;
	} else {
		return $request;
	}
}
add_filter( 'posts_request', 'onyx_supress_main_query', 10, 2 );

/* ---------------------------------------------------------------
| DEVELOPMENT
--------------------------------------------------------------- */

/**
 * Action to inject gulp-livereload server for development,
 * only works with `.local` domains.
 *
 * @return void|boolean
 */
function onyx_enqueue_livereload() {
	if ( is_admin() ) {
		return false;
	}

	if ( strpos( $_SERVER['HTTP_HOST'], 'localhost' ) || strpos( $_SERVER['HTTP_HOST'], '.local' ) ) {
			$port = 3010;
			$url  = 'http://localhost' . ":$port/livereload.js";
			wp_enqueue_script( 'live-reload', $url, [], 1, true );
	}

	return false;
}

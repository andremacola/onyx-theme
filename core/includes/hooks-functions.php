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

use \Onyx\Helpers as O;

/**
 * Remove WordPress frontend jquery.
 *
 * Registered at actions->add->wp_enqueue_scripts at config/hooks.php
 *
 * @return void
 */
function onyx_remove_wp_jquery() {
	if ( ! is_admin() ) {
		wp_deregister_script( 'jquery' );
		wp_enqueue_script( 'jquery' );
	}
}

/**
 * Remove WordPress frontend wp-embed javascript
 *
 * Registered at actions->add->wp_enqueue_scripts at config/hooks.php
 *
 * @return void
 */
function onyx_remove_wp_embed() {
	if ( ! is_admin() ) {
		wp_deregister_script( 'wp-embed' );
	}
}

/**
 * Reallocate js from header to footer.
 * Registered at actions->add->wp_enqueue_scripts at config/hooks.php
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

/**
 * Add custom internal and slug classes to body_class.
 * Registered at filters->add->body_class at config/hooks.php
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

/**
 * Remove empty paragraphs.
 * Registered at filters->add->the_content at config/hooks.php
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

/**
 * Add single templates by categories.
 * single-catslug.php
 * Registered at filters->add->single_template at config/hooks.php
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

/**
 * Action to add Onyx Theme Styles.
 * Registered at actions->add->wp_enqueue_scripts config/hooks.php
 *
 * @return void
 */
function onyx_load_styles() {
	$assets = O::conf( 'assets' )->css;
	foreach ( $assets as $handler => $css ) {
		$src   = O::static_path( $css[0] );
		$home  = ( isset( $css[1] ) ) ? $css[1] : false;
		$deps  = ( isset( $css[2] ) ) ? $css[2] : [];
		$ver   = ( isset( $css[3] ) ) ? $css[3] : ONYX_STATIC_VERSION;
		$media = ( isset( $css[4] ) ) ? $css[4] : false;

		if ( ! $home ) :
			wp_enqueue_style( $handler, $src, $deps, $ver, $media );
		else :
			if ( is_home() ) {
				wp_enqueue_style( $handler, $src, $deps, $ver, $media );
			}
		endif;
	}
}

/**
 * Action to add Onyx Theme Javascripts.
 * Registered at actions->add->wp_head config/hooks.php
 *
 * @return void
 */
function onyx_load_javascripts() {
	$assets = O::conf( 'assets' )->js;
	foreach ( $assets as $handler => $js ) {
		$src       = O::static_path( $js[0] );
		$home      = ( isset( $js[1] ) ) ? $js[1] : false;
		$deps      = ( isset( $js[2] ) ) ? $js[2] : [];
		$ver       = ( isset( $js[3] ) ) ? $js[3] : ONYX_STATIC_VERSION;
		$in_footer = ( isset( $js[4] ) ) ? $js[4] : false;

		if ( ! $home ) :
			wp_enqueue_script( $handler, $src, $deps, $ver, $in_footer );
		else :
			if ( is_home() ) {
				wp_enqueue_script( $handler, $src, $deps, $ver, $in_footer );
			}
		endif;
	}
}

/**
 * Enqueue all styles and scripts
 * Registered at actions->add->wp_enqueue_scripts config/hooks.php.
 *
 * @see config/assets.php
 * @return void
 */
function onyx_enqueue_assets() {
	onyx_header_footer_scripts();
	onyx_load_styles();
	onyx_load_javascripts();
}

/**
 * Action to configure WordPress send an email via SMTP.
 * Registered at actions->add->phpmailer_init config/hooks.php.
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

/**
 * Remove Private or Protected prefix from title.
 * Registered at filters->add->private_title_format|protected_title_format at config/hooks.php
 *
 * @deprecated
 * @param string $title Received from hooks.
 * @return string
 */
function onyx_remove_private_title( $title ) {
	return '%s';
}


/**
 * Show excerpt by default;
 * Not needed for Gutenberg.
 * Registered at filters->add->default_hidden_meta_boxes at config/hooks.php
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

/**
 * Adjust a better wp-caption without <p>
 * and removing the additional 10px.
 * Registered at filters->add->img_caption_shortcode at config/hooks.php
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

/*
|--------------------------------------------------------------------------
| TIMBER
|--------------------------------------------------------------------------
*/

/**
 * Set Timber cache Folder
 *
 * Registered at filters->add->timber/cache/location at config/hooks.php
 *
 * @return string
 */
function onyx_timber_cache_folder() {
	return O::conf( 'env' )->timber['cache_dir'];
}


/*
|--------------------------------------------------------------------------
| ACF CUSTOM FILTERS
|--------------------------------------------------------------------------
*/

/**
 * Show ACF in admin menu only for developers.
 * Registered at filters->add->acf/settings/show_admin at config/hooks.php
 *
 * @return bool
 */
function onyx_acf_show_admin() {
	return ( O::is_dev() ) ? true : false;
}

/**
 * Rename ACF in portuguese language for better UI.
 * Registered at filters->add->acf/init at config/hooks.php
 *
 * @return mixed
 */
function onyx_acf_rename() {
	if ( class_exists( 'acf' ) && is_admin() ) {
		add_filter(
			'gettext',
			function( $menu ) {
				$menu = str_replace( 'Campos Personalizados', 'ACF', $menu );
				return $menu;
			}
		);
	}
}

/**
 * Customize html return in the post object field of the blocks.
 * Registered at filters->add->acf/fields/post_object/result at config/hooks.php
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

/**
 * Customize post query from object field.
 * Registered at filters->add->acf/fields/post_object/query at config/hooks.php
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

/*
|--------------------------------------------------------------------------
| ADMIN CUSTOM FILTERS
|--------------------------------------------------------------------------
*/

/**
 * Customize styles and scripts from admin;
 * Add custom favicon.
 * Registered at actions->add->login_enqueue_scripts|admin_enqueue_scripts at config/hooks.php
 *
 * @return void
 */
function onyx_admin_scripts() {
	$env = O::conf( 'env' );
	echo "<link rel='shortcut icon' href='" . esc_attr( $env->dir_uri ) . "/assets/images/icons/favicon-32.png' />";
	wp_enqueue_style( 'onyx-admin-style', $env->dir_uri . '/assets/css/style.admin.css', [], $env->version );
}

/**
 * Customize admin footer text label.
 * Registered at filters->add->admin_footer_text at config/hooks.php
 *
 * @return string
 */
function onyx_change_footer_text_admin() {
	return O::conf( 'env' )->name;
}

/**
 * Customize admin bar.
 * Registered at action->add->wp_before_admin_bar_render at config/hooks.php
 *
 * @return void
 */
function onyx_customize_admin_bar() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu( 'wp-logo' );
}

/**
 * Customize admin dashboard widgets.
 * Registered at action->add->wp_dashboard_setup at config/hooks.php
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

/**
 * Disable comments and trackbacks.
 * Registered at actions->add->admin_menu at config/hooks.php
 *
 * @return void
 */
function onyx_disable_comments_trackbacks() {
	global $pagenow;
	$post_types = get_post_types();

	remove_menu_page( 'edit-comments.php' );

	add_action('wp_before_admin_bar_render',
		function() {
			global $wp_admin_bar;
			$wp_admin_bar->remove_menu( 'comments' );
		}
	);

	if ( 'edit-comments.php' === $pagenow ) {
		wp_safe_redirect( admin_url() );
		exit;
	}

	foreach ( $post_types as $post_type ) {
		if ( post_type_supports( $post_type, 'comments' ) ) {
			remove_post_type_support( $post_type, 'comments' );
			remove_post_type_support( $post_type, 'trackbacks' );
		}
	}
}

/**
 * Filter allowed mime types to upload.
 * Registered at filters->add->upload_mimes at config/hooks.php
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

/**
 * Limit upload file size inside admin.
 * Registered at filters->add->upload_size_limit at config/hooks.php
 *
 * @return int
 */
function onyx_upload_limit() {
	$max_size = O::conf( 'env' )->uploads['max_file_size'];
	return $max_size * 1024;
}

/**
 * Add nextpage/pagebreak button to mce editor.
 * Registered at filters->add->mce_buttons at config/hooks.php
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

/*
|--------------------------------------------------------------------------
| WP REST API
|--------------------------------------------------------------------------
*/

/**
 * Change WP REST API Prefix (Default: wp-json)
 * Registered at filters->add->rest_url_prefix at config/hooks.php
 *
 * @return string
 */
function onyx_rest_url_prefix() {
	return O::conf( 'env' )->rest;
}

/*
|--------------------------------------------------------------------------
| GUTENBERG
|--------------------------------------------------------------------------
*/

/**
 * Add editor style.
 * Registered at actions->add->admin_init at config/hooks.php
 *
 * @return void
 */
function onyx_gutenberg_style() {
	add_editor_style( 'assets/css/style.editor.css' );
}

/**
 * Add editor custom javascript.
 * Registered at actions->add->enqueue_block_editor_assets at config/hooks.php
 *
 * @return void
 */
function onyx_gutenberg_js() {
	$env = O::conf( 'env' );
	wp_enqueue_script(
		'onyx-gutenberg',
		$env->dir_uri . '/src/js/admin/gutenberg.js',
		array( 'wp-blocks', 'wp-dom-ready', 'wp-edit-post' ),
		$env->version,
		true
	);
}

/*
|--------------------------------------------------------------------------
| QUERIES
|--------------------------------------------------------------------------
*/

/**
 * Supress main query on front/home page for performance. Always use custom query.
 * Registered at filters->add->posts_request at config/hooks.php
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

/*
|--------------------------------------------------------------------------
| DEVELOPMENT
|--------------------------------------------------------------------------
*/

/**
 * Action to inject gulp-livereload server for development,
 * only works with `.local` domains.
 * Registered at actions->add->wp_head config/hooks.php
 *
 * @return void|boolean
 */
function onyx_enqueue_livereload() {
	if ( defined( 'ONYX_LIVERELOAD' ) && ONYX_LIVERELOAD ) {
		$port     = 3010;
		$protocol = isset( $_SERVER['HTTPS'] ) ? 'https' : 'http';
		$url      = $protocol . '://' . $_SERVER['HTTP_HOST'] . ":$port/livereload.js";
		wp_enqueue_script( 'live-reload', $url, [], 1, true );
	}
	return false;
}

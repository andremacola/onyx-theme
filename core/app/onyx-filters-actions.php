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

use \Onyx\O;

/**
 * Remove WordPress frontend jquery
 * and reallocate js from header to footer.
 * Registered at actions->add->wp_enqueue_scripts at config/hook.php
 *
 * @return void
 */
function onyx_header_footer_scripts() {
	// remove WordPress frontend jquery.
	if ( ! is_admin() ) {
		wp_deregister_script( 'jquery' );
		wp_enqueue_script( 'jquery' );
		wp_deregister_script( 'wp-embed' );
	}

	// relocate javascripts to footer.
	remove_action( 'wp_head', 'wp_print_scripts' );
	remove_action( 'wp_head', 'wp_print_head_scripts', 9 );
	remove_action( 'wp_head', 'wp_enqueue_scripts', 1 );
	add_action( 'wp_footer', 'wp_print_scripts', 5 );
	add_action( 'wp_footer', 'wp_enqueue_scripts', 5 );
	add_action( 'wp_footer', 'wp_print_head_scripts', 5 );
}

/**
 * Add custom internal and slug classes to body_class.
 * Registered at filters->add->body_class at config/hook.php
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
 * Remove empty paragraphs
 * Registered at filters->add->the_content at config/hook.php
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
 * Registered at filters->add->single_template at config/hook.php
 *
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
 * Registered at actions->add->wp_head config/hook.php
 *
 * @return void
 */
function onyx_load_styles() {
	$assets = O::conf( 'assets' )->css;
	foreach ( $assets as $css ) {
		$css[1] = ( isset( $css[1] ) ) ? $css[1] : false;
		O::css( $css[0], $css[1] );
	}
}

/**
 * Action to add Onyx Theme Javascripts
 * Registered at actions->add->wp_head config/hook.php
 *
 * @return void
 */
function onyx_load_javascripts() {
	$assets = O::conf( 'assets' )->js;
	foreach ( $assets as $js ) {
		$js[1] = ( isset( $js[1] ) ) ? $js[1] : false;
		$js[2] = ( isset( $js[2] ) ) ? $js[2] : '';
		O::js( $js[0], $js[1], $js[2] );
	}
}

/**
 * Action to inject gulp-livereload server for development,
 * only works with `.local` domains.
 * Registered at actions->add->wp_head config/hook.php
 *
 * @return void
 */
function onyx_load_livereload() {
	O::livereload();
}

/**
 * Action to configure WordPress send an email via SMTP.
 * Registered at actions->add->wp_head phpmailer_init/hook.php.
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
 * Registered at filters->add->private_title_format|protected_title_format at config/hook.php
 *
 * @deprecated
 * @param string $title Received from hooks.
 * @return string
 */
function onyx_remove_private_title( $title ) {
	return '%s';
}


/**
 * Show excerpt by default
 * Not needed for Gutenberg.
 * Registered at filters->add->default_hidden_meta_boxes at config/hook.php
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
 * Registered at filters->add->img_caption_shortcode at config/hook.php
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
| ACF CUSTOM FILTERS
|--------------------------------------------------------------------------
*/

/**
 * Show ACF in admin menu only for developers
 * Registered at filters->add->acf/settings/show_admin at config/hook.php
 *
 * @return bool
 */
function onyx_acf_show_admin() {
	return ( O::is_dev() ) ? true : false;
}

/**
 * Rename ACF in portuguese language for better UI
 * Registered at filters->add->acf/init at config/hook.php
 *
 * @return mixed
 */
function onyx_acf_rename() {
	if ( class_exists( 'acf' ) ) {
		add_filter(
			'gettext',
			function( $menu ) {
				$menu = str_ireplace( 'Campos Personalizados', 'Campos', $menu );
				return $menu;
			}
		);
	}
}

/**
 * Customize html return in the post object field of the blocks
 * Registered at filters->add->acf/fields/post_object/result at config/hook.php
 *
 * @param string $title object field title
 * @param object $post WP_Post object
 * @param array  $field The field array containing all settings
 * @param int    $post_id The current post ID being edited
 * @return string
 */
function acf_object_result( $title, $post, $field, $post_id ) {
	$title = "<span class='id ref'>[$post->ID]</span> / <span class='title'>$title</span>";
	return $title;
}

/**
 * Customize post query from object field
 * Registered at filters->add->acf/fields/post_object/query at config/hook.php
 *
 * @param array      $args The query args. See WP_Query for available args.
 * @param array      $field The field array containing all settings.
 * @param int|string $post_id he current post ID being edited.
 * @return array
 */
function acf_post_object_query( $args, $field, $post_id ) {
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

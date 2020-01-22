<?php
/**
 * 
 * Filtros para o funcionamento da interface do Wordpress
 * 
 */

/* adiciona elementos ao WP */
add_theme_support('menus');						// adiciona suporte a menus
add_theme_support('post-thumbnails');			// adiciona suporte a thumbnails
add_theme_support('automatic-feed-links');	// adiciona suporte a feed RSS
add_theme_support('title-tag');					// suporte a tag title
add_theme_support('custom-logo');				// suporte a logotipos personalizados

/* remover elementos desnecessários do WP */
remove_filter('the_excerpt', 'wpautop');											// remover <p> do resumo
remove_action('wp_head', 'rsd_link');												// remover RSD Link
remove_action('wp_head', 'wlwmanifest_link');									// remover Windows Live Writer
remove_action('wp_head', 'wp_generator');											// remover meta tag do wp
remove_action('wp_head', 'feed_links_extra', 3);								// remover feeds extras (ex: categorias)
remove_action('wp_head', 'feed_links', 2);										// remover feeds (posts e comentários)
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);						// remover shortlink
remove_action('wp_print_styles', 'print_emoji_styles');						// remover style emoji do wp
remove_action('wp_head', 'rest_output_link_wp_head', 10);					// remover link json api
remove_action('wp_head', 'wp_oembed_add_discovery_links');					// remover oembed links
remove_action('template_redirect', 'rest_output_link_header', 11, 0);	// remover link json api do header http
add_filter('the_generator', '__return_false');									// remover meta tag do wp do feed
add_filter('use_default_gallery_style', '__return_false');					// desabilitar style da galeria

/* remover jquery automática do WordPress */
add_action('wp_enqueue_scripts', 'onyx_disable_jquery');
function onyx_disable_jquery() {
	if (!is_admin()) {
		wp_deregister_script('jquery');
		wp_register_script('jquery', (""));
		wp_enqueue_script('jquery');
		wp_deregister_script('wp-embed');
	}
}

// reajustar todos os scripts para o footer
add_action('wp_enqueue_scripts', 'onyx_head_scripts');
function onyx_head_scripts() {
	remove_action('wp_head', 'wp_print_scripts');
	remove_action('wp_head', 'wp_print_head_scripts', 9);
	remove_action('wp_head', 'wp_enqueue_scripts', 1);
	add_action('wp_footer', 'wp_print_scripts', 5);
	add_action('wp_footer', 'wp_enqueue_scripts', 5);
	add_action('wp_footer', 'wp_print_head_scripts', 5);
}

/* adicionar classe de interna para o body_class */
/* slug no body_class para páginas */
add_filter('body_class', 'onyx_body_classes');
function onyx_body_classes($classes) {
	global$post;
	if (!is_home()) {
		$classes[] = 'int';
	}
	if (isset($post) && is_page()) {
		$classes[] = $post->post_type . '-' . $post->post_name;
	}
	return $classes;
}

/* remover 'PRIVADO:' do título */
add_filter('private_title_format', 'onyx_remove_private_title');
function onyx_remove_private_title($title) {
	return "%s";
}

/* ativar campo de resumo por padrão */
/* VERIFICAR: Necessidade com Gutenberg */
add_filter('default_hidden_meta_boxes', 'onyx_show_hidden_excerpt', 10, 2);
function onyx_show_hidden_excerpt($hidden, $screen) {
if ('post' == $screen->base) {
	foreach($hidden as $key=>$value) {
		if ('postexcerpt' == $value) {
			unset($hidden[$key]);
			break;
		}
	}
}
	return $hidden;
}

/* customizar SINGLE templates por categoria */
add_filter('single_template', 'onyx_cat_single_template');
function onyx_cat_single_template($t) {
	foreach(get_the_category() as $cat):
		if (file_exists(TEMPLATEPATH . "/single-cat-{$cat->slug}.php")) {
			return TEMPLATEPATH . "/single-cat-{$cat->slug}.php";
		}
		if($cat->parent) {
			$cat = get_category($cat->parent);
			if (file_exists(TEMPLATEPATH . "/single-cat-{$cat->slug}.php")) {
				return TEMPLATEPATH . "/single-cat-{$cat->slug}.php";
			}
		}
	endforeach;
	return $t;
}

/* fix redirect pagina wp-signup em uma network  */
add_action('signup_header', 'onyx_prevent_multisite_signup');
function onyx_prevent_multisite_signup() {
	wp_redirect(site_url());
	die();
}

/* remover parágrafos vazios do conteúdo  */
/* VERIFICAR: Compatibilidade com Gutenberg */
add_filter('the_content', 'onyx_remove_empty_p', 20, 1);
function onyx_remove_empty_p($content){
	if (!onyx_amp()) {
		$content = force_balance_tags($content);
		$content = preg_replace('#<p>(\s|&nbsp;)*+(<br\s*/*>)?(\s|&nbsp;)*</p>#i', '', $content);
	}
	return $content;
}

/* ajustar um melhor wp-caption sem <p> e removendo os 10px adicionais */
/* VERIFICAR: Compatibilidade com Gutenberg */
add_filter('img_caption_shortcode', 'onyx_better_img_caption', 10, 3);
function onyx_better_img_caption($output, $attr, $content) {
	// ignorar caption caso seja no feed
	if (is_feed())
		return $output;
	// configurações dos argumentos padrões
	$defaults = array(
		'id'			=> '',
		'align'		=> 'alignnone',
		'width'		=> '',
		'caption'	=> ''
	);
	// combinar argumentos com o input do usuário
	$attr = shortcode_atts($defaults, $attr);
	// se o width for menor que 1, não existe caption, retornar a imagem normalmente.
	if (1 > $attr['width'] || empty($attr['caption']))
		return $content;
	// setar os atributos da div do caption
	$attributes = (!empty($attr['id']) ? ' id="' . esc_attr($attr['id']) . '"' : '');
	$attributes .= ' class="wp-caption ' . esc_attr($attr['align']) . '"';
	$attributes .= ' style="width: ' . esc_attr($attr['width']) . 'px"';
	// abrir a div do caption
	$output = '<div' . $attributes . '>';
	// Permitir shortcodes
	$output .= do_shortcode($content);
	// adicionar o texto do caption
	$output .= '<span class="wp-caption-text">' . $attr['caption'] . '</span>';
	// fechar a div do caption
	$output .= '</div>';
	// retornar a lagenda (caption) formatado corretamente
	return $output;
}

 ?>

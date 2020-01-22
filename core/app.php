<?php
/**
 * 
 * Funções únicas/específicas do tema/projeto
 * 
 */

/* ================================
CARREGAMENTO NO HEADER
================================ */

add_action('wp_head', 'onyx_header_elements');
function onyx_header_elements() {

	global $app;

	/* -----------------------------------------
	ALTERAR ALGUMAS VARIÁVEIS DO TEMA
	----------------------------------------- */

	if (is_home())		$app->home		= true;
	if (is_single())	$app->single	= true;

	/* -----------------------------------------
	CARREGAMENTO DE CSS
	----------------------------------------- */

	/* CSS Libraries */
	// onyx_css('$path');

	/* CSS URL Libraries */
	// onyx_css_url('$url');

	/* CSS Personalizado */
	onyx_css('main.css?v='.$app->v);
	// onyx_css('int.css?v='.$app->v, false);

	/* -----------------------------------------
	JAVASCRIPT
	----------------------------------------- */

	/* google services */
	// onyx_js_url('https://www.googletagservices.com/tag/js/gpt.js', true, 'async');
	// onyx_js_url('https://www.google-analytics.com/analytics.js', true, 'async');
	// onyx_analytics('UA-XXXXXX-X');
}

/* ================================
CARREGAMENTO NO FOOTER
================================ */

add_action('wp_footer', 'onyx_footer_elements', 4);
function onyx_footer_elements() {

	global $app;

	/* -----------------------------------------
	CARREGAMENTO DE  JAVA SCRIPTS
	----------------------------------------- */

	/* Javascript Libraries */
	if (ONYX_LOCAL_DEV) {
		onyx_js_src('vendor/jquery.min.js');
		// onyx_js_src('vendor/jquery.owl.carousel.min.js');
		// onyx_js_src('vendor/jquery.fancybox.min.js', false);
		// onyx_js_src('vendor/jquery.mask.min.js', false);
		// onyx_js_src('dfp.js?v='.$app->v);
		onyx_js_src('app.js?v='.$app->v);
		onyx_js_src('app-int.js?v='.$app->v, false);
	} else {
		onyx_js('app.min.js?v='.$app->v);
		onyx_js('app-int.min.js?v='.$app->v, false);
	}

	/* Google Analytics */
	// onyx_ganalytics('UA-XXXXXX-X');

	/* dev: livereload/browsersync */
	if (isset($_GET['lr'])) {
		onyx_js_livereload($_GET['lr'], 'localhost');
	}
}

/* ================================
FILTROS / ACTIONS
================================ */

/* filtro de inicialização */
add_action('init', function() {
	if (!is_admin()) {
		// add_filter('show_admin_bar', '__return_false');					// desabilitar admin bar
		remove_action('wp_head', 'print_emoji_detection_script', 7);	// remover script emoji do wp
		remove_action('wp_head', 'wp_resource_hints', 2);					// remover dns prefetch s.w.org
		remove_action('wp_head', 'rel_canonical');							// remover url canonica
	}
});

/* remover css gutenberg block library  */
// add_action('wp_enqueue_scripts', function() {
// 	wp_dequeue_style('wp-block-library');
// }, 100);

/* desabilitar templates desnecessários */
add_action('template_redirect', function() {
	if (is_category() || is_tag() || is_archive() || is_date() || is_search() || is_attachment() || is_author() || is_404() || is_single()) {
		wp_redirect(home_url());
	}
});

/* remover páginas normais da busca */
// add_filter('pre_get_posts', function() {
// 	if ($query->is_search) {
// 		$query->set('post_type', 'post');
// 	}
// 		return $query;
// });

/* ================================
FUNÇÕES
================================ */
?>

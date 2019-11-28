<?php
/**
 * 
 * Arquivo de Configuração do APP e Plugins
 * 
 */

/* ================================
Constants
================================ */

define('ONYX_LOCAL_DEV', true);	// constante para determinar ambiente
define('ONYX_STATIC', false);		// carregar assets a partir de um static cdn

/* ================================
Variáveis obrigatórias
================================ */

$onyx_ver = 1;

$onyx_usr = (object) array(
	'devs'	=> array(
		'andremacola@gmail.com',
		'igorfpessoa@gmail.com',
		'rosie.baltazar.carvalho@gmail.com'
	),
	'user' => wp_get_current_user()->user_email
);

$app = (object) array(
	'home'			=> false,
	'single'			=> false,
	'key'				=> 'token-de-acesso',
	'dir'				=> str_replace('/views', '', get_template_directory_uri()),
	'path'			=> str_replace('/views', '', get_template_directory()),
	'description'	=> get_bloginfo('description'),
	'name'			=> get_bloginfo('name'),
	'url'				=> get_home_url(),
	'theme'			=> get_option('onyxtheme'),
	'v'				=> (in_array($onyx_usr->user, $onyx_usr->devs)) ? rand(0,99999) : $onyx_ver
);

/* ================================
@ Gutenberg
================================ */

add_action('after_setup_theme', function() {
	add_theme_support('wp-block-styles');
	add_theme_support('align-wide');
	add_theme_support('responsive-embeds');
});

/* ================================
Configuração de EMAIL
================================ */

add_action('phpmailer_init', 'onyx_smtp_config');
function onyx_smtp_config($phpmailer) {
	$phpmailer->isSMTP();
	$phpmailer->From			= "email@domain.tld";
	$phpmailer->FromName		= "Client Name";
	// $phpmailer->Sender	= $phpmailer->From;
	$phpmailer->Host			= 'smtp.gmail.com';
	$phpmailer->Port			= 465;
	$phpmailer->SMTPSecure	= "ssl";
	$phpmailer->SMTPAuth		= true;
	$phpmailer->Username		= 'username';
	$phpmailer->Password		= 'password';
}

/* ================================
Advanced Custom Fields
================================ */

/* mostrar acf somente ao desenvolvedor */
add_filter('acf/settings/show_admin', 'acf_show_admin');
function acf_show_admin($show) {
	global $onyx_usr;
	if (in_array($onyx_usr->user, $onyx_usr->devs)) {
		return true;
	} else {
		return false;
	}
}

/* renomear "Campos Personalizados" do wp-admin */
add_filter('after_setup_theme', 'onyx_plugins_loaded');
function onyx_plugins_loaded() {
	// renomear "Campos Personalizados" do wp-admin
	if(class_exists('acf')) {
		add_filter('gettext', function($menu){
			$menu = str_ireplace( 'Campos Personalizados', 'Campos', $menu );
			return $menu;
		});
	}
}

/* customizar retorno html do campo post object dos blocos/posts */
add_filter('acf/fields/post_object/result', 'acf_object_result', 10, 4);
function acf_object_result($title, $post, $field, $post_id) {
	$title = "<span class='id ref'>[$post->ID]</span> / <span class='title'>$title</span>";
	return $title;
}

/* customizar query do campo post object dos blocos/posts */
add_filter('acf/fields/post_object/query', 'acf_post_object_query', 10, 3);
function acf_post_object_query($args, $field, $post) {
	$args['order']		= 'DESC';
	$args['orderby']	= 'ID';

	// buscar post por ID
	$search = !empty($args['s']) ? $args['s'] : false;
	if($search && is_numeric($search)) {
		$args['post__in'] = array($search);
		unset($args['s']);
	}

	return $args;
}

/* adicionar página de opções do layout/tema */
// if( function_exists('acf_add_options_page') ) {
// 	$options = acf_add_options_page(array(
// 		'page_title'	=> 'Options',
// 		'menu_slug'		=> 'acf-options-site',
// 		'capability'	=> 'manage_options',
// 		'icon_url' 		=> 'dashicons-schedule',
// 		'redirect'		=> false
// 	));
// }

/* ================================
@ The SEO FrameWork
================================ */

/* filtros diversos  */
// add_filter('the_seo_framework_seobox_output', '__return_false');
// add_filter('the_seo_framework_indicator', '__return_false');

/* ================================
@ ADMIN COLUMNS PRO
================================ */

/* desativar smart filtering */
add_filter('acp/search/is_active', '__return_false');
/* remover notices e warnings */
add_filter( 'ac/suppress_site_wide_notices', '__return_true' );

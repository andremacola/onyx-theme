<?php 
/*

	@ Criação/Alteração dos Post Types Padrões do Wordpress

*/

/*==========================================================
Importação de POST TYPES
===========================================================*/

// require_once('cpt.php');

/*==========================================================
Filtros e Actions gerais
===========================================================*/

/* POST: remover elementos */
add_action('init', 'onyx_remove_post_support');
function onyx_remove_post_support() {
	remove_post_type_support('post', 'comments');
	remove_post_type_support('post', 'excerpt');
	register_taxonomy('post_tag', array());
}

/* PAGE: remover elementos */
add_action('init', 'onyx_remove_page_support');
function onyx_remove_page_support() {
	// remove_post_type_support('page', 'editor');
	// remove_post_type_support('page', 'thumbnail');
	// remove_post_type_support('page', 'page-attributes');
}

/* remover gutenberg de páginas */
// add_filter('use_block_editor_for_post_type', 'onyx_block_editor_filter', 10, 2);
// function onyx_block_editor_filter($use_block_editor, $post_type) {
// 	if ('page' === $post_type) {
// 		return false;
// 	}
// 	return $use_block_editor;
// }

/*==========================================================
POST: Customizar
===========================================================*/

// add_action( 'admin_menu', 'post_type_label' );
// add_action( 'init', 'post_type_object' );
// function post_type_label() {
// 	global $menu;
// 	global $submenu;

// 	// habilitar próxima linha para visualizar a ordem dos menus
// 	// echo '<pre>'.print_r($menu,true).'</pre>';
// 	// echo '<pre>'.print_r($submenu,true).'</pre>';

// 	if(current_user_can('edit_posts')):
// 		// renomear menu e submenus
// 		$menu[5][0]							= 'Posts';
// 		$submenu['edit.php'][5][0]		= 'Posts';
// 		$submenu['edit.php'][10][0]	= 'Adicionar novo post';

// 		// alternar ordem dos submenus
// 		$arr						= array();
// 		$arr[]					= $submenu['edit.php'][17];
// 		$arr[]					= $submenu['edit.php'][18];
// 		$arr[]					= $submenu['edit.php'][19];
// 		$arr[]					= $submenu['edit.php'][16];
// 		$submenu['edit.php']	= $arr;
// 	endif;
// }
// function post_type_object() {
// 	global $wp_post_types;
// 	$labels								= $wp_post_types['post']->labels;
// 	$labels->name						= 'Posts';
// 	$labels->singular_name			= 'Post';
// 	$labels->add_new					= 'Adicionar Post';
// 	$labels->add_new_item 			= 'Adicionar Post';
// 	$labels->edit_item				= 'Adicionar Post';
// 	$labels->new_item					= 'Posts';
// 	$labels->view_item				= 'Ver Post';
// 	$labels->search_items			= 'Buscar Post';
// 	$labels->not_found				= 'Nenhum post encontrado';
// 	$labels->not_found_in_trash	= 'Nenhum post encontrado na lixeira';
// 	$labels->all_items				= 'Todos os Posts';
// 	$labels->menu_name				= 'Posts';
// 	$labels->name_admin_bar			= 'Posts';

// 	remove_post_type_support( 'post', 'editor' );
// 	remove_post_type_support( 'post', 'author' );
// 	remove_post_type_support( 'post', 'thumbnail' );
// 	remove_post_type_support( 'post', 'excerpt' );
// 	remove_post_type_support( 'post', 'trackbacks' );
// 	remove_post_type_support( 'post', 'custom-fields' );
// 	remove_post_type_support( 'post', 'comments' );
// 	remove_post_type_support( 'post', 'revisions' );
// 	remove_post_type_support( 'post', 'page-attributes' );
// 	remove_post_type_support( 'post', 'post-formats' );
// }

 ?>
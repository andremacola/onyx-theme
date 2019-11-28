<?php 
/*

	@ POST TYPE: TYPE

*/

// add_action('init', 'register_cpt_onyxtype');
// function register_cpt_onyxtype() {
// 	$name		= 'Onyx';
// 	$namep	= 'Onyxs';
// 	$name_l	= strtolower($name);
// 	$namep_l	= strtolower($namep);

// 	$labels = array( 
// 		'name'					=> $namep,
// 		'singular_name'		=> $name,
// 		'all_items'				=> "Todos os $namep_l",
// 		'add_new'				=> "Adicionar novo",
// 		'add_new_item'			=> "Adicionar novo $name_l",
// 		'edit_item'				=> "Editar $name_l",
// 		'new_item'				=> "Adicionar $name_l",
// 		'view_item'				=> "Ver $name_l",
// 		'search_items'			=> "Buscar $namep_l",
// 		'not_found'				=> "Nenhum $name_l encontrado",
// 		'not_found_in_trash'	=> "Nenhum $name_l encontrado na lixeira",
// 		'parent_item_colon'	=> "$name pai:",
// 		'menu_name'				=> $namep
// 	);

// 	$rewrite = array(
// 		'slug'			=> $namep_l,
// 		'with_front'	=> true,
// 		'pages'			=> true,
// 		'feeds'			=> false
// 	);

// 	$cap = 'Onyx';
// 	$caps = 'Onyxs';

// 	$capabilities = array(
// 		'edit_post'					=> "edit_$cap",
// 		'read_post'					=> "read_$cap",
// 		'delete_post'				=> "delete_$cap",
// 		'edit_posts'				=> "edit_$caps",
// 		'edit_others_posts'		=> "edit_others_$caps",
// 		'publish_posts'			=> "publish_$caps",
// 		'read_private_posts'		=> "read_private_$caps",
// 		'create_posts'				=> "create_$caps",
// 		'read'						=> "read_$caps",
// 		'delete_posts'				=> "delete_$caps",
// 		'delete_private_posts'	=> "delete_private_$caps",
// 		'delete_published_posts'=> "delete_published_$caps",
// 		'delete_others_posts'	=> "delete_others_$caps",
// 		'edit_private_posts'		=> "edit_private_$caps",
// 		'edit_published_posts'	=> "edit_published_$caps"
// 	);

// 	$args = array( 
// 		'labels'					=> $labels,
// 		'hierarchical'			=> false,
// 		'description'			=> "Inclusão de novos $namep_l",
// 		'supports'				=> array('title', 'excerpt', 'thumbnail'),
// 		'public'					=> true,
// 		'show_ui'				=> true,
// 		'show_in_menu'			=> true,
// 		'menu_position'		=> 20,
// 		'menu_icon'				=> 'dashicons-external',
// 		'show_in_nav_menus'	=> true,
// 		'publicly_queryable'	=> true,
// 		'exclude_from_search'=> false,
// 		'has_archive'			=> true,
// 		'query_var'				=> true,
// 		'can_export'			=> true,
// 		'rewrite'				=> $rewrite,
// 		'capability_type'		=> 'post',
// 		'capabilities'			=> $capabilities,
// 		'map_meta_cap'			=> true
// 	);

// 	register_post_type($namep_l, $args);
// }

// add_filter('manage_onyxtype_columns', 'remove_onyxtype_columns');
// function remove_posts_columns($columns) {
// 	unset(
// 		$columns['author'],
// 		$columns['comments'],
// 		$columns['date']
// 	);
// 	return $columns;
// }

?>

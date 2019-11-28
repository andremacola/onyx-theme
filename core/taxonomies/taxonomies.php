<?php 
/*

    @ Criação de Taxonomies do Wordpress
    
*/

/*==========================================================
Categoria para post type: Modelo
===========================================================*/

// add_action( 'init', 'register_taxonomy_onyxtax' );
// function register_taxonomy_onyxtax() {
// 	$name		= 'Categoria';
// 	$namep	= 'Categorias';
// 	$name_l	= strtolower($name);
// 	$namep_l	= strtolower($namep);

// 	$labels = array( 
// 		'name'								=> $namep,
// 		'singular_name'					=> $name,
// 		'search_items'						=> "Buscar $namep_l",
// 		'popular_items'					=> "$namep_l Populares",
// 		'all_items'							=> "Todas as $namep_l",
// 		'parent_item'						=> "$name pai",
// 		'parent_item_colon'				=> "$name pai:",
// 		'edit_item'							=> "Editar $name_l",
// 		'update_item'						=> "Atualizar $name_l",
// 		'add_new_item'						=> "Adicionar nova",
// 		'new_item_name'					=> "Nova $name_l",
// 		'separate_items_with_commas'	=> "Separe as $namep_l com vírgulas",
// 		'add_or_remove_items'			=> "Adicionar ou remover $namep_l",
// 		'choose_from_most_used'			=> "Escolher entre $namep_l mais utilizadas",
// 		'menu_name'							=> $namep
// 	);

// 	$args = array( 
// 		'labels'					=> $labels,
// 		'public'					=> true,
// 		'show_admin_column'	=> true,
// 		'show_in_nav_menus'	=> true,
// 		'show_ui'				=> true,
// 		'show_tagcloud'		=> true,
// 		'hierarchical'			=> true,
// 		'show_in_quick_edit'	=> true,
// 		'meta_box_cb'			=> true,
// 		'rewrite'				=> false,
// 		'query_var'				=> true
// 	);

// 	register_taxonomy($namep_l, array('onyxtype'), $args);
// }

/* adicionar taxonomy na coluna de visualização */

// add_filter( "manage_post_type_modelo_posts_columns", "filter_post_type_modelo_columns" );
// function filter_post_type_modelo_columns($defaults) {
// 	$defaults['onyxtax']	= 'Categorias';
// 	$defaults['author']		= 'Autor';
// 	return $defaults;
// }

// add_action('manage_post_type_modelo_posts_custom_column', 'filter_post_type_modelo_tax_column', 10, 2);
// function filter_post_type_modelo_tax_column($column_name, $post_id) {
// 	$taxonomy	= $column_name;
// 	$post_type	= get_post_type($post_id);
// 	$terms		= get_the_terms($post_id, $taxonomy);

// 	if ( !empty($terms) ) {
// 		foreach ( $terms as $term ):
// 			$post_terms[] = "<a href='edit.php?post_type={$post_type}&{$taxonomy}={$term->slug}'> " . esc_html(sanitize_term_field('name', $term->name, $term->term_id, $taxonomy, 'edit')) . "</a>";
// 			echo join( ', ', $post_terms );
// 		endforeach;
// 	} else {
// 		echo '<i>Sem categoria.</i>';
// 	}
// }

// add_action( 'restrict_manage_posts', 'filter_post_type_modelo_tax' );
// function filter_post_type_modelo_tax() {
// 	global $typenow;
// 	$taxonomies = array('onyxtax');
// 	if( $typenow == 'post_type_modelo' ) {
// 		foreach ($taxonomies as $tax_slug):

// 			$tax_obj		= get_taxonomy($tax_slug);
// 			$onyxtax 	= $tax_obj->labels->name;
// 			$terms 		= get_terms($tax_slug);

// 			if(count($terms) > 0) {
// 				echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
// 				echo "<option value=''>Todas as $onyxtax</option>";
// 				foreach ($terms as $term):
// 					echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>'; 
// 				endforeach;
// 				echo "</select>";
// 			}

// 		endforeach;
// 	}
// }

 ?>

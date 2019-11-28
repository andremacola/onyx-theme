<?php
/**
 * 
 * Queries para utilização com a classe $wpdb ou WP_Query do Wordpress
 * 
 */

$cache_posts_ids = array();

$query_args = array(
	'post_type' 			=> 'post',
	'post_status' 			=> 'publish',
	'suppress_filters' 	=> true,
	'no_found_rows' 		=> true,
	'posts_per_page' 		=> 1,
	'category_name' 		=> 'cat_name',
	'tax_query' 			=> array(
		array(
			'taxonomy'	=> 'tax_name',
			'field'		=> 'slug',
			'terms'		=> 'tax_term'
		)
	)
);

/* ================================
FILTROS / ACTIONS
================================ */

/* limitar main query para evitar slow query na página principal */
add_filter('posts_request', 'onyx_supress_main_query', 10, 2);
function onyx_supress_main_query( $request, $query ){
	if(is_home() && $query->is_main_query() && ! $query->is_admin)
		return false;
	else
		return $request;
}

/* fix para paginação em singular em post types personalizados  */
// add_action('template_redirect', 'onyx_fix_singular_pagination', 0);
// function onyx_fix_singular_pagination() {
// 	if(is_singular('post-type')) {
// 		global $wp_query;
// 		$page = (int)$wp_query->get('page');
// 		if($page > 1) {
// 			$query->set('page', 1);
// 			$query->set('paged', $page);
// 		}
// 		remove_action('template_redirect', 'redirect_canonical');
// 	}
// }

?>

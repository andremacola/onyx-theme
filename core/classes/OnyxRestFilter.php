<?php
/**
 * Plugin Name: WP REST API filter parameter
 * Description: This plugin adds a "filter" query parameter to API post collections to filter returned results based on public WP_Query parameters, adding back the "filter" parameter that was removed from the API when it was merged into WordPress core.
 * Author: WP REST API Team
 * Author URI: http://v2.wp-api.org
 * Version: 0.1
 * License: GPL2+
 * https://wordpress.org/support/topic/wordpress-4-7-custom-taxonomy-filter-stopped-working/
 * https://jeremy.hu/update-rest-api-post-embeds/
 * https://github.com/WP-API/WP-API/issues/2949
 * https://github.com/wp-api/rest-filter
 **/

class OnyxRestFilter {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action('rest_api_init', array($this, 'rest_api_filter_add_filters'));
		add_action('query_vars', array($this, 'add_query_vars_filter'));
	}

	/**
	 * Add the necessary filter to each post type
	 */
	private function rest_api_filter_add_filters() {
		foreach (get_post_types(array('show_in_rest' => true), 'objects') as $post_type) {
			add_filter('rest_' . $post_type->name . '_query', array($this, 'rest_api_filter_add_filter_param'), 10, 2);
		}
	}

	/**
	 * Add the filter parameter
	 *
	 * @param  array           $args    The query arguments.
	 * @param  WP_REST_Request $request Full details about the request.
	 * @return array $args.
	 */
	private function rest_api_filter_add_filter_param($args, $request) {
		if (empty($request['filter']) || !is_array($request['filter'])) {
			return $args;
		}

		$filter = $request['filter'];

		if (isset($filter['posts_per_page']) && ((int) $filter['posts_per_page'] >= 1 && (int) $filter['posts_per_page'] <= 100)) {
			$args['posts_per_page'] = $filter['posts_per_page'];
		}

		global $wp;
		$vars = apply_filters('query_vars', $wp->public_query_vars);

		foreach ($vars as $var) {
			if (isset($filter[$var])) {
				$args[$var] = $filter[$var];
			}
		}
		return $args;
	}

	/**
	 * Get Posts By Meta Field Is Not Available By Default
	 * (https://1fix.io/blog/2015/07/20/query-vars-wp-api/)
	 */
	private function add_query_vars_filter($vars) {
		$vars = array_merge($vars, array('meta_key', 'meta_value', 'meta_compare', 'meta_query', 'tax_query'));
		return $vars;
	}
}

?>

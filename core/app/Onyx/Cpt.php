<?php
/**
 * Onyx Custom Post Types
 *
 * This class was inspired by https://github.com/jjgrainger/PostTypes/
 * Thanks to Joe Grainger https://github.com/jjgrainger
 *
 * @package Onyx Theme
 */

namespace Onyx;

class Cpt {

	/**
	 * The key for post type
	 *
	 * @var string
	 */
	public $key;

	/**
	 * The names params to create the keys passed to the post type
	 * [name, singular, plural, slug]
	 *
	 * @var array
	 */
	public $names;

	/**
	 * The name (singular) for post type
	 *
	 * @var string
	 */
	public $name;

	/**
	 * The plural name for post type
	 *
	 * @var string
	 */
	public $plural;

	/**
	 * The slug for post type
	 *
	 * @var string
	 */
	public $slug;

	/**
	 * Arguments for post type
	 *
	 * @var array
	 */
	public $arguments;

	/**
	 * Labels for post type
	 *
	 * @var array
	 */
	public $labels;

	/**
	 * Custom capability for post type
	 *
	 * @var string
	 */
	public $capability;

	/**
	 * Custom capabilities for post type
	 *
	 * @var array
	 */
	public $capabilities;

	/**
	 * Icon for post type
	 *
	 * @var string
	 */
	public $icon;

	/**
	 * The column manager instance class for post type
	 *
	 * @var mixed
	 */
	public $columns;

	/**
	 * Filters for the post type
	 *
	 * @var array
	 */
	public $filters = [];

	/**
	 * Constructor
	 *
	 * @param string|array $names A string for the name, or an array of names [required]
	 * @param array        $arguments Arguments to register a post type with register_post_type [optional]
	 * @param array        $labels Custom labels to create the post type [optional]
	 * @return void
	 */
	public function __construct( $names = '', $arguments = [], $labels = [] ) {
		$this->names( $names );
		$this->options( $arguments );
		$this->labels( $labels );
	}

	/**
	 * Set the names and key for the post type
	 *
	 * @param string|array $names A string for the name, or an array of names [required]
	 * @return void
	 */
	public function names( $names ) {
		// if the name is a string, then assign the name in array.
		$this->validate_name_params( $names );

		// set name (singular).
		$name       = $this->get_name();
		$this->name = ( substr( $name, -1 ) === 's' ) ? substr( $name, 0, -1 ) : $name;

		// set plural name.
		$plural       = ( isset( $this->names['plural'] ) ) ? $this->names['plural'] : $name;
		$this->plural = ( substr( $plural, -1 ) !== 's' ) ? "{$plural}s" : $plural;

		// set slug.
		$slug       = ( isset( $this->names['slug'] ) ) ? $this->names['slug'] : $this->plural;
		$this->slug = sanitize_title( $slug );

		// set key.
		$this->key = ( isset( $this->names['key'] ) ) ? $this->names['key'] : sanitize_title( $this->name );
	}

	/**
	 * Set the options for post type
	 *
	 * @param array $arguments An array of arguments for post type
	 * @return void
	 */
	public function options( $arguments ) {
		$this->arguments = $arguments;
	}

	/**
	 * Set or create custom capability for post type
	 *
	 * @param string $capability
	 * @return void
	 */
	public function capability( $capability ) {
		$this->capability = $capability;
	}

	/**
	 * Set custom capabilities for post type
	 *
	 * @param array|bool $capabilities
	 * @return void
	 */
	public function capabilities( $capabilities = false ) {
		if ( is_array( $capabilities ) && ! empty( $capabilities ) ) {
			$this->capabilities = $capabilities;
		} elseif ( is_string( $capabilities ) && 'auto' === $capabilities ) {
			$singular = sanitize_title( $this->name );
			$plural   = sanitize_title( $this->plural );

			$this->capabilities = [
				'edit_post'              => "edit_{$singular}",
				'read_post'              => "read_{$singular}",
				'delete_post'            => "delete_{$singular}",
				'edit_posts'             => "edit_{$plural}",
				'edit_others_posts'      => "edit_others_{$plural}",
				'publish_posts'          => "publish_{$plural}",
				'read_private_posts'     => "read_private_{$plural}",
				'create_posts'           => "create_{$plural}",
				'read'                   => "read_{$plural}",
				'delete_posts'           => "delete_{$plural}",
				'delete_private_posts'   => "delete_private_{$plural}",
				'delete_published_posts' => "delete_published_{$plural}",
				'delete_others_posts'    => "delete_others_{$plural}",
				'edit_private_posts'     => "edit_private_{$plural}",
				'edit_published_posts'   => "edit_published_{$plural}",
			];
		}
	}

	/**
	 * Set the labels for post type
	 *
	 * @param array $labels An array of labels for post type
	 * @return void
	 */
	public function labels( $labels ) {
		$this->labels = $labels;
	}

	/**
	 * Set the icon for the post type
	 *
	 * @link https://developer.wordpress.org/resource/dashicons/
	 * @param string $icon A dashicon class for the menu icon
	 * @return void
	 */
	public function icon( $icon ) {
		$this->icon = $icon;
	}

	/**
	 * Get the formated post type name
	 *
	 * @return string
	 */
	private function get_name() {
		$name  = (isset( $this->names['name'] )) ? $this->names['name'] : $this->names['key'];
		$value = str_replace( [ '-', '_' ], ' ', $name );
		$value = strtolower( $value );
		return ucwords( $value );
	}

	/**
	 * Validate $names params passed from __construct
	 *
	 * @param string|array $names A string for the name, or an array of names [required]
	 * @throws \Exception The name(s) param is required.
	 * @return void
	 */
	private function validate_name_params( $names ) {
		if ( is_string( $names ) ) {
			$this->names['name'] = $names;
		} elseif ( is_array( $names ) ) {
			$this->names = $names;
		} else {
			throw new \Exception( 'Please, provide a string or array as param on constructor', 1 );
		}
	}

	/**
	 * Create the labels for post type
	 *
	 * @return array
	 */
	private function create_labels() {
		// default labels.
		$plural_lower = strtolower( $this->plural );
		$labels       = [
			'name'               => $this->plural,
			'singular_name'      => $this->name,
			'menu_name'          => $this->plural,
			'all_items'          => $this->plural,
			'add_new'            => __( 'Add New', 'onyx-theme' ),
			'add_new_item'       => sprintf( __( 'Add New %s', 'onyx-theme' ), $this->name ),
			'edit_item'          => sprintf( __( 'Edit %s', 'onyx-theme' ), $this->name ),
			'new_item'           => sprintf( __( 'New %s', 'onyx-theme' ), $this->name ),
			'view_item'          => sprintf( __( 'View %s', 'onyx-theme' ), $this->name ),
			'search_items'       => sprintf( __( 'Search %s', 'onyx-theme' ), $this->plural ),
			'not_found'          => sprintf( __( 'No %s found', 'onyx-theme' ), $plural_lower ),
			'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'onyx-theme' ), $plural_lower ),
			'parent_item_colon'  => sprintf( __( 'Parent %s:', 'onyx-theme' ), $this->name ),
		];

		// replace defaults with custom labels passed and return
		$this->labels = ( ! empty( $this->labels )) ? $this->labels : [];
		return array_replace_recursive( $labels, $this->labels );
	}

	/**
	 * Create arguments to register post type
	 *
	 * @return array Arguments to pass to register_cpt
	 */
	private function create_cpt_arguments() {
		// default arguments.
		$arguments = [
			'public'  => true,
			'rewrite' => [
				'slug' => $this->slug,
			],
		];

		// replace defaults with the options passed.
		if ( $this->arguments ) {
			$arguments = array_replace_recursive( $arguments, $this->arguments );
		}

		// set capability.
		if ( ! isset( $arguments['capability_type'] ) && ! empty( $this->capability ) ) {
			$arguments['capability_type'] = $this->capability;
		}

		// set custom capabilities.
		if ( isset( $arguments['custom_caps'] ) && $arguments['custom_caps'] ) {
			$this->capabilities( 'auto' );
		}

		if ( ! isset( $arguments['capabilities'] ) && ! empty( $this->capabilities ) ) {
			$arguments['capabilities'] = $this->capabilities;
			$arguments['map_meta_cap'] = ( isset( $this->arguments['map_meta_cap'] ) ) ? $this->arguments['map_meta_cap'] : true;
		}

		// create and set labels.
		if ( ! isset( $arguments['labels'] ) ) {
			$arguments['labels'] = $this->create_labels();
		}

		// set the menu icon.
		if ( ! isset( $arguments['menu_icon'] ) && isset( $this->icon ) ) {
			$arguments['menu_icon'] = $this->icon;
		}

		return $arguments;
	}

	/**
	 * Add a custom CPT Action to WordPress
	 *
	 * @param string   $tag The name of the filter to hook [required]
	 * @param callable $callable The callback to be run when the filter is applied [required]
	 * @param int      $priority Specify the order in which the functions are executed [optional]
	 * @param int      $args The number of arguments the function accepts [optional]
	 * @return bool
	 */
	public function add_action( $tag, $callable, $priority = 10, $args = 1 ) {
		return add_action( $tag, [ $this, $callable ], $priority, $args );
	}

	/**
	 * Add a custom CPT Filter to WordPress
	 *
	 * @param string   $tag The name of the filter to hook [required]
	 * @param callable $callable The callback to be run when the filter is applied [required]
	 * @param int      $priority Specify the order in which the functions are executed [optional]
	 * @param int      $args The number of arguments the function accepts [optional]
	 * @return bool
	 */
	public function add_filter( $tag, $callable, $priority = 10, $args = 1 ) {
		return add_filter( $tag, [ $this, $callable ], $priority, $args );
	}

	/**
	 * Register the custom post type
	 *
	 * @return WP_Post_Type|WP_Error
	 */
	public function register_cpt() {
		$arguments = $this->create_cpt_arguments();

		if ( ! post_type_exists( $this->key ) ) {
			return register_post_type( $this->key, $arguments );
		}
	}

	/**
	 * Hook post type to WordPress
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'init', [ $this, 'register_cpt' ] );
		add_action( 'restrict_manage_posts', [ $this, 'manage_filters' ] );

		if ( isset( $this->columns ) ) {
			// manage/motify the admin edit columns.
			add_filter( "manage_{$this->key}_posts_columns", [ $this, 'manage_columns' ], 10, 1 );

			// add custom columns
			add_filter( "manage_{$this->key}_posts_custom_column", [ $this, 'custom_columns' ], 10, 2 );

			// run filter to make columns sortable.
			add_filter( "manage_edit-{$this->key}_sortable_columns", [ $this, 'sortable_columns' ] );

			// run action that sorts columns on request.
			add_action( 'pre_get_posts', [ $this, 'orderby_columns' ] );
		}
	}

	/*
	|--------------------------------------------------------------------------
	| MANAGE ADMIN COLUMNS
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the Column Manager Instance for post type
	 *
	 * @return \Onyx\Columns
	 */
	public function columns() {
		if ( ! isset( $this->columns ) ) {
			$this->columns = new \Onyx\Columns();
		}

		return $this->columns;
	}

	/**
	 * Manage/Modify the columns for post type
	 *
	 * @param array $columns Default WordPress columns
	 * @return array The modified columns
	 */
	public function manage_columns( $columns ) {
		$columns = $this->columns->manage_columns( $columns );
		return $columns;
	}

	/**
	 * Populate custom columns for post type
	 *
	 * @param string $column   The column slug
	 * @param int    $post_id  The post ID
	 */
	public function custom_columns( $column, $post_id ) {
		if ( isset( $this->columns->populate[$column] ) ) {
			// $this->columns()->populate[$column] = [ $column, $post_id ];
			call_user_func_array( $this->columns()->populate[$column], [ $column, $post_id ] );
		}
	}

	/**
	 * Make custom columns sortable
	 * Callable from manage_edit-{$this->key}_sortable_columns
	 *
	 * @param array $columns Default WordPress sortable columns
	 */
	public function sortable_columns( $columns ) {

		if ( ! empty( $this->columns()->sortable ) ) {
			$columns = array_merge( $columns, $this->columns()->sortable );
		}

		return $columns;
	}

	/**
	 * Set query to sort custom columns
	 * Callable from pre_get_posts
	 *
	 * @param object $query WP_Query
	 */
	public function orderby_columns( $query ) {
		// don't modify the query if we're not in the post type admin
		if ( ! is_admin() || $query->get( 'post_type' ) !== $this->key ) {
			return;
		}

		$orderby = $query->get( 'orderby' );

		// if the sorting a custom column
		if ( $this->columns()->is_sortable( $orderby ) ) {
			// get the custom column options
			$meta_options = $this->columns()->sortable_meta_options( $orderby );

			// determine type of ordering
			$meta_key   = $meta_options[0];
			$meta_value = ($meta_options[1]) ? 'meta_value_num' : 'meta_value';

			// set the custom order
			$query->set( 'meta_key', $meta_key );
			$query->set( 'orderby', $meta_value );
		}
	}

	/**
	 * Register custom admin post types columns
	 *
	 * @param array $columns Custom columns [required]
	 * @return void
	 */
	public function register_columns( $columns ) {
		if ( ! is_admin() ) {
			return;
		}

		if ( ! empty( $columns['add'] ) ) {
			$this->columns()->register_columns( $columns['add'] );
		}

		if ( ! empty( $columns['hide'] ) ) {
			$this->columns()->hide( $columns['hide'] );
		}

		if ( ! empty( $columns['order'] ) ) {
			$this->columns()->order( $columns['order'] );
		}
	}

	/*
	|--------------------------------------------------------------------------
	| MANAGE COLUMN TAXONOMIES FILTERS
	|--------------------------------------------------------------------------
	*/

	/**
	 * Add filters to post type
	 *
	 * @param string|array $filters An array of taxonomy filters
	 * @return void
	 */
	public function filters( $filters ) {
		$filters = is_string( $filters ) ? [ $filters ] : $filters;

		foreach ( $filters as $filters ) {
			$this->filters[] = $filters;
		}
	}

	/**
	 * Modify and display filters on the admin edit screen
	 *
	 * @param string $posttype The current screen post type
	 * @return void
	 */
	public function manage_filters( $posttype ) {
		// first check we are working with the this PostType
		if ( $posttype === $this->key ) {
			// calculate what filters to add
			$filters = $this->filters;

			foreach ( $filters as $taxonomy ) {
				// if the taxonomy doesn't exist, ignore it
				if ( ! taxonomy_exists( $taxonomy ) ) {
					continue;
				}

				// get the taxonomy object
				$tax = get_taxonomy( $taxonomy );

				// get the terms for the taxonomy
				$terms = get_terms(
					[
						'taxonomy'   => $taxonomy,
						'orderby'    => 'name',
						'hide_empty' => false,
					]
				);

				// if there are no terms in the taxonomy, ignore it
				if ( empty( $terms ) ) {
					continue;
				}

				// start the html for the filter dropdown
				$selected  = null;
				$query_var = get_query_var( $taxonomy );
				if ( isset( $query_var ) ) {
					$selected = sanitize_title( $query_var );
				}

				$dropdown_args = [
					'option_none_value' => '',
					'hide_empty'        => 0,
					'hide_if_empty'     => false,
					'show_count'        => true,
					'taxonomy'          => $tax->name,
					'name'              => $taxonomy,
					'orderby'           => 'name',
					'hierarchical'      => true,
					'show_option_none'  => sprintf( __( 'Show all %s', 'onyx-theme' ), $tax->label ),
					'value_field'       => 'slug',
					'selected'          => $selected,
				];

				wp_dropdown_categories( $dropdown_args );
			}
		}
	}

}

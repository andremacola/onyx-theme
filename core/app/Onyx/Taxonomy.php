<?php
/**
 * Onyx Custom Taxonomies
 *
 * This class was inspired by https://github.com/jjgrainger/PostTypes/
 * Thanks to Joe Grainger https://github.com/jjgrainger
 *
 * @package Onyx Theme
 */

namespace Onyx;

class Taxonomy {

	/**
	 * The key for taxonomy
	 *
	 * @var string
	 */
	public $key;

	/**
	 * The names params to create the keys passed to the taxonomy
	 * [name, singular, plural, slug]
	 *
	 * @var array
	 */
	public $names;

	/**
	 * The name (singular) for taxonomy
	 *
	 * @var string
	 */
	public $name;

	/**
	 * The plural name for taxonomy
	 *
	 * @var string
	 */
	public $plural;

	/**
	 * The slug for taxonomy
	 *
	 * @var string
	 */
	public $slug;

	/**
	 * Related post types
	 *
	 * @var array
	 */
	public $cpts;

	/**
	 * Arguments for taxonomy
	 *
	 * @var array
	 */
	public $arguments;

	/**
	 * Labels for taxonomy
	 *
	 * @var array
	 */
	public $labels;


	/**
	 * Constructor
	 *
	 * @param string|array $names A string for the name, or an array of names [required]
	 * @param array        $cpts A post type object to register the taxonomy [optional]
	 * @param array        $arguments Arguments to register a taxonomy with register_taxonomy [optional]
	 * @param array        $labels Custom labels to create the taxonomy [optional]
	 * @return void
	 */
	public function __construct( $names = '', $cpts = null, $arguments = [], $labels = [] ) {
		$this->names( $names );
		$this->post_types( $cpts );
		$this->labels( $labels );
		$this->options( $arguments );
	}

	/**
	 * Set the names and key for the taxonomy
	 *
	 * @param string|array $names A string for the name, or an array of names [required]
	 * @return void
	 */
	public function names( $names ) {
		// if the name is a string, then assign the name in array
		$this->validate_name_params( $names );

		// set name (singular)
		$name       = $this->get_name();
		$this->name = (substr( $name, -1 ) === 's') ? substr( $name, 0, -1 ) : $name;

		// set plural name
		$plural       = (isset( $this->names['plural'] )) ? $this->names['plural'] : $name;
		$this->plural = (substr( $plural, -1 ) !== 's') ? $plural . 's' : $plural;

		// set slug
		$slug       = (isset( $this->names['slug'] )) ? $this->names['slug'] : $this->plural;
		$this->slug = sanitize_title( $slug );

		// set key
		$this->key = $this->slug;
	}

	/**
	 * Register related post types to the taxonomy
	 *
	 * @param array $cpts An array of arguments for taxonomy
	 * @return void
	 */
	public function post_types( $cpts = null ) {
		$this->cpts = $cpts;
	}

	/**
	 * Set the options for taxonomy
	 *
	 * @param array $arguments An array of arguments for taxonomy
	 * @return void
	 */
	public function options( $arguments ) {
		$this->arguments = $arguments;
	}

	/**
	 * Set the labels for the taxonomy
	 *
	 * @param array $labels An array of labels for the taxonomy
	 * @return void
	 */
	public function labels( $labels ) {
		$this->labels = $labels;
	}

	/**
	 * Get the formated taxonomy name
	 *
	 * @return string
	 */
	private function get_name() {
		$value = str_replace( [ '-', '_' ], ' ', $this->names['name'] );
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
	 * Create the labels for taxonomy
	 *
	 * @return array
	 */
	private function create_labels() {
		// default labels
		$plural_lower = strtolower( $this->plural );
		$labels       = [
			'name'                       => $this->plural,
			'singular_name'              => $this->name,
			'menu_name'                  => $this->plural,
			'all_items'                  => sprintf( __( 'All %s', 'onyx-theme' ), $this->plural ),
			'edit_item'                  => sprintf( __( 'Edit %s', 'onyx-theme' ), $this->name ),
			'view_item'                  => sprintf( __( 'View %s', 'onyx-theme' ), $this->name ),
			'update_item'                => sprintf( __( 'Update %s', 'onyx-theme' ), $this->name ),
			'add_new_item'               => sprintf( __( 'Add New %s', 'onyx-theme' ), $this->name ),
			'new_item_name'              => sprintf( __( 'New %s Name', 'onyx-theme' ), $this->name ),
			'parent_item'                => sprintf( __( 'Parent %s', 'onyx-theme' ), $this->plural ),
			'parent_item_colon'          => sprintf( __( 'Parent %s:', 'onyx-theme' ), $this->plural ),
			'search_items'               => sprintf( __( 'Search %s', 'onyx-theme' ), $this->plural ),
			'popular_items'              => sprintf( __( 'Popular %s', 'onyx-theme' ), $this->plural ),
			'separate_items_with_commas' => sprintf( __( 'Seperate %s with commas', 'onyx-theme' ), $this->plural ),
			'add_or_remove_items'        => sprintf( __( 'Add or remove %s', 'onyx-theme' ), $this->plural ),
			'choose_from_most_used'      => sprintf( __( 'Choose from most used %s', 'onyx-theme' ), $plural_lower ),
			'not_found'                  => sprintf( __( 'No %s found', 'onyx-theme' ), $plural_lower ),
			'back_to_items'              => sprintf( __( 'Back to %s', 'onyx-theme' ), $this->plural ),
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
	private function create_tax_arguments() {
		// default arguments
		$arguments = [
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			'rewrite'           => [
				'slug' => $this->slug,
			],
		];

		// replace defaults with the options passed
		$arguments = array_replace_recursive( $arguments, $this->arguments );

		// create and set labels
		if ( ! isset( $arguments['labels'] ) ) {
			$arguments['labels'] = $this->create_labels();
		}

		return $arguments;
	}

	/**
	 * Register the custom post type
	 *
	 * @return void
	 */
	public function register_taxonomy() {
		$arguments = $this->create_tax_arguments();

		if ( ! taxonomy_exists( $this->key ) ) {
			register_taxonomy( $this->key, $this->cpts, $arguments );
		}
	}

	/**
	 * Hook post type to WordPress
	 *
	 * @return bool
	 */
	public function register() {
		return add_action( 'init', [ $this, 'register_taxonomy' ] );
	}
}

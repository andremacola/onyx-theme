<?php 

/**
 * 
 * Onyx Custom Post Types
 * 
 */

class Onyx_Cpt {

	/**
	 * The key for post type
	 * @var string
	 */
    public $key;

	/**
	 * The names params to create the keys passed to the post type
	 * [name, singular, plural, slug]
	 * @var array
	 */
    public $names;

	/**
	 * The name (singular) for post type
	 * @var string
	 */
	 public $name;

	/**
	 * The plural name for post type
	 * @var string
	 */
	 public $plural;

	/**
	 * The slug for post type
	 * @var string
	 */
	 public $slug;

	/**
	 * Taxonomies for post type
	 * @var string}array
	 */
	public $taxonomies;

	/**
	 * Arguments for post type
	 * @var array
	 */
	public $arguments;

	/**
	 * Labels for post type
	 * @var array
	 */
	public $labels;

	/**
	 * Icon for post type
	 * @var string
	 */
	public $icon;


	/**
	 * Constructor
	 * 
	 * @param string|array $names A string for the name, or an array of names [required]
	 * @return void
	 */
	public function __construct($names, $arguments = [], $labels = []) {
		$this->names($names);
		$this->labels($labels);
		$this->options($arguments);
	}

	/**
	 * Set the names and key for the post type
	 * 
	 * @param string|array $names A string for the name, or an array of names [required]
	 * @return void
	 */
    public function names($names) {
		//  if the name is a string, then assign the name in array
		$this->validate_name_params($names);

		// set name (singular)
		$name         = $this->get_name();
		$this->name   = (substr($name, -1) === 's') ? substr($name, 0, -1) : $name;

		// set plural name
		$plural       = (isset($this->names['plural'])) ? $this->names['plural'] : $name;
		$this->plural = (substr($plural, -1) !== 's') ? $plural.'s' : $plural;

		// set slug
		$slug         = (isset($this->names['slug'])) ? $this->names['slug'] : $this->plural;
		$this->slug   = sanitize_title($slug);

		// set key
		$this->key    = $this->slug;
	}

	/**
	 * Set the options for post type
	 * 
	 * @param array $arguments An array of arguments for post type
	 * @return void
	 */
	public function options($arguments) {
		$this->arguments = $arguments;
	}

	/**
	 * Set the labels for the PostType
	 * 
	 * @param  array $labels An array of labels for the PostType
	 * @return void
	 */
	public function labels($labels) {
		$this->labels = $labels;
	}

	/**
	 * Set the icon for the post type
	 * 
	 * @link https://developer.wordpress.org/resource/dashicons/
	 * @param string $icon A dashicon class for the menu icon
	 * @return void
	 */
    public function icon($icon) {
		$this->icon = $icon;
    }

	/**
	 * Register a taxonomy to the post type
	 * 
	 * @param  mixed $taxonomies The Taxonomy name(s) to add
	 * @return void
	 */
	public function taxonomies($taxonomies) {
		$taxonomies = is_string($taxonomies) ? [$taxonomies] : $taxonomies;

		foreach ($taxonomies as $taxonomy) {
			$this->taxonomies[] = $taxonomy;
		}
	}

	/**
	 * Get the formated post type name
	 * 
	 * @return string
	 */
	private function get_name() {
		$value = str_replace(['-', '_'], ' ', $this->names['name']);
		$value = strtolower($value);
		return ucwords($value);
	}

	/**
	 * Validate $names params passed from __construct
	 * 
	 * @param string|array $names A string for the name, or an array of names [required]
	 * @return void
	 */
	private function validate_name_params($names) {
		if (is_string($names)) {
			$this->names['name'] = $names;
		} else if (is_array($names)) {
			$this->names = $names;
		} else {
			throw new Exception("Please, provide a string or array as param on constructor", 1);
		}
	}

	/**
	 * Create the labels for post type
	 * @return array
	 */
	private function create_labels() {
		// default labels
		$labels = [
			'name'                  => $this->plural,
			'singular_name'         => $this->name,
			'menu_name'             => __($this->plural),
			'all_items'             => __($this->plural),
			'add_new'               => __("Add New"),
			'add_new_item'          => __("Add New {$this->name}"),
			'edit_item'             => __("Edit {$this->name}"),
			'new_item'              => __("New {$this->name}"),
			'view_item'             => __("View {$this->name}"),
			'search_items'          => __("Search {$this->plural}"),
			'not_found'             => __("No {$this->plural} found"),
			'not_found_in_trash'    => __("No {$this->plural} found in Trash"),
			'parent_item_colon'     => __("Parent {$this->name}:")
		];

		// replace defaults with custom labels passed and return
		return array_replace_recursive($labels, $this->labels);
	}

	/**
	 * Create arguments to register post type
	 * 
	 * @return array Arguments to pass to register_cpt
	 */
    private function create_cpt_arguments() {
		// default arguments
		$arguments = [
			'public'   => true,
			'rewrite'  => [
				'slug' => $this->slug
			]
		];

		// replace defaults with the options passed
		$arguments = array_replace_recursive($arguments, $this->arguments);

		// register taxonomies
		if(!empty($this->taxonomies)) {
			$arguments['taxonomies'] = $this->taxonomies;
		}

		// create and set labels
		if (!isset($arguments['labels'])) {
			$arguments['labels'] = $this->create_labels();
		}

		// set the menu icon
		if (!isset($arguments['menu_icon']) && isset($this->icon)) {
			$arguments['menu_icon'] = $this->icon;
		}

		return $arguments;
    }

	/**
	 * Register the custom post type
	 * 
	 * @return void
	 */
	public function register_cpt() {
		$arguments = $this->create_cpt_arguments();
		
		if (!post_type_exists($this->key)) {
			register_post_type($this->key, $arguments);
		}
	}

	public function register() {
		add_action('init', [$this, 'register_cpt']);
	}

	// /**
	//  * Register Taxonomies to the PostType
	//  * @return void
	//  */
	// public function register_taxonomies() {
	// 	if (!empty($this->taxonomies)) {
	// 		foreach ($this->taxonomies as $taxonomy) {
	// 			register_taxonomy_for_object_type($taxonomy, $this->name);
	// 		}
	// 	}
	// }

}


?>

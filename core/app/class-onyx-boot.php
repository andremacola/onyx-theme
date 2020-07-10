<?php
/**
 * Onyx Theme Boot
 *
 * Load and assign Templates and Controllers
 *
 * @package Onyx Theme
 */

namespace Onyx;

class Boot {

	/**
	 * Template hierarchy requested from WordPress
	 *
	 * @var array
	 */
	protected $templates = array();

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->hierarchy_template_filter();
		$this->include_template_controller_filter();
	}

	/**
	 * Get and filter required template hierarchy array
	 *
	 * @return array
	 */
	protected function get_templates() {
		return array_unique( call_user_func_array( 'array_merge', $this->templates ) );
	}

	/**
	 * Itinerante through WordPress template hierarchy
	 * and add filter to get the templates WordPress loader order
	 *
	 * @see https://wphierarchy.com/
	 * @see https://developer.wordpress.org/reference/hooks/type_template_hierarchy/
	 */
	protected function hierarchy_template_filter() {
		$hierarchies = [
			'index_template_hierarchy',
			'404_template_hierarchy',
			'archive_template_hierarchy',
			'author_template_hierarchy',
			'category_template_hierarchy',
			'tag_template_hierarchy',
			'taxonomy_template_hierarchy',
			'date_template_hierarchy',
			'home_template_hierarchy',
			'frontpage_template_hierarchy',
			'page_template_hierarchy',
			'paged_template_hierarchy',
			'search_template_hierarchy',
			'single_template_hierarchy',
			'singular_template_hierarchy',
			'attachment_template_hierarchy',
		];

		foreach ( $hierarchies as $hierarchy ) {
			add_filter( $hierarchy, [ $this, 'set_template_hierarchy' ], 10 );
		}
	}

	/**
	 * Set templates variable with all the WordPress requested templates
	 *
	 * @see https://developer.wordpress.org/reference/hooks/type_template_hierarchy/
	 * @param array $files Passed by `{type}_template_hierarchy`
	 */
	public function set_template_hierarchy( $files ) {
		$this->templates[] = $files;
		return $files;
	}

	/**
	 * Use `template_include` WordPress filter to include proper template
	 * and his Controller class
	 *
	 * @see https://wphierarchy.com/
	 * @see https://developer.wordpress.org/reference/hooks/type_template_hierarchy/
	 */
	protected function include_template_controller_filter() {
		add_filter( 'template_include', [ $this, 'load_template_controller_class' ], 100 );
	}

	/**
	 * Load proper controller class based on template hierarchy
	 * The class name convetion follow the WordPress Standard
	 *
	 * @see https://wphierarchy.com/
	 * @param string $template Default WordPress template path
	 */
	public function load_template_controller_class( $template ) {
		foreach ( $this->get_templates() as $t ) {

			$controller_file = locate_template( "core/controllers/{$this->get_controller_file( $t )}" );
			if ( $controller_file ) {
				/**
				 * Instantiate Controller Class if exists
				 */
				$controller_class = $this->get_controller_name( $t );

				if ( class_exists( $controller_class ) ) {
					new $controller_class();
				}

				/**
				 * If first occurrence found in template hierarchy,
				 * return it and ignore other template files
				 */
				return $template;
			}
		}

		return $template;
	}

	/**
	 * Rename and get Class file name following WordPress Coding Standards
	 *
	 * @see https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/
	 * @param string $file The name of the template file loaded from hierarchy [required]
	 * @param string $suffix Filename suffix [optional]
	 * @return string
	 */
	protected function get_controller_file( $file, $suffix = '-controller' ) {
		$search     = [ '_', '.php' ];
		$replace    = [ '-', '' ];
		$controller = str_replace( $search, $replace, $file );
		$controller = trim( str_replace( ' ', '_', $controller ) );
		return ( '404' === $controller ) ? 'error404-controller.php' : "$controller$suffix.php";
	}

	/**
	 * Rename and get controller class name following WordPress Coding Standards
	 *
	 * @see https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/
	 * @param string $file The name of the template file loaded from hierarchy
	 * @return string
	 */
	protected function get_controller_name( $file ) {
		$namespace = '\Onyx\Controllers\\';
		$search    = [ '_', '-', '.php' ];
		$replace   = [ ' ', ' ', '', '' ];

		$controller = str_replace( $search, $replace, $file );
		$controller = trim( str_replace( ' ', '_', ucwords( $controller ) ) );

		$controller = $namespace . $controller;
		return ( '\Onyx\Controllers\404' === $controller ) ? "{$namespace}Error_404_Controller" : "{$controller}_Controller";
	}

}

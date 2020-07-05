<?php

namespace Onyx;

class Boot {

	protected $templates = array();

	public function __construct() {
		$this->add_filters();
		add_filter( 'template_include', [ $this, 'template_include' ], 100 );
	}

	public function run() {
	}

	protected function get_controller_name( $file ) {
		$search     = [ '_', '-', '.php' ];
		$replace    = [ ' ', ' ', '' ];
		$controller = ucwords( str_replace( $search, $replace, $file ) );
		$controller = trim( str_replace( ' ', '_', $controller ) );
		return ( '404' === $controller ) ? 'Error_404' : $controller;
	}

	protected function get_controller_file( $file ) {
		$search     = [ '_', '-', '.php' ];
		$replace    = [ ' ', '', '' ];
		$controller = str_replace( $search, $replace, $file );
		$controller = trim( str_replace( ' ', '_', $controller ) );
		return ( '404' === $controller ) ? 'class-404-controller.php' : "class-$controller-controller.php";
	}

	protected function add_filters() {
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

	public function template_include( $template ) {
		foreach ( $this->get_templates() as $t ) {
			$t = locate_template( "core/controllers/{$this->get_controller_file( $t )}" );
			if ( $t ) {
				return $t;
			}
		}

		return $template;
	}

	public function set_template_hierarchy( $files ) {
		$this->templates[] = $files;
		return $files;
	}

	protected function set_templates( $files ) {
		$this->templates[] = $files;
	}

	protected function get_templates() {
		return array_unique( call_user_func_array( 'array_merge', $this->templates ) );
	}

}

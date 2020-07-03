<?php
/**
 * Onyx Custom Sidebars
 *
 * Create and register a new sidebar
 *
 * @package Onyx Theme
 * @see https://developer.wordpress.org/reference/functions/register_sidebar/
 */

namespace Onyx;

class Sidebar {

	/**
	 * Sidebar name variable
	 *
	 * @var string
	 */
	private $name;

	/**
	 * Sidebar name variable
	 *
	 * @var array
	 */
	private $args;

	/**
	 * Constructor
	 *
	 * @param string $name The name or title of the sidebar displayed [required]
	 * @param array  $args Array or string of arguments for the sidebar being registered [optional]
	 */
	public function __construct( $name = '', $args = [] ) {
		$this->name( $name );
		$this->args( $args );
	}

	/**
	 * Set sidebar name
	 *
	 * @param string $name
	 * @return void
	 */
	public function name( $name ) {
		$this->name = $name;
	}

	/**
	 * Set sidebar arguments
	 *
	 * @param array $args
	 * @return void
	 */
	public function args( $args = [] ) {

		$name = ( ! empty( $this->args['name'] )) ? $this->args['name'] : $this->name;
		$id   = sanitize_key( $name );

		// default arguments
		$default = [
			'name'          => $name,
			'id'            => $id,
			'description'   => '',
			'class'         => "widget-$id",
			'before_widget' => '<div id="%1$s" class="side-section %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => "<h6 class='side-title'>",
			'after_title'   => '</h6>',
		];

		// replace defaults with the options passed.
		$this->args = array_replace_recursive( $default, $args );
	}

	/**
	 * Create sidebar arguments
	 *
	 * @return string Sidebar ID added to $wp_registered_sidebars global.
	 */
	public function create_sidebar() {
		return register_sidebar( $this->args );
	}

	/**
	 * Register the sidebar to WordPress
	 *
	 * @return void
	 */
	public function register() {
		add_action( 'widgets_init', [ $this, 'create_sidebar' ] );
	}

}

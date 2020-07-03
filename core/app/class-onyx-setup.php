<?php
/**
 * Onyx Theme Setup
 *
 * @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
 *
 * @package Onyx Theme
 */

namespace Onyx;

final class Setup {

	/**
	 * App enviroment variables
	 *
	 * @var array|object
	 */
	private $app;

	/**
	 * Theme assets
	 *
	 * @var array|object
	 */
	private $assets;

	/**
	 * Theme images sizes
	 *
	 * @var array|object
	 */
	private $images;

	/**
	 * Mail smtp configuration
	 *
	 * @var array|object
	 */
	private $mail;

	/**
	 * Theme support WordPress features
	 *
	 * @var array|object
	 */
	private $support;

	/**
	 * Custom post types
	 *
	 * @var array|object
	 */
	private $cpts;

	/**
	 * Custom taxonomies
	 *
	 * @var array|object
	 */
	private $taxs;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->app      = O::load( 'app' );
		$this->assets   = O::load( 'assets' );
		$this->hooks    = O::load( 'hooks' );
		$this->images   = O::load( 'images' );
		$this->mail     = O::load( 'mail' );
		$this->sidebars = O::load( 'sidebars' );
		$this->support  = O::load( 'support' );
		$this->cpts     = O::load( 'cpts' );
		$this->taxs     = O::load( 'taxonomies' );

		define( 'ONYX_THEME', true );
		define( 'ONYX_THEME_VERSION', $this->app->version );
		define( 'ONYX_STATIC_VERSION', $this->version() );
		define( 'ONYX_STATIC', $this->app->static );

		add_action( 'after_setup_theme', [ $this, 'setup' ] );
	}

	/**
	 * Setup and start theme
	 *
	 * @return void
	 */
	public function setup() {
		$this->register_theme_support();
		$this->manage_actions();
		$this->manage_filters();
		$this->register_image_sizes();
		$this->register_post_types();
		$this->register_taxonomies();
		$this->register_sidebars();
		$this->manage_rest_api();
	}

	/**
	 * Rest api base configurations
	 *
	 * @see config/app.php
	 * @return void
	 */
	private function manage_rest_api() {
		// change default WordPress rest api prefix
		add_filter(
			'rest_url_prefix',
			function() {
				return $this->app->rest;
			}
		);
	}

	/**
	 * Register custom sidebars
	 *
	 * @see config/sidebars.php
	 * @return void
	 */
	private function register_sidebars() {
		if ( ! empty( $this->sidebars ) ) {
			foreach ( $this->sidebars as $sidebar ) {
				$sidebar = new \Onyx\Sidebar( $sidebar['name'], $sidebar );
				$sidebar->register();
			}
		}
	}

	/**
	 * Register custom post types
	 *
	 * @see config/cpts.php
	 * @return void
	 */
	private function register_post_types() {
		if ( ! empty( $this->cpts ) ) {
			foreach ( $this->cpts as $cpt ) {
				$options = ( ! empty( $cpt['options'] )) ? $cpt['options'] : [];
				$labels  = ( ! empty( $cpt['labels'] )) ? $cpt['labels'] : [];
				$pt      = new Cpt( $cpt['names'], $options, $labels );

				if ( ! empty( $cpt['icon'] ) ) {
					$pt->icon( $cpt['icon'] );
				}

				if ( ! empty( $cpt['filters'] ) ) {
					$pt->filters( $cpt['filters'] );
				}

				if ( ! empty( $cpt['columns']['hide'] ) || ! empty( $cpt['columns']['add'] ) ) {
					$pt->register_columns( $cpt['columns'] );
				}

				$pt->register();
			}
		}
	}

	/**
	 * Register custom taxonomies
	 *
	 * @see config/taxonomies.php
	 * @return void
	 */
	private function register_taxonomies() {
		if ( ! empty( $this->taxs ) ) {
			foreach ( $this->taxs as $tax ) {
				$types   = ( ! empty( $tax['types'] )) ? $tax['types'] : null;
				$options = ( ! empty( $tax['options'] )) ? $tax['options'] : [];
				$labels  = ( ! empty( $tax['labels'] )) ? $tax['labels'] : [];
				$tax     = new Taxonomy( $tax['names'], $types, $options, $labels );
				$tax->register();
			}
		}
	}

	/**
	 * Register custom image sizes
	 *
	 * @see config/images.php
	 * @return void
	 */
	private function register_image_sizes() {
		if ( ! empty( (array) $this->images ) ) {
			foreach ( $this->images as $name => $param ) {
				add_image_size( $name, $param[0], $param[1], $param[2] );
			}
		}
	}

	/**
	 * Manage (remove/add) actions,
	 *
	 * @see config/hooks.php
	 * @return void
	 */
	private function manage_actions() {
		foreach ( $this->hooks->actions as $key => $hooks ) {
			$this->register_hooks( "{$key}_action", $hooks );
		}
	}

	/**
	 * Manage Filters
	 * Remove unwanted filters
	 * Add custom filters
	 *
	 * @see config/hooks.php
	 * @return void
	 */
	private function manage_filters() {
		foreach ( $this->hooks->filters as $key => $hooks ) {
			$s = ( 'apply' === $key ) ? 's' : null; // oh hack day
			$this->register_hooks( "{$key}_filter{$s}", $hooks );
		}
	}

	/**
	 * Register hooks. This method transform the $key value to a WordPress hook function
	 * [remove_filter, add_filter, remove_action, add_action]
	 *
	 * @see config/hooks.php
	 * @param string $key Function key (add|remove - action|filter) [required]
	 * @param array  $hooks Hooks array to register [required]
	 * @return void;
	 */
	private function register_hooks( $key, $hooks ) {
		foreach ( $hooks as $hook ) {
			$hook = $this->get_hook_params( $hook );
			$key( $hook->tag, $hook->function, $hook->priority );
		}
	}

	/**
	 * Get hook params from object|array
	 *
	 * @see config/hooks.php
	 * @param array $arr ​​containing the tag, function and priority [required]
	 * @return object
	 */
	private function get_hook_params( $arr ) {
		return (object) [
			'tag'      => $arr[0],
			'function' => $arr[1],
			'priority' => (isset( $arr[2] )) ? $arr[2] : 10,
			'args'     => (isset( $arr[3] )) ? $arr[3] : false,
		];
	}

	/**
	 * Add theme support
	 *
	 * @see config/support.php
	 * @return void
	 */
	private function register_theme_support() {
		foreach ( $this->support as $feature ) {
			add_theme_support( $feature );
		}
	}

	/**
	 * Define theme version
	 * Mostly used for development purpose (cachebuster)
	 *
	 * @return int
	 */
	private function version() {
		return (in_array( $this->app->user, $this->app->devs )) ? wp_rand( 0, 99999 ) : $this->app->version;
	}

	/**
	 * Defines a constant if doesnt already exist.
	 *
	 * @param string $name The constant name.
	 * @param mixed  $value The constant value.
	 * @return void
	 */
	private function define( $name, $value = true ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Show app params
	 *
	 * @param string $param Param name [required]
	 * @return void
	 */
	public function show( $param ) {
		echo $this->get( $param );
	}

	/**
	 * Get app params
	 *
	 * @param string $param Param name [required]
	 * @return mixed
	 */
	public function get( $param ) {
		return $this->app->$param;
	}

	/**
	 * Set app params
	 *
	 * @param string          $param Param name [required]
	 * @param string|int|bool $value Params value [required]
	 * @return void
	 */
	public function set( $param, $value ) {
		if ( $param && $value ) {
			$this->app->$param = $value;
		}
	}

}

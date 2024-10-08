<?php
/**
 * Onyx Theme Setup
 *
 * @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
 *
 * @package Onyx Theme
 */

namespace Onyx;

use Onyx\Helpers as O;

class Setup extends \Timber\Site {

	/**
	 * App enviroment variables
	 *
	 * @var array|object
	 */
	protected $env;

	/**
	 * App features
	 *
	 * @var array|object
	 */
	protected $classes;

	/**
	 * Theme assets
	 *
	 * @var array|object
	 */
	protected $assets;

	/**
	 * Theme hooks
	 *
	 * @var array|object
	 */
	protected $hooks;

	/**
	 * Theme images sizes
	 *
	 * @var array|object
	 */
	protected $images;

	/**
	 * Mail smtp configuration
	 *
	 * @var array|object
	 */
	protected $mail;

	/**
	 * Sidebars options
	 *
	 * @var array|object
	 */
	protected $sidebars;

	/**
	 * Theme support WordPress features
	 *
	 * @var array|object
	 */
	protected $support;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->env      = O::load( 'env' );
		$this->classes  = O::load( 'app' );
		$this->assets   = O::load( 'assets' );
		$this->hooks    = O::load( 'hooks' );
		$this->images   = O::load( 'images' );
		$this->mail     = O::load( 'mail' );
		$this->sidebars = O::load( 'sidebars' );
		$this->support  = O::load( 'support' );

		define( 'ONYX_THEME', true );
		define( 'ONYX_THEME_VERSION', $this->version() );

		add_action( 'after_setup_theme', [ $this, 'setup' ] );

		if ( $this->env->local ) {
			add_action( 'wp_enqueue_scripts', 'onyx_enqueue_livereload' );
		}

		parent::__construct();
	}

	/**
	 * Setup and start theme
	 *
	 * @return void
	 */
	public function setup() {
		$this->load_tests_if_exist();
		$this->register_theme_support();
		$this->register_image_sizes();
		$this->register_sidebars();
		$this->register_app_features();
		$this->manage_actions();
		$this->manage_filters();
	}

	/**
	 * Add theme support
	 *
	 * @see config/support.php
	 * @return false|void
	 */
	protected function register_theme_support() {
		if ( $this->support ) {
			foreach ( $this->support as $feature ) {
				add_theme_support( $feature );
			}
		}
	}

	/**
	 * Register custom image sizes
	 *
	 * @see config/images.php
	 * @return void
	 */
	protected function register_image_sizes() {
		if ( $this->images && ! empty( (array) $this->images ) ) {
			foreach ( $this->images as $name => $param ) {
				add_image_size( $name, $param[0], $param[1], $param[2] );
			}
		}
	}

	/**
	 * Register custom sidebars
	 *
	 * @see config/sidebars.php
	 * @return void
	 */
	protected function register_sidebars() {
		if ( $this->sidebars && ! empty( $this->sidebars ) ) {
			foreach ( $this->sidebars as $sidebar ) {
				$sidebar = new \Onyx\Sidebar( $sidebar['name'], $sidebar );
				$sidebar->register();
			}
		}
	}

	/**
	 * Register App Classes
	 *
	 * @see config/app.php
	 * @return void
	 */
	protected function register_app_features() {
		if ( $this->classes && ! empty( $this->classes ) ) {
			foreach ( $this->classes as $class ) {
				if ( class_exists( $class ) ) {
					new $class();
				}
			}
		}
	}

	/**
	 * Manage (remove/add) actions,
	 *
	 * @see config/hooks.php
	 * @return void
	 */
	protected function manage_actions() {
		if ( $this->hooks ) {
			foreach ( $this->hooks->actions as $key => $hooks ) {
				$this->register_hooks( "{$key}_action", $hooks );
			}
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
	protected function manage_filters() {
		if ( $this->hooks ) {
			foreach ( $this->hooks->filters as $key => $hooks ) {
				$s = ( 'apply' === $key ) ? 's' : null; // oh hack day
				$this->register_hooks( "{$key}_filter{$s}", $hooks );
			}
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
	protected function register_hooks( $key, $hooks ) {
		foreach ( $hooks as $hook ) {
			$hook = $this->get_hook_params( $hook );
			$key( $hook->tag, $hook->function, $hook->priority, $hook->args );
		}
	}

	/**
	 * Get hook params from object|array
	 *
	 * @see config/hooks.php
	 * @param array $arr ​​containing the tag, function and priority [required]
	 * @return object
	 */
	protected function get_hook_params( $arr ) {
		return (object) [
			'tag'      => $arr[0],
			'function' => $arr[1],
			'priority' => (isset( $arr[2] )) ? $arr[2] : 10,
			'args'     => (isset( $arr[3] )) ? $arr[3] : 1,
		];
	}

	/**
	 * Require tests if exist
	 * Documentation: needed
	 *
	 * @return void
	 */
	protected function load_tests_if_exist() {
		if ( defined( 'ONYX_TESTS' ) && ONYX_TESTS ) {
			$test = __DIR__ . '/../../../tests/functions.php';
			if ( file_exists( $test ) ) {
				require $test;
			}
		}
	}

	/**
	 * Define theme version
	 * Mostly used for development purpose (cachebuster)
	 *
	 * @return int
	 */
	protected function version() {
		return (in_array( $this->env->user, $this->env->devs )) ? wp_rand( 0, 99999 ) : $this->env->version;
	}

}

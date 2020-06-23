<?php

/**
 * 
 * Onyx Theme Setup
 * 
 */


final class Onyx_Setup {

	private $app;
	private $assets;
	private $images;
	private $mail;
	private $support;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->app      = O::load('app');
		$this->assets   = O::load('assets');
		$this->hooks    = O::load('hooks');
		$this->images   = O::load('images');
		$this->mail     = O::load('mail');
		$this->support  = O::load('support');

		define('ONYX_THEME', true);
		define('ONYX_THEME_VERSION', $this->version());
		define('ONYX_STATIC', $this->app->static);
		
		add_action('after_setup_theme', [$this, 'setup']);
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
	}


	/**
	 * Register custom image sizes
	 * 
	 * @see config/images.php
	 * @return void
	 */
	private function register_image_sizes() {
		if (empty((array) $this->images)) {
			foreach ($this->images as $name => $param) {
				add_image_size($name, $param[0], $param[1], $param[2]);
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
		foreach ($this->hooks->actions as $key => $hooks) {
			$this->register_hooks("{$key}_action", $hooks);
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
		foreach ($this->hooks->filters as $key => $hooks) {
			$s = ($key === 'apply') ? 's' : null; // oh hack day
			$this->register_hooks("{$key}_filter{$s}", $hooks);
		}
	}

	/**
	 * Register hooks. This method transform the $key value to a wordpress hook function
	 * [remove_filter, add_filter, remove_action, add_action]
	 * 
	 * @see config/hooks.php
	 * @param string $key Function key (add|remove - action|filter) [required]
	 * @param array $hooks Hooks array to register [required]
	 * @return void;
	 */
	private function register_hooks($key, $hooks) {
		foreach ($hooks as $hook) {
			$hook = $this->get_hook_params($hook);
			$key($hook->tag, $hook->function, $hook->priority);
		}
	}

	/**
	 * Get hook params from object|array
	 * 
	 * @see config/hooks.php
	 * @param array $arr ​​containing the tag, function and priority [required]
	 * @return object
	 */
	private function get_hook_params($arr) {
		return (object) [
			'tag' => $arr[0],
			'function' => $arr[1],
			'priority' => (isset($arr[2])) ? $arr[2] : 10,
			'args' => (isset($arr[3])) ? $arr[3] : false
		];
	}

	/**
	 * Add theme support
	 * 
	 * @see config/support.php
	 * @return void
	 */
	private function register_theme_support() {
		foreach ($this->support as $feature) {
			add_theme_support($feature);
		}
	}

	/**
	 * Define theme version
	 *
	 * @return int
	 */
	private function version() {
		return (in_array($this->app->user, $this->app->devs)) ? rand(0,99999) : $this->app->v;
	}

	/**
	 * Defines a constant if doesnt already exist.
	 *
	 * @param string $name The constant name.
	 * @param mixed $value The constant value.
	 * @return void
	 */
	private function define($name, $value = true) {
		if(!defined($name)) {
			define($name, $value);
		}
	}

	/**
	 * Show app params
	 *
	 * @param string $param Param name [required]
	 * @return void
	 */
	public function show($param) {
		echo $this->get($param);
	}

	/**
	 * Get app params
	 *
	 * @param string $param Param name [required]
	 * @return mixed
	 */
	public function get($param) {
		return $this->app->$param;
	}

	/**
	 * Set app params
	 *
	 * @param string $param Param name [required]
	 * @param string|int|bool $value Params value [required]
	 * @return void
	 */
	public function set($param, $value) {
		if ($param && $value) {
			$this->app->$param = $value;
		}
	}

}


?>

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

	public function __construct() {
		$this->app      = $this->load('app');
		$this->assets   = $this->load('assets');
		$this->hooks    = $this->load('hooks');
		$this->images   = $this->load('images');
		$this->mail     = $this->load('mail');
		$this->support  = $this->load('support');

		if(!defined('ONYX_THEME')) {
			define('ONYX_THEME', true);
			define('ONYX_THEME_VERSION', $this->version());
			define('ONYX_STATIC', $this->app->static);
		}

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
	}

	/**
	 * Manage (remove/add) actions,
	 * 
	 * @see config/hooks.php
	 * @return void
	 */
	private function manage_actions() {
		foreach ($this->hooks['actions'] as $key => $hooks) {
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
		foreach ($this->hooks['filters'] as $key => $hooks) {
			$this->register_hooks("{$key}_filter", $hooks);
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
	 * Load configuration file.
	 * The configuration file must be returning an array
	 *
	 * @param string $file File name [required]
	 * @return array|object|false
	 */
	private function load($file) {
		if (file_exists($file = __DIR__."/../config/$file.php")) {
			return require_once $file;
		}
		return false;
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

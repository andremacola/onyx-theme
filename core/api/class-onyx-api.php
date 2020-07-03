<?php
/**
 * Onyx custom api route
 *
 * @see https://developer.wordpress.org/rest-api/
 *
 * @package Onyx Theme
 */

namespace Onyx;

class MyCustomApi {

	/**
	 * The namespace for the rest endpoint api
	 *
	 * @var string
	 */
	protected $namespace;

	/**
	 * The route for the namespace
	 *
	 * @var string
	 */
	protected $route;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {
		$this->namespace = 'myapi';
		$this->route     = 'myroute/v1';
	}

	/**
	 * Register routes
	 *
	 * @return void
	 */
	public function register_routes() {
		// register route for...
		register_rest_route(
			$this->namespace,
			'/' . $this->route,
			array(
				'methods'  => \WP_REST_Server::READABLE, // GET
				'callback' => array( $this, 'onyx_cron_func' ),
			)
		);
	}

	/**
	 * Hook and initialize the api do WordPress
	 *
	 * @return void
	 */
	public function initialize() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

}

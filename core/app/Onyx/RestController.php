<?php
/**
 * Onyx custom api route
 *
 * @see https://developer.wordpress.org/rest-api/
 * @see https://andremacola.github.io/onyx-theme-doc/docs/rest
 * @package Onyx Theme
 */

namespace Onyx;

abstract class RestController {

	/**
	 * The namespace for the rest endpoint api
	 *
	 * @var string
	 */
	protected $namespace;

	/**
	 * Constructor
	 */
	abstract public function __construct();

	/**
	 * Register routes
	 */
	abstract public function register_routes();

	/**
	 * Hook and initialize the api do WordPress
	 *
	 * @return void
	 */
	public function initialize() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Add route
	 *
	 * @param \WP_Rest_Server|mixed $method (GET, POST, PUT, PATCH, DELETE) [required]
	 * @param string                $route The Endpoint route [required]
	 * @param callable              $callback Method to execute [required]
	 * @param array                 $options Endpoint options [optional]
	 * @param bool                  $override Overwrite existing route [optional]
	 * @return bool
	 * @throws \Exception           If the $this->namespace does not exist.
	 */
	protected function route( $method, $route, $callback, $options = [], $override = false ) {
		if ( ! $this->namespace ) {
			throw new \Exception( 'You need to provide a namespace', 1 );
		}

		$args = [
			'methods'  => $method,
			'callback' => [ $this, $callback ],
		];

		$options = array_merge( $args, $options );

		return register_rest_route(
			$this->namespace,
			$route,
			$options,
			$override
		);
	}

	/**
	 * Ensures a REST response is a response object (for consistency).
	 *
	 * @param mixed $data Response data. Default null.
	 * @param int   $status HTTP status code. Default 200. [optional]
	 * @param array $headers HTTP header map. Default empty array. [optional]
	 * @return mixed
	 */
	protected function rest_response( $data, $status = 200, $headers = [] ) {
		return new \WP_REST_Response( $data, $status, $headers );
	}

}

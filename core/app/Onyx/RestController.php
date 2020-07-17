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
	 * @see \rest_ensure_response();
	 * @param WP_HTTP_Response|WP_Error|mixed $data esponse to check
	 * @return WP_REST_Response|mixed If response generated an error, WP_Error, if response
	 *                                is already an instance, WP_HTTP_Response, otherwise
	 *                                returns a new WP_REST_Response instance.
	 */
	protected function response( $data ) {
		return rest_ensure_response( $data );
	}

}

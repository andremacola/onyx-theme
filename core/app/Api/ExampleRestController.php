<?php
/**
 * Onyx Custom Api Route
 *
 * @see https://developer.wordpress.org/rest-api/
 * @see https://andremacola.github.io/onyx-theme-doc/docs/rest
 * @package Onyx Theme
 */

namespace Onyx\Controllers;

use \Onyx\RestController;

class ExampleRestController extends RestController {

	/**
	 * The namespace for the rest endpoint api
	 *
	 * @var string
	 */
	protected $namespace = 'onyx/v1';

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {
		$this->initialize();
	}

	/**
	 * Register routes
	 *
	 * @return void
	 */
	public function register_routes() {
		// register examples routes
		$this->route( 'POST', '/example', 'generic_callback' );
		$this->route( 'GET', '/example', 'generic_callback' );
	}

	/**
	 * Generic Callback
	 *
	 * @param \WP_REST_Request $req
	 * @return mixed
	 */
	public function generic_callback( \WP_REST_Request $req ) {
		return $this->rest_response( $req->get_params() );
	}

}

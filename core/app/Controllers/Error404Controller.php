<?php
/**
 * Error 404 Controller
 *
 * @see https://andremacola.github.io/onyx-theme-doc/docs/controllers
 * @package Onyx Theme
 */

namespace Onyx\Controllers;

use Onyx\Controller;

class Error404Controller extends Controller {

	/**
	 * Initialize
	 */
	public function initialize() {
		$this->set_templates( [ 'pages/error404.twig', 'pages/index.twig' ] );
	}

}

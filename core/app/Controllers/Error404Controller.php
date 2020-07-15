<?php
/**
 * Error 404 Controller
 *
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

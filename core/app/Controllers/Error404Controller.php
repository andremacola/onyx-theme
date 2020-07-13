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
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->templates = [ 'pages/error404.twig' ];

		$this->render_view();
	}

}

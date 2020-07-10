<?php
/**
 * Error 404 Controller
 *
 * @package Onyx Theme
 */

namespace Onyx\Controllers;

class Error_404_Controller extends Controller {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->templates = [ 'pages/error404.twig' ];

		$this->render_view();
	}

}

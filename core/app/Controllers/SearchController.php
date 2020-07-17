<?php
/**
 * Search Controller
 *
 * @see https://andremacola.github.io/onyx-theme-doc/docs/controllers
 * @package Onyx Theme
 */

namespace Onyx\Controllers;

use Onyx\Controller;

class SearchController extends Controller {

	/**
	 * Initialize
	 */
	public function initialize() {
		$this->set_templates( [ 'pages/search.twig' ] );
		$this->set_context( 'posts', $this->get_posts() );
	}

}

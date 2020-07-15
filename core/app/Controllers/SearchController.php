<?php
/**
 * Search Controller
 *
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

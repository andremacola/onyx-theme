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
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->templates        = [ 'pages/search.twig' ];
		$this->context['posts'] = $this->get_posts();

		$this->render_view();
	}

}

<?php
/**
 * Category Controller
 *
 * @see https://andremacola.github.io/onyx-theme-doc/docs/controllers
 * @package Onyx Theme
 */

namespace Onyx\Controllers;

use Onyx\Controller;

class CategoryController extends Controller {

	/**
	 * Initialize
	 */
	public function initialize() {
		$this->set_taxonomy_templates();
		$this->set_context( 'posts', $this->get_posts() );
	}

}

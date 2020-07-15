<?php
/**
 * Category Controller
 *
 * @package Onyx Theme
 */

namespace Onyx\Controllers;

use Onyx\Controller;

class CategoryController extends Controller {

	/**
	 * Initialize
	 */
	public function initialize() {
		$this->set_context( 'posts', $this->get_posts() );
		$this->set_taxonomy_templates();
	}

}

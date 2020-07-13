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
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->context['posts'] = $this->get_posts();

		$this->set_taxonomy_templates();
		$this->render_view();
	}

}

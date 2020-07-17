<?php
/**
 * Page Controller
 *
 * @see https://andremacola.github.io/onyx-theme-doc/docs/controllers
 * @package Onyx Theme
 */

namespace Onyx\Controllers;

use Onyx\Controller;

class PageController extends Controller {

	/**
	 * Initialize
	 */
	public function initialize() {
		$this->set_page_templates( 'page' );
		$this->set_context( 'post', $this->get_post() );
	}

}

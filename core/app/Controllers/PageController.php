<?php
/**
 * Page Controller
 *
 * @package Onyx Theme
 */

namespace Onyx\Controllers;

use Onyx\Controller;

class PageController extends Controller {

	/**
	 * Initialize
	 */
	public function initialize() {
		$this->set_context( 'post', $this->get_post() );
		$this->set_page_templates( 'page' );
	}

}

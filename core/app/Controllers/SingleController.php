<?php
/**
 * Single Controller
 *
 * @package Onyx Theme
 */

namespace Onyx\Controllers;

use Onyx\Controller;

class SingleController extends Controller {

	/**
	 * Initialize
	 */
	public function initialize() {
		$this->set_page_templates( 'single' );
		$this->set_context( 'post', $this->get_post() );
	}

}

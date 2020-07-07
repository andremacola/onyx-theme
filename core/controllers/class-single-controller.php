<?php
/**
 * Single Controller
 *
 * @package Onyx Theme
 */

namespace Onyx\Controllers;

class Single_Controller extends Controller {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->context['post'] = $this->get_post();

		$this->set_page_templates( $this->context['post'], 'single' );
		$this->render_view();
	}

}

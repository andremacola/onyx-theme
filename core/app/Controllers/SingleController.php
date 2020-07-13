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
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->context['post'] = $this->get_post();

		$this->set_page_templates( $this->context['post'], 'single' );
		$this->render_view();
	}

}

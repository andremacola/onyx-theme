<?php
/**
 * Archive Controller
 *
 * @package Onyx Theme
 */

namespace Onyx\Controllers;

use Onyx\Controller;

class ArchiveController extends Controller {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->context['posts'] = $this->get_posts();

		$this->set_archive_templates();
		$this->render_view();
	}

}

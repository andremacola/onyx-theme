<?php
/**
 * Archive Controller
 *
 * @phpcs:disable PEAR.Functions.FunctionCallSignature.ContentAfterOpenBracket
 * @phpcs:disable PEAR.Functions.FunctionCallSignature.CloseBracketLine
 *
 * @package Onyx Theme
 */

namespace Onyx\Controllers;

class Archive_Controller extends Controller {

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

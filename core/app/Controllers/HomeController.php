<?php
/**
 * Home Controller
 *
 * @phpcs:disable PEAR.Functions.FunctionCallSignature.ContentAfterOpenBracket
 * @phpcs:disable PEAR.Functions.FunctionCallSignature.CloseBracketLine
 *
 * @package Onyx Theme
 */

namespace Onyx\Controllers;

use Onyx\Controller;
use Timber\PostQuery;

class HomeController extends Controller {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		$this->templates        = [ 'pages/index.twig', 'pages/home.twig' ];
		$this->context['posts'] = $this->get_home_posts();
		$this->render_view();
	}

	/**
	 * Get posts from home main loop
	 *
	 * @return object
	 */
	protected function get_home_posts() {
		return new PostQuery([
			'post_type'      => 'post',
			'posts_per_page' => 10,
		]);
	}

}

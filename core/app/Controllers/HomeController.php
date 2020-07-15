<?php
/**
 * Home Controller
 *
 * @package Onyx Theme
 */

namespace Onyx\Controllers;

use Onyx\Controller;
use Timber\PostQuery;

class HomeController extends Controller {

	/**
	 * Initialize
	 */
	public function initialize() {
		$this->set_context( 'posts', $this->get_home_posts() );
		$this->set_templates( [ 'pages/index.twig', 'pages/home.twig' ] );
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

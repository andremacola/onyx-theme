<?php
/**
 * Page Controller
 *
 * @package Onyx Theme
 */

namespace Onyx\Controller;

use Timber\Timber;
use Timber\Post;

class Page_Controller extends Controller {

	/**
	 * Constructor
	 */
	public function __construct() {
		$timber_post     = new Post();
		$context         = Timber::get_context();
		$context['post'] = $timber_post;
		$templates       = [
			'pages/page-' . $timber_post->post_name . '.twig',
			'pages/page.twig',
		];
		Timber::render( $templates, $context );
	}

}

<?php
/**
 * Single Controller
 *
 * @package Onyx Theme
 */

namespace Onyx\Controller;

use Timber\Timber;
use Timber\Post;

class Single_Controller extends Controller {

	/**
	 * Constructor
	 */
	public function __construct() {
		$timber_post     = new Post();
		$context         = Timber::get_context();
		$context['post'] = $timber_post;
		$templates       = [
			'pages/single-' . $timber_post->ID . '.twig',
			'pages/single-' . $timber_post->post_type . '.twig',
			'pages/single-' . $timber_post->slug . '.twig',
			'pages/single.twig',
		];
		Timber::render( $templates, $context );
	}

}

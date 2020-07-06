<?php
/**
 * Home Controller
 *
 * @package Onyx Theme
 */

namespace Onyx\Controller;

use Timber\Timber;
use Timber\PostQuery;

class Home_Controller extends Controller {

	/**
	 * Constructor
	 */
	public function __construct() {
		$args = [
			'post_type'      => 'post',
			'posts_per_page' => 10,
		];

		$context          = Timber::get_context();
		$context['posts'] = new PostQuery( $args );
		Timber::render( 'pages/index.twig', $context );
	}

}

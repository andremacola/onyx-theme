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
		$context         = Timber::get_context();
		$context['post'] = new Post();
		Timber::render( 'pages/single.twig', $context );
	}

}

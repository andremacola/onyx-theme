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
		$context         = Timber::get_context();
		$context['post'] = new Post();
		Timber::render( 'pages/page.twig', $context );
	}

}

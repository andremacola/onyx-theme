<?php
/**
 * Archive Controller
 *
 * @package Onyx Theme
 */

namespace Onyx\Controller;

use Timber\Timber;
use Timber\PostQuery;

class Archive_Controller extends Controller {

	/**
	 * Constructor
	 */
	public function __construct() {
		$context          = Timber::get_context();
		$context['posts'] = new PostQuery();
		Timber::render( 'pages/archive.twig', $context );
	}

}

<?php
/**
 * Error 404 Controller
 *
 * @package Onyx Theme
 */

namespace Onyx\Controller;

use Timber\Timber;

class Error_404_Controller extends Controller {

	/**
	 * Constructor
	 */
	public function __construct() {
		$context = Timber::get_context();
		Timber::render( 'pages/error404.twig', $context );
	}

}

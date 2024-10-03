<?php
/**
 * Onyx Theme autoloader and requirements
 *
 * @package Onyx Theme
 */

/*
|--------------------------------------------------------------------------
| Register Auto Loader
|--------------------------------------------------------------------------
*/

$autoload = __DIR__ . '/core/vendor/autoload.php';
if ( ! file_exists( $autoload ) ) {
	wp_die( 'Please, run <code>composer install</code> inside your theme directory.' );
}

require_once $autoload;

/*
|--------------------------------------------------------------------------
| Require Theme Functions
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/core/includes/hooks-functions.php';

/*
|--------------------------------------------------------------------------
| Initiate Timber
|--------------------------------------------------------------------------
*/

Timber\Timber::init();
Timber\Timber::$dirname = 'views';

/*
|--------------------------------------------------------------------------
| Initiate Theme Setup
|--------------------------------------------------------------------------
*/

new \Onyx\Setup(); // setup theme configuration `after_setup_theme`
new \Onyx\Boot();  // boot controllers routes functionality

// \Onyx\Helpers::clear_cache_timber();

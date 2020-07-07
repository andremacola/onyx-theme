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

require_once __DIR__ . '/core/app/onyx-filters-actions.php';

/*
|--------------------------------------------------------------------------
| Initiate Timber
|--------------------------------------------------------------------------
*/

$timber              = new \Timber\Timber();
$timber::$dirname    = array( 'views', 'twig' );
$timber::$autoescape = false;

/*
|--------------------------------------------------------------------------
| Initiate Theme Setup
|--------------------------------------------------------------------------
*/

new \Onyx\Setup();
new \Onyx\Boot();


/*
|--------------------------------------------------------------------------
| Register REST Api routes
|--------------------------------------------------------------------------
*/

// $api = new \Onyx\MyCustomApi();
// $api->initialize();

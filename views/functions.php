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

$autoload = __DIR__ . '/../core/vendor/autoload.php';
if ( ! file_exists( $autoload ) ) {
	wp_die( 'Please, run <code>composer install</code> inside your theme directory.' );
}

require_once $autoload;

/*
|--------------------------------------------------------------------------
| Require Theme Functions
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../core/app/onyx-filters-actions.php';

/*
|--------------------------------------------------------------------------
| Initiate Theme Setup
|--------------------------------------------------------------------------
*/

new \Onyx\Setup();

/*
|--------------------------------------------------------------------------
| Register REST Api routes
|--------------------------------------------------------------------------
*/

// $api = new \Onyx\MyCustomApi();
// $api->initialize();

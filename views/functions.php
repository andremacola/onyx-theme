<?php

/*
|--------------------------------------------------------------------------
| Register Auto Loader
|--------------------------------------------------------------------------
*/

if (!file_exists($autoload = __DIR__ . '/../core/vendor/autoload.php')) {
	wp_die('Please, run <code>composer install</code> inside your theme directory.');
}

require_once $autoload;

/*
|--------------------------------------------------------------------------
| Require Theme Functions
|--------------------------------------------------------------------------
*/

require_once(__DIR__."/../core/app/onyx-filters-actions.php");

/*
|--------------------------------------------------------------------------
| Initiate Theme Setup
|--------------------------------------------------------------------------
*/

$app = new Onyx_Setup();

?>

<?php
/**
 * Edit this file to enqueue WordPress styles and javascripts
 * Please do not add any type of functions here
 * Onyx do not set javascript dependencies on wp_enqueue functions. All is managed by Gulp
 *
 * @package Onyx Theme
 * @see https://developer.wordpress.org/reference/functions/wp_enqueue_style/
 * @see https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/
 *
 * @param string the file path or url [required]
 * @param bool   Show in home? [required]
 * @param string async|defer for javascript [optional]
 */

return [

	/*
	|--------------------------------------------------------------------------
	| LOAD CSS
	|--------------------------------------------------------------------------
	*/
	'css' => [
		[ 'assets/css/main.css', true ],
		[ 'assets/css/int.css', false ],
	],

	/*
	|--------------------------------------------------------------------------
	| LOAD JAVASCRIPTS
	|--------------------------------------------------------------------------
	*/
	'js'  => [
		[ 'assets/js/app.min.js', true ],
		[ 'assets/js/app-int.min.js', false ],
	],

];

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
 * @param bool   Show only in home? Default false [optional]
 * @param string async|defer for javascript [optional]
 */

return [

	/*
	|--------------------------------------------------------------------------
	| LOAD CSS
	|--------------------------------------------------------------------------
	*/
	'css' => [
		[ 'assets/css/main.css' ],
		[ 'assets/css/home.css', true ],
	],

	/*
	|--------------------------------------------------------------------------
	| LOAD JAVASCRIPTS
	|--------------------------------------------------------------------------
	*/
	'js'  => [
		[ 'assets/js/app.min.js' ],
		[ 'assets/js/home.min.js', true ],
	],

];

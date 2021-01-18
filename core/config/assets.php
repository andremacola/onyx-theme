<?php
/**
 * Edit this file to enqueue WordPress styles and javascripts
 * Please do not add any type of functions here
 * Onyx do not set javascript dependencies on wp_enqueue functions. All is managed by Gulp
 *
 * @package Onyx Theme
 * @see https://developer.wordpress.org/reference/functions/wp_enqueue_style/
 * @see https://developer.wordpress.org/reference/functions/wp_enqueue_script/
 * @see https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/
 *
 * @param array handler $args {
 *      style or scripts params
 *
 *      @type string src [required]
 *      @type boolean $home only in home? [optional]
 *      @type array $deps [optional]
 *      @type string|bool|null $ver [optional]
 *      @type string|bool $media|$in_footer [optional]
 * }
 */

return [

	/*
	|--------------------------------------------------------------------------
	| LOAD CSS
	|--------------------------------------------------------------------------
	*/
	'css' => [
		'main-style' => [ 'assets/css/main.css' ],
		'home-style' => [ 'assets/css/home.css', true ],
	],

	/*
	|--------------------------------------------------------------------------
	| LOAD JAVASCRIPTS
	|--------------------------------------------------------------------------
	*/
	'js'  => [
		'app-script'  => [ 'assets/js/app.min.js', false ],
		'home-script' => [ 'assets/js/home.min.js', true ],
	],

];

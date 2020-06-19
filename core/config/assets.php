<?php 

/**
 * Edit this file to enqueue wordpress styles and javascripts
 * Please do not add any type of functions here
 * Onyx do not set javascript dependencies on wp_enqueue functions. All is managed by Gulp
 *
 * @see https://developer.wordpress.org/reference/functions/wp_enqueue_style/
 * @see https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/
 *
 * @param string      the file path [required]
 * @param bool        Show in home? [required]
 */

return [
	'css' => [
		['assets/css/main.css', true],
		['assets/css/int.css', false]
	],
	'js'  => [
		['assets/js/app.min.js', true],
		['assets/js/app-int.min.js', false]
	],
];

?>

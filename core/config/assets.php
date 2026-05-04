<?php
/**
 * Edit this file to enqueue WordPress styles and javascripts.
 * Please do not add any type of functions here.
 *
 * Source paths are resolved through Vite (`\Onyx\Vite::asset()`) — the dev
 * server URL is used when `npm run dev` is running, otherwise the hashed
 * filename is read from `assets/dist/.vite/manifest.json` (production build).
 *
 * @package Onyx Theme
 * @see https://developer.wordpress.org/reference/functions/wp_enqueue_style/
 * @see https://developer.wordpress.org/reference/functions/wp_enqueue_script/
 * @see https://developer.wordpress.org/reference/hooks/wp_enqueue_scripts/
 *
 * Handler entry shape (positional array values):
 *      [0] string $src        [required] Vite source entry, e.g. `src/sass/style.scss`.
 *      [1] boolean $home      [optional] Only enqueue on the home page.
 *      [2] array $deps        [optional] Script/style dependencies.
 *      [3] mixed $ver         [optional] Ignored — Vite handles cache-busting via filename hash.
 *      [4] string|bool $media [optional] Media (CSS) or in_footer (JS).
 */

return [

	/*
	|--------------------------------------------------------------------------
	| LOAD CSS
	|--------------------------------------------------------------------------
	*/
	'css' => [
		'style' => [ 'src/sass/style.scss' ],
	],

	/*
	|--------------------------------------------------------------------------
	| LOAD JAVASCRIPTS
	|--------------------------------------------------------------------------
	*/
	'js'  => [
		'app' => [ 'src/js/app/app.js' ],
	],

];

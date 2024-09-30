<?php
/**
 * Global Timber Contexts loaded on all pages from controllers
 *
 * @package Onyx
 */

use Onyx\Helpers as O;
use Timber\Timber;

return [
	'theme' => [
		'img' => O::conf( 'env' )->dir_uri . '/assets/images',
		// 'logo' => wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' ),
	],

	// 'menu'  => Timber::get_menu( 'Menu' ),
];

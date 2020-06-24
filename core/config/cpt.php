<?php

/**
 * Edit this file to register your custom post types
 * This file is related to app/class-onyx-cpt.php
 *
 * @see https://developer.wordpress.org/reference/functions/register_post_type/
 * @see https://developer.wordpress.org/reference/functions/get_post_type_labels/
 *
 * @param array names => Post type keys (singular, plural, slug) [required]
 * @param array options => Post type argument options [optional]
 * @param array labels => Post type labels [optional]
 */

return [

	/*
	|--------------------------------------------------------------------------
	| My CPT
	|--------------------------------------------------------------------------
	*/
	[
		'names'   => [
			'name'   => 'Cpt'
		],
		'options' => [],
		'labels'  => []
	],

]

?>

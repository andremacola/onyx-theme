<?php

/**
 * Edit this file to register your custom taxonomies
 * This file is related to app/class-onyx-taxonomy.php
 *
 * @see https://codex.wordpress.org/Function_Reference/register_taxonomy
 *
 * @param array names => Taxonomy keys (singular, plural, slug) [required]
 * @param array types => Related Post types [required]
 * @param array options => Taxonomy argument options [optional]
 * @param array labels => Taxonomy labels [optional]
 */

return [

	/*
	|--------------------------------------------------------------------------
	| My Taxonomy
	|--------------------------------------------------------------------------
	*/
	[
		'names'   => [
			'name'   => 'Tax',
		],
		'types'   => ['cpt'],
		'options' => [],
		'labels'  => []
	],

]

?>

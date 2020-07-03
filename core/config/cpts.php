<?php
/**
 * Edit this file to register your custom post types
 * This file is related to app/class-onyx-cpt.php
 *
 * To sort a custom column, provide an array like ['meta_key', true];
 * The second parameter check if is numerically (true) or alphabetically (false).
 *
 * To populate a custom column, please use a callback function
 * receiving $column and $post_id as a parameters.
 *
 * @package Onyx Theme
 * @see https://developer.wordpress.org/reference/functions/register_post_type/
 * @see https://developer.wordpress.org/reference/functions/get_post_type_labels/
 *
 * @param array names => Post type keys (singular, plural, slug) [required]
 * @param array options => Post type argument options [optional]
 * @param array labels => Post type labels [optional]
 * @param array filters => Post type custom form taxonomy filters. [optional]
 * @param array columns => Post type columns. [add, order, hide] [optional]
 */

return [

	/*
	|--------------------------------------------------------------------------
	| My CPT
	|--------------------------------------------------------------------------
	*/
	[
		'names'   => [
			'name' => 'Cpt',
		],
		'options' => [],
		'labels'  => [],
		'filters' => [ 'taxs' ],
		'columns' => [
			'add'   => [
				'column-1' => [
					'label'    => 'Column 1',
					'sort'     => '',
					'numeric'  => false,
					'populate' => '',
				],
				'column-2' => [
					'label'    => 'Column 2',
					'sort'     => '',
					'numeric'  => true,
					'populate' => '',
				],
			],
			'order' => [
				'title',
				'column-1',
				'column-2',
			],
			'hide'  => [],
		],
	],

];

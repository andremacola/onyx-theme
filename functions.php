<?php
/**
 * Onyx Theme autoloader and requirements
 *
 * @package Onyx Theme
 */

/*
|--------------------------------------------------------------------------
| Register Auto Loader
|--------------------------------------------------------------------------
*/

$autoload = __DIR__ . '/core/vendor/autoload.php';
if ( ! file_exists( $autoload ) ) {
	wp_die( 'Please, run <code>composer install</code> inside your theme directory.' );
}

require_once $autoload;

/*
|--------------------------------------------------------------------------
| Require Theme Functions
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/core/app/onyx-filters-actions.php';

/*
|--------------------------------------------------------------------------
| Initiate Timber
|--------------------------------------------------------------------------
*/

$timber              = new \Timber\Timber();
$timber::$dirname    = array( 'views', 'twig' );
$timber::$autoescape = false;

add_filter(
	'timber/context',
	function( $context ) {
		$context['theme']->uri = \Onyx\O::conf( 'app' )->dir_uri;
		return $context;
	}
);

/*
|--------------------------------------------------------------------------
| Initiate Theme Setup
|--------------------------------------------------------------------------
*/

new \Onyx\Setup();
new \Onyx\Boot();


/*
|--------------------------------------------------------------------------
| Register REST Api routes
|--------------------------------------------------------------------------
*/

// $api = new \Onyx\MyCustomApi();
// $api->initialize();

// PEGAR O NOME DO TEMPLATE PARA TRANSFORMAR EM CLASSE

// add_filter( 'template_include', 'template_include', 100 );
// function template_include( $template ) {
// global $temp;
// var_dump( $temp );

// foreach ( get_templates( $temp ) as $t ) {
// $t = locate_template( "core/controllers/$t" );

// if ( $t ) {
// return $t;
// }
// }

// return $template;
// }


// add_filter( 'index_template_hierarchy', 'template_hierarchy', 10 );
// add_filter( '404_template_hierarchy', 'template_hierarchy', 10 );
// add_filter( 'archive_template_hierarchy', 'template_hierarchy', 10 );
// add_filter( 'author_template_hierarchy', 'template_hierarchy', 10 );
// add_filter( 'category_template_hierarchy', 'template_hierarchy', 10 );
// add_filter( 'tag_template_hierarchy', 'template_hierarchy', 10 );
// add_filter( 'taxonomy_template_hierarchy', 'template_hierarchy', 10 );
// add_filter( 'date_template_hierarchy', 'template_hierarchy', 10 );
// add_filter( 'home_template_hierarchy', 'template_hierarchy', 10 );
// add_filter( 'frontpage_template_hierarchy', 'template_hierarchy', 10 );
// add_filter( 'page_template_hierarchy', 'template_hierarchy', 10 );
// add_filter( 'paged_template_hierarchy', 'template_hierarchy', 10 );
// add_filter( 'search_template_hierarchy', 'template_hierarchy', 10 );
// add_filter( 'single_template_hierarchy', 'template_hierarchy', 10, 1 );
// add_filter( 'singular_template_hierarchy', 'template_hierarchy', 10 );
// add_filter( 'attachment_template_hierarchy', 'template_hierarchy', 10 );

// global $temp;
// $temp = [];

// function template_hierarchy( $files ) {
// global $temp;
// $temp[] = $files;
// return $files;
// }

// function get_templates( $temp = [] ) {
// return call_user_func_array( 'array_merge', $temp );
// }

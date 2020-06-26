<?php
/**
 * App environment variables/settings
 * Self explanatory file :)
 *
 * @package Onyx Theme
 */

return [
	'version' => '1.0',
	'static'  => false,
	'home'    => false,
	'single'  => false,
	'theme'   => explode( '/', get_template() )[0],
	'dir_uri' => str_replace( '/views', '', get_template_directory_uri() ),
	'dir'     => str_replace( '/views', '', get_template_directory() ),
	'desc'    => get_bloginfo( 'description' ),
	'name'    => get_bloginfo( 'name' ),
	'url'     => get_home_url(),
	'user'    => wp_get_current_user()->user_email,
	'devs'    => [ 'dev@domain.tld' ],
];

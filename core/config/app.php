<?php

	// ini_set('xdebug.var_display_max_depth', '10');
	// ini_set('xdebug.var_display_max_children', '256');
	// ini_set('xdebug.var_display_max_data', '1024');

	return (object) [
		'v'            => '1.0',
		'static'       => false,
		'home'         => false,
		'single'       => false,
		'key'          => 'token-de-acesso',
		'dir'          => str_replace('/views', '', get_template_directory_uri()),
		'path'         => str_replace('/views', '', get_template_directory()),
		'description'  => get_bloginfo('description'),
		'name'         => get_bloginfo('name'),
		'url'          => get_home_url(),
		'theme'        => get_option('onyxtheme'),
		'user'         => wp_get_current_user()->user_email,
		'devs'         => ['andremacola@gmail.com']
	];

?>

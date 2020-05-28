<?php
/**
 *
 *   @ Funcionamento dos Widgets
 *	
 */


/* Default Sidebar */
add_action('widgets_init', 'onyx_sidebar');
function onyx_sidebar() {
	register_sidebar(array(
		'name'				=> 'Sidebar',
		'id'					=> 'sidebar',
		'before_widget' 	=> '<div id="%1$s" class="side-section %2$s">',
		'after_widget' 	=> '</div>',
		'before_title' 	=> "<h6 class='side-title'>",
		'after_title' 		=> '</h6>',
	));
}

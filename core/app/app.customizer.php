<?php
/**
 *
 *   @ Funções para personalização do Tema
 *	
 *	SECTIONS:
 *		title_tagline		- Site Identity
 *		colors				- Colors
 *		header_image		- Header Image
 *		background_image()- Background Image
 *		nav_menus			- Navigation
 *		static_front_page	- Static Front Page
 *		custom_css			- Additional CSS
 *
 *	CONTROLS:
 *		WP_Customize_Control()
 *			Type: text, checkbox, radio, select, textarea, dropdown-pages, email, url, number, hidden, and date
 *		WP_Customize_Color_Control()
 *		WP_Customize_Media_Control()
 *		WP_Customize_Upload_Control()
 *		WP_Customize_Image_Control()
 *		WP_Customize_Background_Image_Control()
 *		WP_Customize_Cropped_Image_Control()
 *		WP_Customize_Site_Icon_Control()
 *		WP_Customize_Header_Image_Control()
 *		WP_Customize_Nav_Menu_*_Control (5)()
 *		WP_Customize_Theme_Control()
 *		WP_Widget_Area_Customize_Control()
 *		WP_Widget_Form_Customize_Control()
 *
 */

/* ================================
Variáveis
================================ */

// $onyx_color_fields = array(
// 	'Primeira Cor'			=> 'color1',
// 	'Segunda Cor'			=> 'color2',
// 	'Links'					=> 'color-link',
// 	'Links:hover'			=> 'color-link-hover',
// 	'Post: Títulos'		=> 'color-post-title',
// 	'Post: Links'			=> 'color-post-link',
// 	'Post: Links:hover'	=> 'color-post-link-hover',
// 	'Post: Elementos'		=> 'color-post-elements',
// 	'Site Background'		=> 'color-bg',
// 	'Rodapé'					=> 'color-footer-bg',
// 	'Terceira Cor'			=> 'color3',
// 	'Quarta Cor'			=> 'color4',
// 	'Quinta Cor'			=> 'color5',
// 	'Sexta Cor'				=> 'color6'
// );

/* ================================
Funções de customização
================================ */

add_action('customize_register', 'onyx_theme_customizer');
function onyx_theme_customizer($wp_customize) {

	/* ========================================
	SETTINGS (CONFIG)
	======================================== */

	$onyx_settings = array(
		'type'						=> 'option', // or 'theme_mod'
		'capability'				=> 'edit_theme_options',
		'transport'					=> 'postMessage', // refresh
		'default'					=> '',
		'sanitize_callback'		=> '',
		'sanitize_js_callback'	=> '',
		'theme_supports'			=> ''
	);

	/* ========================================
	PANELS
	======================================== */

	// $wp_customize->add_panel('panels[name]', array(
	// 	'priority'       => 10,
	// 	'capability'     => 'edit_theme_options',
	// 	'title'          => 'Tema',
	// 	'description'    => 'Altere elementos do Tema',
	// 	'theme_supports' => '',
	// ));

	/* ========================================
	SECTIONS
	======================================== */

	// $wp_customize->add_section('sections[name]', array(
	// 	'title'				=> 'Section Name',
	// 	'description'		=> 'Altere elementos',
	// 	'panel'				=> 'panels[name]',
	// 	'priority'			=> 1,
	// 	'capability'		=> 'edit_theme_options',
	// 	'theme_supports'	=> '', // Rarely needed.
	// 	'active_callback'	=> '', // hide section based on currently viewed page
	// ));

	/* ========================================
	CONTROLS (FIELDS)
	======================================== */

	/* customização das cores */
	// foreach ($onyx_color_fields as $label => $color_field) {
	// 	$wp_customize->add_setting("onyxtheme[colors][$color_field]", $onyx_settings);
	// 	$wp_customize->add_control(
	// 		new WP_Customize_Color_Control($wp_customize,
	// 			"$color_field", array(
	// 				"label"		=> $label,
	// 				"settings"	=> "onyxtheme[colors][$color_field]",
	// 				"section"	=> "colors"
	// 			)
	// 		)
	// 	);
	// }

	// $wp_customize->add_setting('onyxtheme[option]', $onyx_settings);
	// $wp_customize->add_control(
	// 	new WP_Customize_Control($wp_customize,
	// 		'control_name', array(
	// 			'label'		=> 'Control Name',
	// 			'settings'	=> 'onyxtheme[option]',
	// 			'section'	=> 'sections[name]',
	// 			'type'		=> 'textarea'
	// 		)
	// 	)
	// );

}

/* ================================
Carregar javascript de customização
================================ */

add_action('customize_preview_init', 'onyx_customizer');
function onyx_customizer() {
	global $app;
	wp_enqueue_script(
		'onyx_customizer',
		$app->dir . '/src/js/onyx-customizer.js',
		array( 'jquery','customize-preview' ),
		'',
		true
	);
}

/* ================================
Carregar JAVASCRIPT de customização
================================ */

add_action('customize_preview_init', 'onyx_customizer_preview_js');
function onyx_customizer_preview_js() {
	global $app, $onyx_color_fields;
	wp_enqueue_script(
		'onyx_customizer',
		$app->dir . '/src/js/onyx-customizer.js',
		array( 'jquery','customize-preview' ),
		'',
		true
	);
	wp_localize_script('onyx_customizer', 'onyxThemeColors', $onyx_color_fields);
}

/* ================================
Carregar CSS de customização
================================ */

add_action('wp_head', 'onyx_customizer_css');
function onyx_customizer_css() {
	global $app;
	if (!onyx_amp()) {
		/* carregar variáveis de CSS */
		if (!empty($app->theme['colors'])) {
			echo "<style> :root { ";
				foreach ($app->theme['colors'] as $key => $value) {
					if ($value) echo "--$key: $value;";
				}
			echo " } </style> ";
		}
	}
}

?>

<?php
/**
 * 
 * Funções que alteram a funcionalidade e a interface do wp-admin
 * 
 */

/* personalização de css da tela de login e admin */
add_action('login_enqueue_scripts', 'onyx_admin_scripts');
add_action('admin_enqueue_scripts', 'onyx_admin_scripts');
function onyx_admin_scripts() {
	global $app;
	echo "<link rel='shortcut icon' href='$app->dir/assets/images/icons/favicon-32.png' />";
	onyx_css('styles.admin.css');
}

/* frase rodapé do wp-admin */
add_filter('admin_footer_text', 'onyx_change_footer_admin');
function onyx_change_footer_admin () {
	global $app;
	echo $app->name;
}

/* customizar barra do wordpress admin */
add_action('wp_before_admin_bar_render', 'onyx_admin_bar');
function onyx_admin_bar() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('wp-logo');
	$wp_admin_bar->remove_menu('comments');
}

/* clean dashboard */
add_action('wp_dashboard_setup', 'onyx_dashboard_widgets');
function onyx_dashboard_widgets() {
	global $wp_meta_boxes;
	remove_action('welcome_panel', 'wp_welcome_panel');
	unset($wp_meta_boxes['dashboard']['normal']['high']['dashboard_browser_nag']); // browse happy
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);	 // rascunho rápido
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);		 // wordpress.com
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);		 // wordpress news
}

/* dashboard com 1 ou 2 colunas (padrão: Forçar 1 coluna/full-width) */
add_filter('get_user_option_screen_layout_dashboard', 'onyx_fix_columns');
add_action('admin_head-index.php', 'onyx_dashboard_width');
function onyx_dashboard_width() {
	echo "
	<style>
		.postbox-container {min-width: 100% !important;}
		.meta-box-sortables.ui-sortable.empty-container { display: none;}
	</style>";
}
function onyx_fix_columns() { return 1; }

/* desabilitar comentários e trackbacks */
add_action('admin_menu', 'onyx_disable_comments_page');
function onyx_disable_comments_page() {
	global $pagenow;
	$post_types = get_post_types();
	remove_menu_page('edit-comments.php');
	if ($pagenow === 'edit-comments.php') {
		wp_redirect(admin_url()); exit;
	}
	foreach ($post_types as $post_type) {
		if (post_type_supports($post_type, 'comments')) {
			remove_post_type_support($post_type, 'comments');
			remove_post_type_support($post_type, 'trackbacks');
		}
	}
}

/* Adiciona categorias em Mídia */
add_action('admin_init', 'onyx_enable_midia_cat');
function onyx_enable_midia_cat() {
	register_taxonomy_for_object_type('category', 'attachment');
	add_post_type_support('attachment', 'category');
}

/* alterar padrão do link da imagem/galeria no post*/
/* VERIFICAR: funcionamento e gutenberg */
add_action('admin_init', 'onyx_default_imagelink', 10);
add_filter('media_view_settings', 'onyx_default_gallerylink');
function onyx_default_imagelink() {
	$image_set = get_option('image_default_link_type');
	if ($image_set !== 'none') {
		update_option('image_default_link_type', 'none');
	}
}
function onyx_default_gallerylink($settings) {
	$settings['galleryDefaults']['link'] = 'file';
	$settings['galleryDefaults']['columns'] = '4';
	return $settings;
}

/* altera o padrão da URL ao inserir uma imagem no editor para RELATIVE (facilita na migração dev<->prod) */
/* VERIFICAR: funcionamento e gutenberg */
/* https://github.com/danielbachhuber/gutenberg-migration-guide/blob/master/filter-image-send-to-editor.md */
add_filter('image_send_to_editor','onyx_image_relative_url',5,8);
function onyx_image_relative_url($html, $id, $caption, $title, $align, $url, $size, $alt) {
	$sp = strpos($html,"src=") + 5;
	$ep = strpos($html,"\"",$sp);
	
	$imageurl = substr($html,$sp,$ep-$sp);
	
	$relativeurl = str_replace("http://","",$imageurl);
	$sp = strpos($relativeurl,"/");
	$relativeurl = substr($relativeurl,$sp);
	
	$html = str_replace($imageurl,$relativeurl,$html);
	
	return $html;
}

/* adicionar botão de nextpage/pagebreak no wordpress */
/* VERIFICAR: funcionamento e gutenberg */
add_filter('mce_buttons', 'onyx_editor_page_break');
function onyx_editor_page_break($mce_buttons) {
	$pos = array_search('wp_more', $mce_buttons, true);
	if ($pos !== false) {
		$buttons = array_slice($mce_buttons, 0, $pos + 1);
		$buttons[] = 'wp_page';
		$mce_buttons = array_merge($buttons, array_slice($mce_buttons, $pos + 1));
	}
	return $mce_buttons;
}

/* filtros para upload */
add_filter('upload_mimes','onyx_remove_mime_types');
add_filter('upload_size_limit', 'onyx_upload_limit');
function onyx_remove_mime_types($existing_mimes=array()){
	unset($existing_mimes['mp4|m4v']);
	unset($existing_mimes['mov|qt']);
	unset($existing_mimes['wmv']);
	unset($existing_mimes['avi']);
	unset($existing_mimes['mpeg|mpg|mpe']);
	unset($existing_mimes['3gp|3gpp']);
	unset($existing_mimes['3g2|3gp2']);
	unset($existing_mimes['asf|asx']);
	unset($existing_mimes['wmx']);
	unset($existing_mimes['wm']);
	unset($existing_mimes['divx']);
	unset($existing_mimes['flv']);
	unset($existing_mimes['ogv']);
	unset($existing_mimes['webm']);
	unset($existing_mimes['mkv']);
	return $existing_mimes;
}
function onyx_upload_limit() {
    return 5000 * 1024;
}

/* remover campos desnecessários do perfil do usuário */
add_filter('user_contactmethods' , 'onyx_user_contactmethods' , 10 , 1);
function onyx_user_contactmethods($contactmethods) {
	// Adicionar campos de contato
	// $contactmethods['twitter'] = 'Twitter ID';
	// $contactmethods['facebook'] = 'Facebook ID';

	// Remover campos de contato
	unset($contactmethods['aim']);
	unset($contactmethods['yim']);
	unset($contactmethods['jabber']);

	return $contactmethods;
}

/* ================================
@ GUTENBERG
================================ */

/* filtros e funcionalidades */
add_action('admin_init', 'onyx_gutenberg_css');
function onyx_gutenberg_css() {
	add_theme_support('editor-styles');
	add_editor_style('../assets/css/styles.editor.css');
}

/* adicionar script para personalização do gutenberg */
add_action('enqueue_block_editor_assets', 'onyx_gutenberg_js');
function onyx_gutenberg_js() {
	global $app;
	wp_enqueue_script(
		'onyx-gutenberg',
		$app->dir.'/src/js/admin/gutenberg.js',
		array('wp-blocks', 'wp-dom-ready', 'wp-edit-post'),
		$app->v
	);
}

?>

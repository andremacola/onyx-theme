<?php
/**
 * 
 * Customizações da REST API do Wordpress
 * 
 */

/* ===============================
CONFIGURAÇÕES
================================ */

/* alterar url base da api do wordpress para api/wp/v2 */
add_filter('rest_url_prefix', function() {
	return 'api';
});

/* ================================
FILTROS / ACTIONS
================================ */

/* limitar acesso api */
// add_filter('rest_authentication_errors', function($result) {
// 	global $app;
// 	if (!empty($result)) {
// 		return $result;
// 	}
// 	if (isset($_REQUEST['key']) && $_REQUEST['key'] === $app->key) {
// 		return $result;
// 	}
// 	if (!is_user_logged_in()) {
// 		return new WP_Error('unauthorized', 'Acesso não autorizado.', array('status' => 401));
// 	}
// 	return $result;
// });

/* bloquear user enumeration via wp rest api */
// add_filter('rest_endpoints', function($endpoints){
// 	if (!is_user_logged_in()) {	
// 		if (isset($endpoints['/wp/v2/users'])) {
// 			unset($endpoints['/wp/v2/users']);
// 		}
// 		if (isset($endpoints['/wp/v2/users/(?P<id>[\d]+)'])) {
// 			unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
// 		}
// 	}
// 	return $endpoints;
// });

/* ================================
REGISTRAR ROTAS
================================ */

// $cron_controller = new OnyxCron();
// add_action('rest_api_init', array($cron_controller, 'register_routes'));

?>

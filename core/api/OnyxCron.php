<?php
/**
 * 
 * Arquivo para funcionamento dos CRONS
 * /api/ocron/fixo/?key=token-de-acesso
 * 
 */

class OnyxCron extends WP_REST_Controller {

	protected $namespace;
	protected $cronroute;

	/**
	* Constructor
	*/
	public function __construct() {
		$this->namespace		= 'ocron';
		$this->cronroute		= 'someroute';
	}

	/**
	* Registrar rotas personalizadas do Cron
	*/
	public function register_routes() {
		// registrar rota de....
		register_rest_route($this->namespace, '/' . $this->fixoroute, array(
			'methods'	=> WP_REST_Server::READABLE, // GET
			'callback'	=> array($this, 'onyx_cron_func')
		));
	}

	/**
	* Função de execução do cron
	*/
	public function onyx_cron_func($req) {
		$header_code = ($req) ? 200 : 204;
		return new WP_REST_Response($req, $header_code);
	}

}

?>

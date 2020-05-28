<?php
/**
 * Carregar funções do core da aplicação e funcionamentos do tema do Wordpress
 * 
 * Autor: André Mácola Machado 
 * Versão: 3.1
 */


/* ================================
Autoload
================================ */

require_once(__DIR__ . '/vendor/autoload.php');

/* ================================
Core e Helpers
================================ */

// arquivo de configuração
require_once(__DIR__ . '/app/conf.app.php');

// funções para retornar tipos de conteúdo
require_once(__DIR__ . '/app/conf.rest.php');

// funções que alteram o wp-admin
require_once(__DIR__ . '/app/app.admin.php');

// filtros para o funcionamento do wordpress
require_once(__DIR__ . '/app/app.filters.php');

// funções para retornar tipos de conteúdo
require_once(__DIR__ . '/app/app.helpers.php');

// queries do tema
require_once(__DIR__ . '/app/app.queries.php');

// funções específicas do tema
require_once(__DIR__ . '/app.php');

// funcionamento/criação de post types
require_once(__DIR__ . '/types/types.php');

// funções para funcionamento das áreas dos widgets
require_once(__DIR__ . '/app/app.widgets.php');

// funções para personalização do Tema
// require_once(__DIR__ . '/app/app.customizer.php');

// funções e tamanhos para thumbnails
// require_once(__DIR__ . '/app/thumbs.php');

// funcionamento/criação das taxonomies
// require_once(__DIR__ . '/taxonomies/taxonomies.php');
?>

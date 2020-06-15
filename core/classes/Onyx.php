<?php

Class Onyx {


	/**
	* Mostrar menu de navegação
	* @param string $menu nome do menu required
	*/
	static function menu($menu = null) {
		wp_nav_menu(array('menu' => $menu, 'container' => '', 'items_wrap' => '%3$s'));
	}

		
}

?>

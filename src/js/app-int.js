/**
	@ Funcões diversas das internas do tema
 */

(function($, window, document) {
	$(function() {
		// função para adicionar modo fancybox galeria nas imagens
		$('.wp-block-gallery a').attr('data-fancybox', 'gallery');
		// função para habilitar fancybox em link de imagens
		$('img[class*=wp-image]').parent('a').fancybox({});
		// $(".wp-caption a, .gallery-icon a, .modal").fancybox({
		// 	// Options will go here
		// });
	});
}(window.jQuery, window, document));

/**
	@ PREPEND FRAMEWORKS E BIBLIOTECAS
 */

//=require "jquery/dist/jquery.min.js"
// require "jquery-mask-plugin/dist/jquery.mask.min.js"
// require "@fancyapps/fancybox/dist/jquery.fancybox.min.js"
// require "tiny-slider/dist/min/tiny-slider.js"
// require "./plugins/dfp.js"

/**
	@ Funcões diversas do tema
 */

(function($, window, document) {
	$(function() {
		// ios ::active hack
		document.addEventListener('touchstart', function() {}, true);
		// dfpLoadBanners();
	});
}(window.jQuery, window, document));

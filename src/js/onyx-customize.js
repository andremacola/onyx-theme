/**
	@ Funcões para visualização em tempo real da costumização
 */

(function($, window, document) {
	$(function() {
		// iniciar preview colors
		onyxPreviewColors();
	});
}(window.jQuery, window, document));

/* função para funcionamento de visualizacão das cores no preview */
function onyxPreviewColors() {
	var root = document.documentElement;
	var colors = onyxThemeColors;

	Object.keys(colors).forEach(function(key) {
		console.log(colors[ key ]);
		wp.customize('onyxtheme[colors][' + colors[ key ] + ']', function(value) {
			value.bind(function(newval) {
				newval = newval.length > 0 ? newval : null;
				root.style.setProperty(String('--' + colors[ key ]), newval);
			});
		});
	});
}

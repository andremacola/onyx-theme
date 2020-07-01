/**
 * IMPORT FRAMEWORKS AND LIBRARIES
 */

//=require "jquery/dist/jquery.min.js"
// require "jquery-mask-plugin/dist/jquery.mask.min.js"
// require "@fancyapps/fancybox/dist/jquery.fancybox.min.js"
// require "tiny-slider/dist/min/tiny-slider.js"
// require "./plugins/dfp.js"

/**
 * APP FUNCTIONS
 */

(function($, window, document) {
	$(function() {
		// ios ::active hack
		document.addEventListener('touchstart', function() {}, true);
	});
}(window.jQuery, window, document));

/**
 * IMPORT FRAMEWORKS AND LIBRARIES
 */

//=require "jquery/dist/jquery.min.js"
// require "jquery-mask-plugin/dist/jquery.mask.min.js"
// require "@fancyapps/fancybox/dist/jquery.fancybox.min.js"
// require "tiny-slider/dist/min/tiny-slider.js"
// require "./plugins/dfp.js"

// const jquery = require('jquery');
// const appTeste = require('./app-int');
// const jquery = require('../../node_modules/jquery/dist/jquery.min.js');
// import jquery from '../../node_modules/jquery/dist/jquery.min.js';

import jquery from 'jquery';
// import './app-int';

window.$ = window.jQuery = jquery;

// import { tns } from 'tiny-slider';

// const { tns } = require('tiny-slider');

/**
 * APP FUNCTIONS
 */

(function($, window, document) {
	$(function() {
		// ios ::active hack
		document.addEventListener('touchstart', function() {}, true);

		$('body').css('background', 'green');
	});
}(window.jQuery, window, document));

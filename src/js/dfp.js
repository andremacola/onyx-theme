/**
	@ DFP
 */

// var googletag = googletag || {};
// googletag.cmd = googletag.cmd || [];
// (function() {
// 	var gads = document.createElement('script');
// 	gads.async = true;
// 	gads.type = 'text/javascript';
// 	var useSSL = 'https:' == document.location.protocol;
// 	gads.src = (useSSL ? 'https:' : 'http:') +
// 	'//www.googletagservices.com/tag/js/gpt.js';
// 	var node = document.getElementsByTagName('script')[ 0 ];
// 	node.parentNode.insertBefore(gads, node);
// }());

// var DefaultBannerSize = [[320, 50]];

// googletag.cmd.push(function() {
// 	var mappingFull = googletag.sizeMapping()
// 		.addSize([320, 200], [320, 50])
// 		.addSize([768, 200], [728, 90])
// 		.addSize([990, 200], [[970, 90]])
// 		.addSize([1200, 200], [[1200, 500], [1200, 110], [1170, 110], [970, 250]])
// 		.build();

// 	var mappingCentral = googletag.sizeMapping()
// 		.addSize([319, 200], [300, 250])
// 		.addSize([767, 200], [728, 90])
// 		.addSize([990, 200], [[970, 250], [970, 90]])
// 		.addSize([1199, 200], [[970, 250], [1200, 110], [1170, 110], [970, 90]])
// 		.build();

// 	var mappingSidebar = googletag.sizeMapping()
// 		.addSize([319, 200], [300, 250])
// 		.addSize([990, 200], [[300, 600]])
// 		.build();

// 	var adSlot0 = googletag.defineSlot('/9754072/90anos_topo_fullbanner', DefaultBannerSize, 'div-gpt-ad-1462756043225-1').defineSizeMapping(mappingFull).addService(googletag.pubads());
// 	var adSlot1 = googletag.defineSlot('/9754072/90anos_central_fullbanner', DefaultBannerSize, 'div-gpt-ad-1462756043225-0').defineSizeMapping(mappingCentral).addService(googletag.pubads());
// 	var adSlot3 = googletag.defineSlot('/9754072/90anos_sidebar_1_300', [[300, 600]], 'div-gpt-ad-1462754018182-0').defineSizeMapping(mappingSidebar).addService(googletag.pubads());
// 	var adSlot4 = googletag.defineSlot('/9754072/90anos_sidebar_2_300', [[300, 250], [300, 300], [300, 600]], 'div-gpt-ad-1462754018182-2').addService(googletag.pubads());
// 	var adSlot5 = googletag.defineSlot('/9754072/90anos_rodape_fullbanner', DefaultBannerSize, 'div-gpt-ad-1485373371286-0').defineSizeMapping(mappingCentral).addService(googletag.pubads());
// 	var adSlot6 = googletag.defineSlot('/9754072/90anos_conteudo', [300, 250], 'div-gpt-ad-1500279621206-0').addService(googletag.pubads());

// 	googletag.pubads().enableSingleRequest();
// 	googletag.pubads().collapseEmptyDivs();
// 	// googletag.pubads().addEventListener('slotRenderEnded', function(event) {});
// 	googletag.enableServices();
// });

// //
// // DFP INTERSTITIAL (FLUTUANTE)
// //
// googletag.cmd.push(function() {
// 	var adSlot7 = googletag.defineOutOfPageSlot('/9754072/90anos_flutuante', 'div-gpt-ad-1468345935839-0').addService(googletag.pubads());

// 	googletag.pubads().enableSingleRequest();
// 	googletag.pubads().collapseEmptyDivs();
// 	googletag.pubads().enableSyncRendering();
// 	googletag.enableServices();
// });

// // var resizeTimer;
// // function resizer() {
// // 	googletag.pubads().refresh();
// // }
// // window.addEventListener("resize", function(){
// // 	clearTimeout(resizeTimer);
// // 	resizeTimer = setTimeout(resizer, 250);
// // 	// console.log('refresh ads');
// // });

// function dfpLoadBanners() {
// 	// fullbanner topo
// 	googletag.cmd.push(function() {
// 		googletag.display('div-gpt-ad-1462756043225-1');
// 	});
// 	// fullbanner central
// 	googletag.cmd.push(function() {
// 		googletag.display('div-gpt-ad-1462756043225-0');
// 	});
// 	// sidebar 1
// 	googletag.cmd.push(function() {
// 		googletag.display('div-gpt-ad-1462754018182-0');
// 	});
// 	// sidebar 2
// 	googletag.cmd.push(function() {
// 		googletag.display('div-gpt-ad-1462754018182-2');
// 	});
// 	// fullbanner rodape
// 	googletag.cmd.push(function() {
// 		googletag.display('div-gpt-ad-1485373371286-0');
// 	});
// 	// flutuante
// 	googletag.cmd.push(function() {
// 		googletag.display('div-gpt-ad-1468345935839-0');
// 	});
// 	// conteudo
// 	googletag.cmd.push(function() {
// 		googletag.display('div-gpt-ad-1500279621206-0');
// 	});
// }

// function dfpRefreshBanners() {
// 	window.googletag.cmd.push(function() {
// 		googletag.pubads().refresh();
// 	});
// }

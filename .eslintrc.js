'use strict';

module.exports = {
	"extends": "plugin:@wordpress/eslint-plugin/esnext",
	"env": {
		"node": true,
		"amd": true,
		"browser": true,
		"es6": true
	},
	"globals": {
		// wp
		wp: true,
		// framework
		onyx: true,
		onyxLoadBanners: true,
		onyxIgnorePosts: true,
		onyxPostID: true,
		onyxPostType: true,
		onyxViewsCount: true,
		onyxThemeColors: true,
		// dependencies
		$: true,
		jQuery: true,
		ga: true,
		googletag: true,
		objectFitImages: true,
		LazyLoad: true,
		bodyScrollLock: true,
		isMobile: true
	},
	"rules": {
		"quotes": [
			'error',
			'single',
			{ allowTemplateLiterals: true },
		],
		"no-var": 0,
		"no-console": 0,
		"eqeqeq": 0,
		"space-in-parens": ["warn", "never"],
		"template-curly-spacing": ["warn", "never"],
		"computed-property-spacing": ["warn", "never"]
	}
}

'use strict';

require('dotenv/config');

const gulp = require('gulp');
const gulpif = require('gulp-if');
const rename = require('gulp-rename');
const source = require('vinyl-source-stream');

const liveReload = require('gulp-livereload');
const browserSync = require('browser-sync').create();

const autoprefixer = require('gulp-autoprefixer');
const purgecss = require('gulp-purgecss');
const sass = require('gulp-sass');
sass.compiler = require('node-sass');

const rollup = require('@rollup/stream');
const commonjs = require('@rollup/plugin-commonjs');
const { nodeResolve } = require('@rollup/plugin-node-resolve');
const { terser } = require('rollup-plugin-terser');

const read = require('fs').readFileSync;

/*
|--------------------------------------------------------------------------
| CONFIGURATION VARIABLES
|--------------------------------------------------------------------------
*/

const isProd = (process.env.NODE_ENV === 'prod');

const config = {
	liveReload: (process.env.LIVERELOAD == 'true'),
	url: process.env.URL,
	port: parseInt(process.env.PORT, 10),
	ui_port: parseInt(process.env.UI_PORT, 10),

	key: (process.env.KEY) ? process.env.KEY : false,
	cert: (process.env.CRT) ? process.env.CRT : false,
	https() {
		return (this.key) ? { key: this.key, cert: this.cert	} : false;
	},

	cssout: isProd ? 'compressed' : 'expanded',
	prefixer: isProd,
	terser: isProd,

	style: './src/sass/styles.scss',
	styleDest: './assets/css',
	jmq: isProd,

	js: './src/js/app.js',
	jsDest: './assets/js',
};

const wpCSS = {
	whitelist: [
		'rtl',
		'home',
		'blog',
		'archive',
		'date',
		'error404',
		'logged-in',
		'admin-bar',
		'no-customize-support',
		'custom-background',
		'wp-custom-logo',
		'alignnone',
		'alignright',
		'alignleft',
		'wp-caption',
		'wp-caption-text',
		'screen-reader-text',
		/^hide-(.*)?$/,
		/^gm-(.*)?$/,
		/^lg-(.*)?$/,
		/^tns-(.*)?$/,
		/^wp-block(-.*)?$/,
		/^active(-.*)?$/,
		/^search(-.*)?$/,
		/^(.*)-template(-.*)?$/,
		/^(.*)?-?single(-.*)?$/,
		/^postid-(.*)?$/,
		/^attachmentid-(.*)?$/,
		/^attachment(-.*)?$/,
		/^page(-.*)?$/,
		/^(post-type-)?archive(-.*)?$/,
		/^author(-.*)?$/,
		/^category(-.*)?$/,
		/^tag(-.*)?$/,
		/^tax-(.*)?$/,
		/^term-(.*)?$/,
		/^(.*)?-?paged(-.*)?$/,
		/^comments-(.*)?$/,
		/^comment-(.*)?$/,
	],
};

const onyx = {
	watch() {
		return (config.liveReload) ? startLiveReload() : startBrowserSync();
	},
	stream() {
		return (config.liveReload) ? liveReload() : browserSync.stream();
	},
	reload(file) {
		return (config.liveReload) ? liveReload.reload(file) : browserSync.reload;
	},
	prefixer() {
		return autoprefixer({ cascade: false });
	},
};

/*
|--------------------------------------------------------------------------
| STYLES
|--------------------------------------------------------------------------
*/

function styles() {
	return gulp
		.src(config.style)
		.pipe(sass({
			outputStyle: config.cssout,
			includePaths: [ './node_modules/' ],
		}).on('error', sass.logError))
		.pipe(rename('main.css'))
		.pipe(onyx.stream())
		.pipe(gulpif(config.prefixer, onyx.prefixer()))
		.pipe(gulp.dest(config.styleDest));
}

function stylesPurge() {
	return gulp
		.src([ 'assets/css/main.css' ])
		.pipe(
			purgecss({
				content: [ './comments.php', 'core/**/*.php', 'templates/**/*.php', 'views/**/*.twig', 'views/**/*.php', 'src/js/**/*.js' ],
				safelist: wpCSS.whitelist,
			})
		)
		.pipe(gulp.dest(config.styleDest));
}

/*
|--------------------------------------------------------------------------
| JAVASCRIPTS
|--------------------------------------------------------------------------
*/

let cache;
function js() {
	const options = {
		input: config.js,
		output: {
			file: config.jsDest,
			format: 'cjs',
		},
		plugins: [
			commonjs(),
			nodeResolve(),
			config.terser && terser({ output: { comments: false } }),
		],
		cache,
	};
	return rollup(options)
		.on('bundle', (bundle) => {
			cache = bundle;
		})
		.pipe(source('app.min.js'))
		.pipe(gulp.dest(config.jsDest))
		.pipe(onyx.stream());
}

/*
|--------------------------------------------------------------------------
| BROWSERSYNC
|--------------------------------------------------------------------------
*/

// eslint-disable-next-line no-unused-vars
function startBrowserSync() {
	browserSync.init({
		proxy: {
			target: config.url,
		},
		host: config.url.split('//')[1],
		port: config.port,
		https: config.https(),
		ui: {
			port: config.ui_port,
		},
		open: false,
		notify: false,
		injectChanges: true,
		logConnections: false,
		logFileChanges: false,
		logSnippet: false,
		ghostMode: {
			clicks: false,
			forms: false,
			scroll: false,
		},
	});
}

/*
|--------------------------------------------------------------------------
| LIVE RELOAD
|--------------------------------------------------------------------------
*/

// eslint-disable-next-line no-unused-vars
function startLiveReload() {
	liveReload.listen({
		port: config.port,
		key: read(config.key),
		cert: read(config.cert),
	});
}

/*
|--------------------------------------------------------------------------
| WATCH FUNCTIONS
|--------------------------------------------------------------------------
*/

function serve() {
	onyx.watch();
	gulp.watch([ './src/sass/**/*.scss' ], styles);
	gulp.watch([ './src/js/**/*.js' ], js);
	gulp.watch([ 'core/**/*.php', 'templates/**/*.php', 'views/**/*.php', 'views/**/*.twig' ]).on('change', (file) => onyx.reload(file));
}

/*
|--------------------------------------------------------------------------
| EXPORTS
|--------------------------------------------------------------------------
*/

exports.styles = styles;
exports.stylesPurge = stylesPurge;
exports.js = js;
exports.serve = serve;
exports.default = gulp.series(styles, stylesPurge, js);

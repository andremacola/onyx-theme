'use strict';

require('dotenv/config');

const gulp = require('gulp');
const gulpif = require('gulp-if');
const rename = require('gulp-rename');
const source = require('vinyl-source-stream');

const browserSync = require('browser-sync').create();

const autoprefixer = require('gulp-autoprefixer');
const purgecss = require('gulp-purgecss');
const sass = require('gulp-sass');
sass.compiler = require('node-sass');

const rollup = require('@rollup/stream');
const commonjs = require('@rollup/plugin-commonjs');
const { nodeResolve } = require('@rollup/plugin-node-resolve');
const { terser } = require('rollup-plugin-terser');

/*
|--------------------------------------------------------------------------
| CONFIGURATION VARIABLES
|--------------------------------------------------------------------------
*/

const config = {
	url: process.env.URL,
	port: parseInt(process.env.PORT, 10),
	ui_port: parseInt(process.env.UI_PORT, 10),
	https: false,
	cssout: 'expanded',
	prefixer: false,
	terser: false,

	style: './src/sass/styles.scss',
	styleDest: './assets/css',

	js: './src/js/app.js',
	jsDest: './assets/js',
};

if (process.env.KEY) {
	config.https = {
		key: process.env.KEY,
		cert: process.env.CRT,
	};
}

if (process.env.NODE_ENV === 'prod') {
	config.cssout = 'compressed';
	config.prefixer = true;
	config.terser = true;
}

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
		'comment-list',
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
	],
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
		.pipe(browserSync.stream())
		.pipe(
			gulpif(
				config.prefixer,
				autoprefixer({
					cascade: false,
				})
			)
		)
		.pipe(gulp.dest(config.styleDest));
}

function stylesPurge() {
	return gulp
		.src([ 'assets/css/main.css', 'assets/css/home.css' ])
		.pipe(
			purgecss({
				content: [ 'core/**/*.php', 'templates/**/*.php', 'views/**/*.twig', 'views/**/*.php', 'src/js/**/*.js' ],
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
		// .pipe(buffer())
		.pipe(gulp.dest(config.jsDest))
		.pipe(browserSync.stream());
}

/*
|--------------------------------------------------------------------------
| WATCH FUNCTIONS
|--------------------------------------------------------------------------
*/

function watch() {
	browserSync.init({
		proxy: {
			target: config.url,
		},
		port: config.port,
		https: config.https,
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

	gulp.watch([ './src/sass/**/*.scss', '!./src/sass/**/home.scss' ], styles);
	gulp.watch([ './src/js/**/*.js', '!./src/js/**/home.js' ], js);
	gulp.watch([ 'core/**/*.php', 'templates/**/*.php', 'views/**/*.php', 'views/**/*.twig' ]).on('change', browserSync.reload);
}

/*
|--------------------------------------------------------------------------
| EXPORTS
|--------------------------------------------------------------------------
*/

exports.styles = styles;
exports.stylesPurge = stylesPurge;
exports.js = js;
exports.watch = watch;
exports.default = gulp.series(styles, stylesPurge, js);

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
const sass = require('@selfisekai/gulp-sass');
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
	livereload: (process.env.LIVERELOAD == 'true'),
	url: process.env.URL,
	port: parseInt(process.env.PORT, 10),
	portui: parseInt(process.env.PORT_UI, 10),

	key: (process.env.KEY) ? process.env.KEY : false,
	cert: (process.env.CRT) ? process.env.CRT : false,
	https() {
		return (this.key) ? { key: this.key, cert: this.cert	} : false;
	},

	cssout: isProd ? 'compressed' : 'expanded',
	prefixer: isProd,
	terser: isProd,

	js: {
		source: './src/js/app.js',
		output: 'app.min.js',
		dest: './assets/js',
	},

	styles: {
		source: './src/sass/style.scss',
		output: 'style.css',
		dest: './assets/css',
	},

	purgecss: {
		content: [
			'./comments.php',
			'core/**/*.php',
			'templates/**/*.php',
			'views/**/*.twig',
			'views/**/*.php',
			'src/js/**/*.js',
		],
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
	},

	watch: {
		js: [ './src/js/**/*.js' ],
		styles: [ './src/sass/**/*.scss' ],
		views: [ 'core/**/*.php', 'templates/**/*.php', 'views/**/*.php', 'views/**/*.twig' ],
	},
};

const onyx = {
	watch() {
		return (config.livereload) ? startLiveReload() : startBrowserSync();
	},
	stream() {
		return (config.livereload) ? liveReload() : browserSync.stream();
	},
	reload(file) {
		return (config.livereload) ? liveReload.reload(file) : browserSync.reload;
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
		.src(config.styles.source)
		.pipe(sass({
			outputStyle: config.cssout,
			includePaths: [ './node_modules/' ],
		}).on('error', sass.logError))
		.pipe(rename(config.styles.output))
		.pipe(onyx.stream())
		.pipe(gulpif(config.prefixer, onyx.prefixer()))
		.pipe(gulp.dest(config.styles.dest));
}

function stylesPurge() {
	return gulp
		.src([ config.styles.dest + '/' + config.styles.output ])
		.pipe(
			purgecss({
				content: config.purgecss.content,
				safelist: config.purgecss.whitelist,
			})
		)
		.pipe(gulp.dest(config.styles.dest));
}

/*
|--------------------------------------------------------------------------
| JAVASCRIPTS
|--------------------------------------------------------------------------
*/

let cache;
function js() {
	const options = {
		input: config.js.source,
		output: {
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
		.pipe(source(config.js.output))
		.pipe(gulp.dest(config.js.dest))
		.pipe(onyx.stream());
}

/*
|--------------------------------------------------------------------------
| BROWSERSYNC
|--------------------------------------------------------------------------
*/

function startBrowserSync() {
	browserSync.init({
		proxy: {
			target: config.url,
		},
		host: config.url.split('//')[1],
		port: config.port,
		https: config.https(),
		ui: {
			port: config.portui,
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

function startLiveReload() {
	liveReload.listen({
		port: config.port,
		key: (config.key) ? read(config.key) : false,
		cert: (config.cert) ? read(config.cert) : false,
	});
}

/*
|--------------------------------------------------------------------------
| WATCH FUNCTIONS
|--------------------------------------------------------------------------
*/

function serve() {
	onyx.watch();
	gulp.watch(config.watch.js, js);
	gulp.watch(config.watch.styles, styles);
	gulp.watch(config.watch.views).on('change', (file) => onyx.reload(file));
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

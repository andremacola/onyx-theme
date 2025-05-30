import 'dotenv/config';

import gulp from 'gulp';
import gulpif from 'gulp-if';
import rename from 'gulp-rename';
import source from 'vinyl-source-stream';

import liveReload from 'gulp-livereload';

import autoprefixer from 'gulp-autoprefixer';
import purgecss from 'gulp-purgecss';
import * as sassCompiler from 'sass';
import gulpSass from 'gulp-sass';
import px2rem from 'gulp-px2rem';

import rollup from '@rollup/stream';
import commonjs from '@rollup/plugin-commonjs';
import { nodeResolve } from '@rollup/plugin-node-resolve';
import terser from '@rollup/plugin-terser';

import { readFileSync } from 'fs';

const sass = gulpSass(sassCompiler);
const read = readFileSync;

/* -------------------------------------------------------------------------
| CONFIGURATION VARIABLES
------------------------------------------------------------------------- */

const isProd = (process.env.NODE_ENV === 'prod');

const config = {
	livereload: process.env.LIVERELOAD ? (process.env.LIVERELOAD == 'true') : true,
	port: process.env.LIVERELOAD_PORT ? parseInt(process.env.LIVERELOAD_PORT, 10) : 3010,
	key: (process.env.KEY) ? process.env.KEY : false,
	cert: (process.env.CRT) ? process.env.CRT : false,

	cssout: isProd ? 'compressed' : 'expanded',
	prefixer: isProd,
	terser: isProd,

	jsApp: {
		source: './src/js/app/app.js',
		output: 'app.min.js',
		dest: './assets/js',
	},

	jsAdmin: {
		source: './src/js/admin/admin.js',
		output: 'onyx.min.js',
		dest: './assets/js/admin',
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
			/^ep-(.*)?$/,
			/^autosuggest-(.*)?$/,
			/^hide-(.*)?$/,
			/^gm-(.*)?$/,
			/^lg-(.*)?$/,
			/^tns-(.*)?$/,
			/^wp-block(-.*)?$/,
			/^wp-preset(-.*)?$/,
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
		jsApp: [ './src/js/app/**/*.js' ],
		jsAdmin: [ './src/js/admin/**/*.js' ],
		styles: [ './src/sass/**/*.scss' ],
		views: [ 'core/**/*.php', 'templates/**/*.php', 'views/**/*.php', 'views/**/*.twig' ],
	},
};

const onyx = {
	watch() {
		return (config.livereload) ? startLiveReload() : null;
	},
	stream() {
		return (config.livereload) ? liveReload() : null;
	},
	reload(file) {
		return (config.livereload) ? liveReload.reload(file) : null;
	},
	prefixer() {
		return autoprefixer({ cascade: false });
	},
};

/* -------------------------------------------------------------------------
| STYLES
------------------------------------------------------------------------- */

function styles() {
	return gulp
		.src(config.styles.source)
		.pipe(sass({
			style: config.cssout,
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
		.pipe(px2rem({
			replace: true,
		}))
		.pipe(gulp.dest(config.styles.dest));
}

/* -------------------------------------------------------------------------
| JAVASCRIPTS
------------------------------------------------------------------------- */

function js(jsconfig) {
	let cache;
	const options = {
		input: jsconfig.source,
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
		.on('bundle', (bundle) => (cache = bundle))
		.pipe(source(jsconfig.output))
		.pipe(gulp.dest(jsconfig.dest))
		.pipe(onyx.stream());
}

const jsApp = () => js(config.jsApp);
const jsAdmin = () => js(config.jsAdmin);

/* -------------------------------------------------------------------------
| LIVE RELOAD
------------------------------------------------------------------------- */

function startLiveReload() {
	liveReload.listen({
		port: config.port,
		key: (config.key) ? read(config.key) : false,
		cert: (config.cert) ? read(config.cert) : false,
	});
}

/* -------------------------------------------------------------------------
| WATCH FUNCTIONS
------------------------------------------------------------------------- */

function serve() {
	onyx.watch();
	gulp.watch(config.watch.jsApp, jsApp);
	gulp.watch(config.watch.jsAdmin, jsAdmin);
	gulp.watch(config.watch.styles, styles);
	gulp.watch(config.watch.views).on('change', (file) => onyx.reload(file));
}

/* -------------------------------------------------------------------------
| EXPORTS
------------------------------------------------------------------------- */

export {
	styles,
	stylesPurge,
	jsApp,
	jsAdmin,
	serve,
};
export default gulp.series(styles, stylesPurge, jsApp, jsAdmin);

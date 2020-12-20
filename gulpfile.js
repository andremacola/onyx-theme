'use strict';

require('dotenv/config');

const gulp = require('gulp');
const gulpif = require('gulp-if');
const rename = require('gulp-rename');
const source = require('vinyl-source-stream');

const livereload = require('gulp-livereload');

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
	uiport: parseInt(process.env.UIPORT, 10),

	cssout: 'compact',
	prefixer: false,
	terser: false,

	style: './src/sass/styles.scss',
	styleHome: './src/sass/home.scss',
	styleDest: './assets/css',

	js: './src/js/app.js',
	jsHome: './src/js/home.js',
	jsDest: './assets/js',
};

if (process.env.NODE_ENV === 'prod') {
	config.cssout = 'compressed';
	config.prefixer = true;
	config.terser = true;
}

const wpCSS = {
	safelist: [
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
		.pipe(
			gulpif(
				config.prefixer,
				autoprefixer({
					cascade: false,
				})
			)
		)
		.pipe(gulp.dest(config.styleDest))
		.pipe(livereload());
}

function stylesHome() {
	return gulp
		.src(config.styleHome)
		.pipe(sass({ outputStyle: config.cssout }).on('error', sass.logError))
		.pipe(rename('home.css'))
		.pipe(
			gulpif(
				config.prefixer,
				autoprefixer({
					cascade: false,
				})
			)
		)
		.pipe(gulp.dest(config.styleDest))
		.pipe(livereload());
}

function stylesPurge() {
	return gulp
		.src([ 'assets/css/main.css', 'assets/css/home.css' ])
		.pipe(
			purgecss({
				content: [ 'core/**/*.php', 'templates/**/*.php', 'views/**/*.twig', 'views/**/*.php', 'src/js/**/*.js' ],
				safelist: wpCSS.safelist,
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
		.pipe(livereload());
}

function jsHome() {
	const options = {
		input: config.jsHome,
		output: {
			dir: config.jsDest,
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
		.pipe(source('home.min.js'))
		// .pipe(buffer())
		.pipe(gulp.dest(config.jsDest))
		.pipe(livereload());
}

/*
|--------------------------------------------------------------------------
| WATCH FUNCTIONS
|--------------------------------------------------------------------------
*/

function watch() {
	gulp.watch([ './src/sass/**/*.scss', '!./src/sass/**/home.scss' ], styles);
	gulp.watch([ './src/sass/**/*.scss', '!./src/sass/**/styles.scss' ], stylesHome);
	gulp.watch([ './src/js/**/*.js', '!./src/js/**/home.js' ], js);
	gulp.watch('./src/js/**/home.js', jsHome);
	gulp.watch([ 'core/**/*.php', 'templates/**/*.php', 'views/**/*.php', 'views/**/*.twig' ]).on('change', function(file) {
		livereload.reload(file);
	});
}

function live() {
	livereload.listen(config.port);
	watch();
}

/*
|--------------------------------------------------------------------------
| EXPORTS
|--------------------------------------------------------------------------
*/

exports.styles = styles;
exports.stylesHome = stylesHome;
exports.stylesPurge = stylesPurge;
exports.js = js;
exports.jsHome = jsHome;
exports.watch = watch;
exports.live = live;
exports.default = gulp.series(styles, stylesHome, stylesPurge, js, jsHome);

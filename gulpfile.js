'use strict';

require('dotenv/config');
const gulp = require('gulp');
const gulpif = require('gulp-if');
const rename = require('gulp-rename');
const autoprefixer = require('gulp-autoprefixer');
const sass = require('gulp-sass');
const include = require('gulp-include');
// const concat = require('gulp-concat');
const terser = require('gulp-terser');
const browserSync = require('browser-sync').create();
const livereload = require('gulp-livereload');
const purgecss = require('gulp-purgecss');
sass.compiler = require('node-sass');

const config = {
	url: process.env.URL,
	port: parseInt(process.env.PORT, 10),
	uiport: parseInt(process.env.UIPORT, 10),
	cssout: process.env.CSSOUTPUT,
	prefixer: process.env.PREFIXER === 'true',
	terser: process.env.TERSER === 'true',
	liveReload: process.env.LIVERELOAD === 'true',
	browserSync: process.env.BROWSERSYNC === 'true',
	destCSS: './assets/css',
	mainCSS: './src/sass/styles.scss',
	intCSS: './src/sass/styles-int.scss',
	extraCSS: [],
};

/*
 * SASS/STYLES
 */
function styleMain() {
	return gulp
		.src(config.mainCSS)
		.pipe(sass({ outputStyle: config.cssout }).on('error', sass.logError))
		.pipe(rename('main.css'))
		.pipe(
			gulpif(
				config.prefixer,
				autoprefixer({
					cascade: false,
				})
			)
		)
		.pipe(gulp.dest(config.destCSS))
		.pipe(gulpif(config.liveReload, livereload()))
		.pipe(gulpif(config.browserSync, browserSync.stream()));
}

function styleInt() {
	return gulp
		.src(config.intCSS)
		.pipe(sass({ outputStyle: config.cssout }).on('error', sass.logError))
		.pipe(rename('int.css'))
		.pipe(
			gulpif(
				config.prefixer,
				autoprefixer({
					cascade: false,
				})
			)
		)
		.pipe(gulp.dest(config.destCSS))
		.pipe(gulpif(config.liveReload, livereload()))
		.pipe(gulpif(config.browserSync, browserSync.stream()));
}

// function styleExtras() {
// 	return gulp
// 		.src(config.extraCSS)
// 		.pipe(sass({ outputStyle: config.cssout }).on('error', sass.logError))
// 		.pipe(gulpif(config.prefixer, autoprefixer({
// 			cascade: false,
// 		})))
// 		.pipe(gulp.dest(config.destCSS))
// 		.pipe(browserSync.stream());
// }

function purgeCSS() {
	return gulp
		.src([ 'assets/css/main.css' ])
		.pipe(
			purgecss({
				content: [ 'core/**/*.php', 'templates/**/*.php', 'views/**/*.php', 'src/js/**/*.js' ],
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
				],
				whitelistPatterns: [
					/^wp-block(-.*)?$/,
					/^active(-.*)?$/,
					/^owl-(.*)?$/,
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
			})
		)
		.pipe(gulp.dest(config.destCSS));
}

/*
 * JAVASCRIPTS
 */
function jsMain() {
	return gulp
		.src('./src/js/app.js')
		.pipe(include())
		.on('error', console.log)
		.pipe(gulpif(config.terser, terser()))
		.pipe(rename('app.min.js'))
		.pipe(gulp.dest('./assets/js'))
		.pipe(gulpif(config.liveReload, livereload()))
		.pipe(gulpif(config.browserSync, browserSync.stream()));
}

function jsInt() {
	return gulp
		.src('./src/js/app-int.js')
		.pipe(include())
		.on('error', console.log)
		.pipe(gulpif(config.terser, terser()))
		.pipe(rename('app-int.min.js'))
		.pipe(gulp.dest('./assets/js'))
		.pipe(gulpif(config.liveReload, livereload()))
		.pipe(gulpif(config.browserSync, browserSync.stream()));
}

/*
 * WATCH
 */
function watchFiles() {
	gulp.watch([ './src/sass/**/*.scss', '!./src/sass/**/styles-int.scss' ], styleMain);
	gulp.watch([ './src/sass/**/styles-int.scss' ], styleInt);
	gulp.watch([ './src/js/**/*.js', '!./src/js/**/app-int.js' ], jsMain);
	gulp.watch('./src/js/**/app-int.js', jsInt);
}

function browserWatch() {
	browserSync.init({
		open: false,
		proxy: config.url ? config.url : 'http://localhost',
		ghostMode: false,
		port: config.port ? config.port : 3000,
		minify: false,
		ui: {
			port: config.uiport ? config.uiport : 3001,
		},
	});
	watchFiles();
	gulp.watch('./**/*.php').on('change', browserSync.reload);
}

function liveReloadWatch() {
	livereload.listen(config.port);
	watchFiles();
	gulp.watch('**/*.php').on('change', function(file) {
		livereload.reload(file);
	});
}

/*
 * EXPORTS
 */
// exports.styleExtras = styleExtras;
exports.styleMain = styleMain;
exports.styleInt = styleInt;
exports.purgecss = purgeCSS;
exports.jsMain = jsMain;
exports.jsInt = jsInt;
exports.watch = watchFiles;
exports.server = browserWatch;
exports.live = liveReloadWatch;
exports.default = gulp.series(styleMain, styleInt, purgeCSS, jsMain, jsInt);

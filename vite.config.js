import { defineConfig, loadEnv } from 'vite';
import fullReload from 'vite-plugin-full-reload';
import purgecss from '@fullhuman/postcss-purgecss';
import autoprefixer from 'autoprefixer';
import pxtorem from 'postcss-pxtorem';
import { resolve, dirname } from 'node:path';
import { fileURLToPath } from 'node:url';
import fs from 'node:fs';

const __dirname = dirname(fileURLToPath(import.meta.url));

const purgecssSafelist = {
	standard: [
		'rtl', 'home', 'blog', 'archive', 'date', 'error404',
		'logged-in', 'admin-bar', 'no-customize-support',
		'custom-background', 'wp-custom-logo',
		'alignnone', 'alignright', 'alignleft',
		'wp-caption', 'wp-caption-text', 'screen-reader-text',
	],
	greedy: [
		/^ep-/, /^autosuggest-/, /^hide-/, /^gm-/, /^lg-/, /^tns-/,
		/^wp-block(-.*)?$/, /^wp-preset(-.*)?$/, /^active(-.*)?$/,
		/^search(-.*)?$/, /-template(-.*)?$/, /-?single(-.*)?$/,
		/^postid-/, /^attachmentid-/, /^attachment(-.*)?$/,
		/^page(-.*)?$/, /^(post-type-)?archive(-.*)?$/,
		/^author(-.*)?$/, /^category(-.*)?$/, /^tag(-.*)?$/,
		/^tax-/, /^term-/, /-?paged(-.*)?$/,
		/^comments-/, /^comment-/,
	],
};

const purgecssContent = [
	'comments.php',
	'core/**/*.php',
	'templates/**/*.php',
	'views/**/*.twig',
	'views/**/*.php',
	'src/js/**/*.js',
];

/**
 * Writes a `hot` file to assets/dist/ while the dev server is running so the
 * PHP layer can detect the active dev server URL.
 *
 * @param {string} devUrl Dev-server URL written to the hot file.
 * @return {import('vite').Plugin} Vite plugin.
 */
function hotFilePlugin(devUrl) {
	const distDir = resolve(__dirname, 'assets/dist');
	const hotFile = resolve(distDir, 'hot');

	const cleanup = () => {
		try {
			fs.unlinkSync(hotFile);
		} catch { /* noop */ }
	};

	return {
		name: 'onyx:hot-file',
		apply: 'serve',
		configureServer(server) {
			server.httpServer?.once('listening', () => {
				fs.mkdirSync(distDir, { recursive: true });
				fs.writeFileSync(hotFile, devUrl);
			});
			process.on('SIGINT', () => {
				cleanup(); process.exit();
			});
			process.on('SIGTERM', () => {
				cleanup(); process.exit();
			});
			process.on('exit', cleanup);
		},
	};
}

export default defineConfig(({ mode }) => {
	const env = loadEnv(mode, process.cwd(), '');
	const isProd = mode === 'production';
	const port = parseInt(env.VITE_DEV_PORT || '5173', 10);
	const useHttps = env.VITE_HTTPS === 'true' && env.VITE_KEY && env.VITE_CRT;
	const protocol = useHttps ? 'https' : 'http';
	const devUrl = `${protocol}://localhost:${port}`;

	return {
		root: __dirname,
		base: isProd ? './' : `${devUrl}/`,

		server: {
			host: 'localhost',
			port,
			strictPort: true,
			cors: true,
			origin: devUrl,
			https: useHttps ? {
				key: fs.readFileSync(env.VITE_KEY),
				cert: fs.readFileSync(env.VITE_CRT),
			} : false,
			hmr: { host: 'localhost', protocol: useHttps ? 'wss' : 'ws' },
		},

		build: {
			outDir: 'assets/dist',
			emptyOutDir: true,
			manifest: true,
			sourcemap: ! isProd,
			cssCodeSplit: true,
			rollupOptions: {
				input: {
					style: resolve(__dirname, 'src/sass/style.scss'),
					'style.admin': resolve(__dirname, 'src/sass/admin.scss'),
					'style.editor': resolve(__dirname, 'src/sass/editor.scss'),
					app: resolve(__dirname, 'src/js/app/app.js'),
					admin: resolve(__dirname, 'src/js/admin/admin.js'),
				},
				output: {
					assetFileNames: '[name]-[hash][extname]',
					chunkFileNames: '[name]-[hash].js',
					entryFileNames: '[name]-[hash].js',
				},
			},
		},

		css: {
			postcss: {
				plugins: [
					autoprefixer(),
					...(isProd ? [
						pxtorem({ propList: [ '*' ], rootValue: 16, replace: true }),
						purgecss({ content: purgecssContent, safelist: purgecssSafelist }),
					] : []),
				],
			},
			preprocessorOptions: {
				scss: {
					loadPaths: [ 'node_modules' ],
				},
			},
		},

		plugins: [
			hotFilePlugin(devUrl),
			fullReload([
				'core/**/*.php',
				'templates/**/*.php',
				'views/**/*.twig',
				'views/**/*.php',
				'comments.php',
			]),
		],
	};
});

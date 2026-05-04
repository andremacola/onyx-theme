<?php
/**
 * Vite asset resolver. Reads the dev-server URL from `assets/dist/hot` while
 * Vite is running and falls back to `assets/dist/.vite/manifest.json` for
 * production builds. Mirrors the behavior of Laravel/Symfony Vite helpers.
 *
 * @package Onyx Theme
 */

namespace Onyx;

use Onyx\Helpers as O;

class Vite {

	/**
	 * Cached manifest contents (production).
	 *
	 * @var array|null
	 */
	private static $manifest = null;

	/**
	 * Resolved dev-server URL when Vite is running, empty string otherwise.
	 *
	 * @var string|null
	 */
	private static $dev_url = null;

	/**
	 * Script handles that must be emitted as ES modules.
	 *
	 * @var array<string, true>
	 */
	private static $module_handles = [];

	/**
	 * Whether the Vite dev server is currently running.
	 *
	 * @return bool
	 */
	public static function is_running() {
		if ( null !== self::$dev_url ) {
			return '' !== self::$dev_url;
		}

		if ( empty( O::conf( 'env' )->local ) ) {
			self::$dev_url = '';
			return false;
		}

		$hot = self::dist_path() . '/hot';
		if ( file_exists( $hot ) ) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Local file read.
			self::$dev_url = trim( file_get_contents( $hot ) );
			return '' !== self::$dev_url;
		}

		self::$dev_url = '';
		return false;
	}

	/**
	 * Resolve a source path (e.g. `src/sass/style.scss`) to a public URL.
	 *
	 * @param string $source Source entry path relative to the theme root.
	 * @return string Public URL or empty string when the entry was not found.
	 */
	public static function asset( $source ) {
		$source = ltrim( $source, '/' );

		if ( self::is_running() ) {
			return self::$dev_url . '/' . $source;
		}

		$entry = self::manifest()[$source] ?? null;
		if ( ! $entry ) {
			return '';
		}

		return self::dist_url() . '/' . $entry['file'];
	}

	/**
	 * Returns the CSS files associated with a JS entry from the production
	 * manifest. Empty in dev (Vite injects CSS via the JS module).
	 *
	 * @param string $source Source entry path relative to the theme root.
	 * @return array<string> List of public URLs.
	 */
	public static function css_for( $source ) {
		if ( self::is_running() ) {
			return [];
		}

		$entry = self::manifest()[ ltrim( $source, '/' ) ] ?? null;
		if ( ! $entry || empty( $entry['css'] ) ) {
			return [];
		}

		$base = self::dist_url() . '/';
		return array_map(
			static function ( $file ) use ( $base ) {
				return $base . $file;
			},
			$entry['css']
		);
	}

	/**
	 * URL of the Vite HMR client (only meaningful in dev).
	 *
	 * @return string
	 */
	public static function hmr_client_url() {
		return self::is_running() ? self::$dev_url . '/@vite/client' : '';
	}

	/**
	 * Mark a script handle to be emitted with `type="module"`.
	 *
	 * @param string $handle Script handle.
	 * @return void
	 */
	public static function register_module( $handle ) {
		self::$module_handles[ $handle ] = true;
	}

	/**
	 * `script_loader_tag` filter callback. Adds `type="module"` to handles
	 * registered via `register_module()`.
	 *
	 * @param string $tag    HTML script tag.
	 * @param string $handle Script handle.
	 * @return string
	 */
	public static function filter_script_tag( $tag, $handle ) {
		if ( empty( self::$module_handles[ $handle ] ) ) {
			return $tag;
		}
		if ( preg_match( '/\stype\s*=\s*["\'][^"\']*["\']/i', $tag ) ) {
			return preg_replace( '/\stype\s*=\s*["\'][^"\']*["\']/i', ' type="module"', $tag, 1 );
		}
		return preg_replace( '/<script /', '<script type="module" ', $tag, 1 );
	}

	/**
	 * Lazy-loaded manifest reader.
	 *
	 * @return array
	 */
	private static function manifest() {
		if ( null !== self::$manifest ) {
			return self::$manifest;
		}

		$path = self::dist_path() . '/.vite/manifest.json';
		if ( ! file_exists( $path ) ) {
			self::$manifest = [];
			return self::$manifest;
		}

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Local file read.
		$decoded        = json_decode( file_get_contents( $path ), true );
		self::$manifest = is_array( $decoded ) ? $decoded : [];
		return self::$manifest;
	}

	/**
	 * Absolute filesystem path to the Vite output directory.
	 *
	 * @return string
	 */
	private static function dist_path() {
		return get_template_directory() . '/assets/dist';
	}

	/**
	 * Public URL to the Vite output directory.
	 *
	 * @return string
	 */
	private static function dist_url() {
		return get_template_directory_uri() . '/assets/dist';
	}
}

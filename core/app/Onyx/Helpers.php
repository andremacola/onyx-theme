<?php
/**
 * Helper Class with some WordPress custom methods
 *
 * @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
 *
 * @package Onyx Theme
 */

namespace Onyx;

class Helpers {

	/**
	 * Configuration enviroments parameters
	 *
	 * @var array
	 */
	private static $conf;

	/**
	 * Templates hierarchy from current page
	 *
	 * @var array
	 */
	private static $hierarchy = [];

	/**
	 * Return enviroment settings.
	 * Use with caution
	 *
	 * @param string $name Config name variable (see config/ folder) [optional].
	 * @return object|false
	 */
	public static function conf( $name = false ) {
		$confs  = self::$conf;
		$filter = [ 'pass', 'password', 'key', 'keys', 'dev', 'devs' ];

		$confs['env'] = self::array_filter_keys( $confs['env'], $filter );
		// $confs['mail'] = self::array_filter_keys( $confs['mail'], $filter );

		$config = ( ! empty( $name ) ) ? $confs[$name] : $confs;

		return (object) $config;
	}

	/**
	 * Load configuration file.
	 * The configuration file must be returning an array
	 *
	 * @param string $file File name [required].
	 * @param bool   $obj Return as object. Default object [optional].
	 * @return array|object|false
	 */
	public static function load( $file, $obj = true ) {
		$require = __DIR__ . "/../../config/$file.php";
		if ( file_exists( $require ) ) {
			self::$conf[$file] = require_once $require;
			return ($obj) ? (object) self::$conf[$file] : self::$conf[$file];
		}
		return false;
	}

	/**
	 * Set template files hierarchy loaded from boot.
	 *
	 * @param array $hierarchy Hierarchy templates array.
	 * @return void
	 */
	public static function set_hierarchy( $hierarchy = [] ) {
		self::$hierarchy[] = $hierarchy;
	}

	/**
	 * Get template files hierarchy loaded from boot.
	 * Provide suffix `-controller` to the filename to get the controller file.
	 *
	 * @return array
	 */
	public static function get_hierarchy() {
		return self::$hierarchy;
	}

	/**
	 * Filter multidimensional array recursively
	 *
	 * @param array $arr Array source [required].
	 * @param array $filter Keys to remove [required].
	 * @return array
	 */
	public static function array_filter_keys( $arr, $filter ) {
		foreach ( $arr as $key => $value ) {
			if ( is_array( $value ) && ! is_numeric( array_keys( $value )[0] ) ) {
				// $arr[$key] = call_user_func(array(__CLASS__, __FUNCTION__), $value, $filter);
				$arr[$key] = self::array_filter_keys( $value, $filter );
			} elseif ( in_array( $key, $filter ) ) {
				unset( $arr[ $key ] );
			}
		}
		return $arr;
	}

	/**
	 * Checks whether it is an AMP page
	 * This is needed for https://br.wordpress.org/plugins/amp/
	 *
	 * @return bool
	 */
	public static function is_amp() {
		return function_exists( 'amp_is_request' ) && amp_is_request();
	}

	/**
	 * Validate url from a string
	 *
	 * @param string $uri Provide url with protocol (ex: https://domain.tld/somefile.js)
	 * @return bool
	 */
	public static function valid_url( $uri ) {
		return filter_var( $uri, FILTER_VALIDATE_URL );
	}

	/**
	 * Returns the path url based on the file location
	 * if is a remote or local file.
	 *
	 * @param string $file File location or URL
	 * @return string
	 */
	public static function static_path( $file ) {
		if ( self::valid_url( $file ) ) {
			$asset = $file;
		} else {
			$dir_uri = self::static_uri( self::$conf['env']['dir_uri'] );
			$asset   = $dir_uri . '/' . ltrim( $file, '/' );
		}

		return $asset;
	}

	/**
	 * Return assets directory based on ambient variable|constant
	 * If ONYX_STATIC is defined, it will use a static subdomain to serve the files.
	 * The domain pattern is configured in `static` parameter at: `./core/config/app.php`
	 *
	 * @param string $uri [required]
	 * @return string Path of the file
	 */
	public static function static_uri( $uri ) {
		if ( ! ONYX_STATIC ) {
			return $uri;
		} else {
			$static_uri = '//' . ONYX_STATIC;
			return $static_uri;
		}
	}

	/**
	 * Get image from theme folder
	 *
	 * @param string $img Image path [required]
	 * @param string $title Image title/alt attributes [optional]
	 * @param string $w Width [optional]
	 * @param string $h Height [optional]
	 * @return string
	 */
	public static function get_img( $img, $title = null, $w = null, $h = null ) {
		$src = self::static_path( $img );

		$alt   = ( $title ) ? " alt='$title'" : false;
		$title = ( $title ) ? " title='$title'" : false;
		$w     = ( $w ) ? " width='$w'" : false;
		$h     = ( $h ) ? " height='$h'" : false;
		$img   = "<img src='$src'$w$h$title$alt>";

		return $img;
	}

	/**
	 * Show image from theme folder
	 *
	 * @see selff::get_img();
	 * @param string $img Image path [required]
	 * @param string $title Image title/alt attributes [optional]
	 * @param string $w Width [optional]
	 * @param string $h Height [optional]
	 * @return void
	 */
	public static function img( $img, $title = null, $w = null, $h = null ) {
		echo self::get_img( $img, $title = null, $w = null, $h = null );
	}

	/**
	 * Add CSS from assets
	 *
	 * @param string $css [required]
	 * @param bool   $home Display CSS only on Home [optional]
	 * @return void Link style html tag
	 */
	public static function css( $css, $home = false ) {
		$src = self::static_path( $css );
		$v   = ONYX_STATIC_VERSION;
		$css = "<link rel='stylesheet' href='$src?ver=$v'>\n";

		if ( ! $home ) :
			echo $css;
		else :
			if ( is_home() ) {
				echo $css;
			}
		endif;
	}

	/**
	 * Add javascript from assets
	 *
	 * @param string $js file|url [required]
	 * @param bool   $home Display script only on Home [optional]
	 * @param string $attr Tag attributes (data|async|defer) [optional]
	 * @return void Script html tag
	 */
	public static function js( $js, $home = false, $attr = '' ) {
		$src    = self::static_path( $js );
		$v      = ONYX_STATIC_VERSION;
		$script = "<script $attr src='$src?ver=$v'></script>\n";

		if ( ! $home ) :
			echo $script;
		else :
			if ( is_home() ) {
				echo $script;
			}
		endif;
	}

	/**
	 * Add google analytics script html (main method)
	 *
	 * @param string $uax Google UAX ID required [required]
	 * @param bool   $script Load google tag manager (gtag.js) script. Default false [optional]
	 * @return void Google Tag Manager html and (or) tag
	 */
	public static function gtag( $uax, $script = false ) {
		if ( true === $script ) {
			echo "<script async src='https://www.googletagmanager.com/gtag/js?id=$uax'></script>";
		}
		$ganalytics = "
			<script>
				window.dataLayer = window.dataLayer || [];
				function gtag(){dataLayer.push(arguments);}
				gtag('js', new Date()); gtag('config', '$uax');
			</script>
		";
		echo $ganalytics;
	}

	/**
	 * Alternative way to add google analytics script html
	 *
	 * @see https://github.com/h5bp/html5-boilerplate/issues/2014
	 *
	 * @param string $uax Google UAX ID [required]
	 * @param bool   $script Load analytics.js script from google [optional]
	 * @return void  Google Analytics html and (or) tag
	 */
	public static function analytics( $uax, $script = false ) {
		$ganalytics = "
			<script>
				window.ga = function () { ga.q.push(arguments) }; ga.q = []; ga.l = +new Date;
				ga('create', '$uax', 'auto'); ga('set','transport','beacon'); ga('send', 'pageview')
			</script>
		";
		echo $ganalytics;
		if ( true === $script ) {
			echo "<script async src='https://www.google-analytics.com/analytics.js'></script>\n";
		}
	}

	/**
	 * Check if logged user is a developer
	 *
	 * @return bool
	 */
	public static function is_dev() {
		$env = (object) self::$conf['env'];
		return in_array( $env->user, $env->devs );
	}

	/**
	 * Clear Timber Cache
	 *
	 * @return void
	 */
	public static function clear_cache_timber() {
		$loader = new \Timber\Loader();
		$loader->clear_cache_timber();
		$loader->clear_cache_twig();
	}

	/*
	|--------------------------------------------------------------------------
	| ONLY HELPERS FOR WordPress NATIVE FUNCTIONS FROM HERE
	|--------------------------------------------------------------------------
	*/

	/**
	 * Return the current section title depending on route section type of WordPress
	 *
	 * This need another approach, maybe using self::route_type()
	 *
	 * @param bool   $echo Whether to display or retrieve title. Default true [optional]
	 * @param string $prefix What to display before the title [optional]
	 * @return void|string
	 */
	public static function section_title( $echo = true, $prefix = '' ) {
		if ( is_post_type_archive() ) {
			$title = post_type_archive_title( $prefix, false );
		} elseif ( is_category() ) {
			$title = single_cat_title( $prefix, false );
		} elseif ( is_tag() ) {
			$title = single_tag_title( $prefix, false );
		} elseif ( is_author() ) {
			$title = get_the_author();
		} elseif ( is_tax() ) { // for custom post types.
			$title = single_term_title( $prefix, false );
		}

		if ( $echo ) {
			echo $title;
		} else {
			return $title;
		}
	}

	/**
	 * Return the section route type on WordPress. Ex: is_page, is_home, is_archive etc...
	 *
	 * @return string
	 */
	public static function route_type() {
		global $wp_query;

		$types = array_filter(
			(array) $wp_query,
			function( $key ) {
				return strpos( $key, 'is_' ) === 0;
			},
			ARRAY_FILTER_USE_KEY
		);

		$type = key( array_filter( $types ) );

		return $type;
	}

	/**
	 * Show navigation menu
	 *
	 * @param string $menu The menu name [required]
	 * @return void
	 */
	public static function menu( $menu = null ) {
		wp_nav_menu(
			array(
				'menu'       => $menu,
				'container'  => '',
				'items_wrap' => '%3$s',
			)
		);
	}

	/**
	 * Onyx Pagenavi. Show posts/pages pagination
	 *
	 * @param object $query The query object to show the pagination.
	 * @return void
	 */
	public static function pagenavi( $query = null ) {
		global $wp_query;

		if ( ! $query ) {
			$query = $wp_query;
		}

		$total = $query->max_num_pages;
		// only bother with the rest if we have more than 1 page!
		if ( $total > 1 ) {
			// get the current page.
			$current_page = get_query_var( 'paged' );
			if ( ! $current_page ) {
				$current_page = 1;
			}
			// structure of "format" depends on whether we're using pretty permalinks.
			$format = empty( get_option( 'permalink_structure' ) ) ? '&page=%#%' : 'page/%#%/';
			$pages  = paginate_links(
				array(
					'base'               => get_pagenum_link( 1 ) . '%_%',
					'format'             => $format,
					'current'            => $current_page,
					'total'              => $total,
					'mid_size'           => 4,
					'end_size'           => 1,
					'type'               => 'array',
					'show_all'           => false,
					'prev_next'          => true,
					'prev_text'          => '« <span class="nav-text">' . __( 'Previous', 'onyx-theme' ) . '</span>',
					'next_text'          => '<span class="nav-text">' . __( 'Next', 'onyx-theme' ) . '</span> »',
					'add_args'           => false,
					'add_fragment'       => '',
					'before_page_number' => '',
					'after_page_number'  => '',
				)
			);
			if ( is_array( $pages ) ) {
				echo '<div class="onyx-pagination"><ul class="page-numbers">';
				foreach ( $pages as $page ) {
					$current = false;
					if ( strpos( $page, 'current' ) !== false ) {
						$current = ' class="current"';
					};
					echo "<li$current>$page</li>";
				}
				echo '</ul></div>';
			}
		}
	}

}

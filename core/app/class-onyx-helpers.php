<?php

/**
 * 
 * Helper Class with some Wordpress custom methods
 * 
 */

Class O {

	/**
	 * Return the current section title depending on route section type of Wordpress
	 * 
	 * This need another approach, maybe using self::section_type()
	 * 
	 * @param bool Whether to display or retrieve title. Default true [optional]
	 * @param string $prefix What to display before the title [optional]
	 * @return void|string
	 */
	static function section_title($echo = true, $prefix = '') {
		if (is_post_type_archive()) {
			$title = post_type_archive_title($prefix, false);
		} elseif (is_category()) {    
			$title = single_cat_title($prefix, false);
		} elseif (is_tag()) {
			$title = single_tag_title($prefix, false);
		} elseif (is_author()) {
			$title = get_the_author();
		} elseif (is_tax()) { //for custom post types
			$title = single_term_title($prefix, false);
		}

		if ($echo) {
			echo $title;
		} else {
			return $title;
		}
	}

	/**
	 * Return the section route type on Wordpress. Ex: is_page, is_home, is_archive etc...
	 * 
	 * @return string
	 */
	static function section_type() {
		global $wp_query;

		$types = array_filter((array) $wp_query, function($key) {
			return strpos($key, 'is_') === 0;
		}, ARRAY_FILTER_USE_KEY);

		$type = key(array_filter($types));

		return $type;
	}

	/**
	* Add google analytics script html (main method)
	*
	* @param string $uax Google UAX ID required [required]
	* @param bool $script Load google tag manager (gtag.js) script. Default false [optional]
	* @return void Google Tag Manager html and (or) tag
	*/
	static function gtag($uax, $script = false) {
		if ($script == true) {
			echo "<script async src='https://www.googletagmanager.com/gtag/js?id=$uax'></script>";
		}
		$ganalytics	= "
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
	* @see https://github.com/h5bp/html5-boilerplate/issues/2014
	*
	* @param string $uax Google UAX ID [required]
	* @param bool $script Load analytics.js script from google [optional]
	* @return void Google Analytics html and (or) tag
	*/
	static function analytics($uax, $script = false) {
		$ganalytics = "
			<script>
				window.ga = function () { ga.q.push(arguments) }; ga.q = []; ga.l = +new Date;
				ga('create', '$uax', 'auto'); ga('set','transport','beacon'); ga('send', 'pageview')
			</script>
		";
		echo $ganalytics;
		if ($script == true) {
			echo "<script async src='https://www.google-analytics.com/analytics.js'></script>\n";
		}
	}

	/**
	* Show navigation menu
	*
	* @param string $menu The menu name [required]
	* @return void
	*/
	static function menu($menu = null) {
		wp_nav_menu(array('menu' => $menu, 'container' => '', 'items_wrap' => '%3$s'));
	}

	/**
	* Checks whether it is an AMP page
	* This is needed for https://br.wordpress.org/plugins/amp/
	*
	* @return bool
	*/
	static function is_amp() {
		return function_exists('is_amp_endpoint') && is_amp_endpoint();
	}

	/**
	 * Return assets directory based on ambient variable|constant
	 * If ONYX_STATIC is defined, it will use a static subdomain to serve the files
	 * 
	 * @param string $uri [required]
	 * @param string $subdomain [optional] Default 'static'
	 * @return string Path of the file
	 */
	static function static_uri($uri, $subdomain = 'static') {
		if (!ONYX_STATIC) {
			return $uri;
		} else {
			$app_folder = str_replace('%2F', '/', rawurlencode(get_template()));
			$static_uri = (!ONYX_STATIC) ? $uri : '//' . $subdomain . '.' . $_SERVER['HTTP_HOST'] . "/$app_folder";
			return $static_uri;
		}
	}

	// /**
	//  * Add javascript from assets
	//  * @param string $url [required]
	//  * @param bool $home Display script on Home [optional]
	//  * @param string $attr Is async|defer script [optional]
	//  * @return void Script html tag
	//  */
	// function js($js, $home = true, $async = null) {
	// 	global $app;
	// 	$static_path = onyx_get_static($app->dir);
	// 	$path_file = '<script '.$async.' src="'.$static_path.'/assets/js/'.$js.'"></script>' . "\n";
	// 	if ($home): 
	// 		echo $path_file;
	// 	else:
	// 		if (!is_home()) echo $path_file;
	// 	endif;
	// }

	/**
	 * Add javascript from url
	 * @param string $url [required]
	 * @param bool $home Display script on Home [optional]
	 * @param string $attr Is async|defer script [optional]
	 * @return void Script html tag
	 */
	static function js_url($js, $home = true, $attr = null) {
		$script = '<script '.$attr.' src="'.$js.'"></script>' . "\n";
		if ($home):
			echo $script;
		else:
			if (!is_home()) echo $script;
		endif;
	}

	/**
	* Method to call gulp-livereload script (see gulp.js)
	*
	* @return void|false
	*/
	static function livereload($port = 3010, $host = 'localhost') {
		if (pathinfo($_SERVER['SERVER_NAME'])['extension'] === 'local') {
			$url = "//$host:$port/livereload.js?snipver=1";
			$headers = @get_headers("http:$url");
			if ($headers) {
				return self::js_url($url);
			}
		}
		return false;
	}
		
}

?>

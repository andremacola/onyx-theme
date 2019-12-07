<?php
/**
 * 
 * Funções para o funcionamento do front-end
 * Retorno de tipos de conteúdo
 * 
 */

/* retornar título da área em páginas de listagens/arquivos */
function onyx_get_section_title() {
	if (is_post_type_archive()) {
		$title = post_type_archive_title('', false);
	} elseif (is_category()) {    
		$title = single_cat_title('', false);
	} elseif (is_tag()) {
		$title = single_tag_title('', false);
	} elseif (is_author() ) {
		$title = get_the_author();
	} elseif (is_tax()) { //for custom post types
		$title = single_term_title('', false);
	}

	return $title;
}

	/* variação da onyx_get_section_title para echo; */
	function onyx_section_title() {
		echo onyx_get_section_title();
	}

/* mostrar menu de navegação */
function onyx_menu($menu = null) {
	wp_nav_menu(array('menu' => $menu, 'container' => '', 'items_wrap' => '%3$s'));
}

/* mostrar tags sem link (necessita testar) */
function onyx_nolink_tags() {
	$posttags = get_the_tags();
	if ($posttags) {
		foreach($posttags as $tag):
			$showtag = $tag->name.", ";
		endforeach;
	echo substr($showtag,0,-2);
	}
}

/* mostrar primeita tag cadastrada e sem link */
function onyx_first_tag() {
	$posttags = get_the_tags();
	if ($posttags) {
		$count = 0;
		foreach($posttags as $tag):
			$count++;
			if ($count == 1 ):
				echo $tag->name . ' ';
			endif;
		endforeach;
	}
}

/* mostrar o ID da categoria */
/* como usar: <?php echo onyx_category_id(); ?> */
function onyx_get_category_id() {
	$category = get_the_category();
	if ($category) {
		return $category[0]->term_id;
	}
	return false;
}

	/* variação da onyx_get_category_id para echo; */
	function onyx_category_id() {
		echo onyx_get_category_id();
	}

/* mostrar o ID da categoria pai */
/* como usar: <?php echo onyx_get_cat_parent_id(); ?> */
function onyx_get_cat_parent_id() {
	$parent_category = get_the_category();
	return $parent_category[0]->category_parent;
}

	/* variação da onyx_get_cat_parent_id para echo; */
	function onyx_cat_parent_id() {
		echo onyx_get_cat_parent_id();
	}


/* mostrar o nome da categoria pai */
/* como usar: <?php echo onyx_get_cat_parent_name(); ?> */
function onyx_get_cat_parent_name() {
	$parent_category = get_the_category();
	$cat_ID = $parent_category[0]->category_parent;
	return get_cat_name($cat_ID);
}

	/* variação da onyx_get_cat_parent_name para echo; */
	function onyx_cat_parent_name() {
		echo onyx_get_cat_parent_name();
	}

/* mostrar o primeiro filho da categoria ou a categoria pai */
/* como usar: <?php echo onyx_get_cat_name(); ?> */
function onyx_get_cat_name() {
	$parent_category	= get_the_category();
	$category_id		= $parent_category[0]->category_parent;
	foreach((get_the_category()) as $childcat):
		if ($childcat->cat_name) {
			return $childcat->cat_name;
		} else {
			return get_cat_name($category_id);
		}
		break;
	endforeach;
}

	/* Variação da onyx_get_cat_name para echo; */
	function onyx_cat_name() {
		echo onyx_get_cat_name();
	}

/* retornar slug da categoria */
function onyx_get_cat_slug() {
	$parent_category	= get_the_category();
	$category_id		= $parent_category[0]->category_parent;
	foreach((get_the_category()) as $childcat):
		if ($childcat->slug) {
			return $childcat->slug;
		} else {
			return get_cat_name($category_id);
		}
		break;
	endforeach;
}

/* retornar objeto da taxonomia */
function onyx_get_term_object($tax = null) {
	global $post;
	if (!$tax) return false;
	$terms = get_the_terms($post->ID, $tax);
	if (!empty($terms)){
		// get the first term
		$term = array_shift($terms);
		return $term;
	}
}

/* retormar nome do termo via id */
function onyx_get_term_name($term_id) {
	return get_term($term_id)->name;
}
function onyx_term_name($term_id) {
	echo get_term($term_id)->name;
}

/* mostrar post slug/name */
function onyx_post_slug($post) {
	$post_slug 	= get_post($post->ID,  ARRAY_A);
	$slug 		= $post_slug['post_name'];
	echo $slug; 
}

/* mostrar resumo com caracteres limitados */
/* como usar: <?php onyx_excerpt(16); ?> */
function onyx_excerpt($limit) {
	$excerpt = explode(' ', get_the_excerpt(), $limit);
	if (count($excerpt)>=$limit) {
		array_pop($excerpt);
		$excerpt = implode(" ",$excerpt).'...';
	} else {
		$excerpt = implode(" ",$excerpt);
	}
	$excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
	echo $excerpt;
}

/* in_category(); recursiva */
/* como usar: <?php if (onyx_in_catparent(2)) {  // coisas } ?> */
/* como usar 2: <?php if (onyx_in_catparent('categoria')) {  // coisas } ?> */
function onyx_in_catparent($cats, $_post = null) {
	foreach ((array)$cats as $cat):
		if (in_category($cat, $_post)) {
			return true;
		} else {
			if (!is_int($cat)) $cat = get_cat_ID($cat);
			$descendants = get_term_children($cat, 'category');
			if ($descendants && in_category($descendants, $_post)) return true;
		}
	endforeach;
	return false;
}

/* is_page(); recursiva */
/* como usar: <?php if (onyx_is_tree(2)) {  // coisas } ?> */
function onyx_is_tree($pid) {
	global $post;
	$anc = get_post_ancestors( $post->ID );
	foreach($anc as $ancestor):
		if (is_page() && $ancestor == $pid) {
			return true;
		}
	endforeach;
	if (is_page()&&(is_page($pid))) {
		return true;
	} else {
		return false;
	}
}
	/* hack para onyx_is_tree baseado na url sem precisar consultar db */
	function onyx_in_permalink($slug) {
		global $wp;
		if (preg_match( "#^$slug(/.+)?$#", $wp->request )) {
			return true;
		} else {
			return false;
		}
	}

/* mostrar url das thumbnails em diferentes tamanhos do padrão do wp */
/* modo de uso: <img src="<?php echo onyx_get_thumb('thumbnail'); ?>" /> */
function onyx_get_thumb($thumb_size = 'thumbnail', $post_id = NULL) {
	global $id;
	$post_id	= ( NULL === $post_id ) ? $id : $post_id;
	$src = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), $thumb_size);
	$src = $src[0];
	return $src;
}

	/* variação da onyx_get_thumb() para echo */
	function onyx_thumb($thumb_size = 'thumbnail', $post_id = NULL) {
		echo onyx_get_thumb($thumb_size, $post_id);
	}

/* atalho para the_post_thumbnail com alt e title definidos pelo título do post */
/* modo de uso: <?php onyx_post_thumb('300x300'); ?> */
function onyx_post_thumb($size = null) {
	the_post_thumbnail($size,['class'=>'img-featured', 'title' => get_the_title(), 'alt' => get_the_title()]);
}

/* mostrar imagens destacada com fallback webp */
function onyx_post_webp($size = null, $custom_class = null) {
	$get_thumbnail	= onyx_get_thumb($size);
	$webp_thumb		= $get_thumbnail.".webp";
	if ($get_thumbnail) {
		$image = "
			<picture class='img-container $custom_class'>
				<source type='image/webp' srcset='$webp_thumb'>
				<img class='img-featured' title='".get_the_title()."' alt='".get_the_title()."' src='$get_thumbnail'>
			</picture>
		";
		echo $image;
	}
}

/* retornar diretório dos assets de acordo com o ambiente (dev ou prod) */
function onyx_get_static($path, $subdomain = 'static') {
	if (!ONYX_STATIC) {
		return $path;
	} else {
		$app_folder = str_replace('%2F', '/', rawurlencode(get_template()));
		$static_path = (!ONYX_STATIC) ? $path : '//' . $subdomain . '.' . $_SERVER['HTTP_HOST'] . "/$app_folder";
		return $static_path;
	}
}

/* return: adicionar imagem do tema */
/* modo de uso: <?php echo onyx_get_img('imagem.jpg'); ?> */
function onyx_get_img($img, $a = null, $w = null, $h = null) {
	global $app;
	$static_path = onyx_get_static($app->dir);
	$path_file = '<img src="'.$static_path.'/assets/images/'.$img.'" width="'.$w.'"height="'.$h.'" alt="'.$a.'" title="'.$a.'">';
	return $path_file;
}

	/* variação da onyx_get_img() para echo */
	function onyx_img($img, $a = null, $w = null, $h = null) {
		echo onyx_get_img($img, $a, $w, $h);
	}

/* adicionar css */
function onyx_css($css, $home = true) {
	global $app;
	$static_path = onyx_get_static($app->dir);
	$path_file = '<link rel="stylesheet" href="'.$static_path.'/assets/css/'.$css.'">' . "\n";
	if ($home): 
		echo $path_file;
	else:
		if (!is_home()) echo $path_file;
	endif;
}

/* adicionar url de css */
function onyx_css_url($css, $home = true) {
	$path_file = '<link rel="stylesheet" href="'.$css.'">' . "\n";
	if ($home): 
		echo $path_file;
	else:
		if (!is_home()) echo $path_file;
	endif;
}

/* adicionar javascript */
function onyx_js($js, $home = true, $async = null) {
	global $app;
	$static_path = onyx_get_static($app->dir);
	$path_file = '<script '.$async.' src="'.$static_path.'/assets/js/'.$js.'"></script>' . "\n";
	if ($home): 
		echo $path_file;
	else:
		if (!is_home()) echo $path_file;
	endif;
}

/* adicionar source do javascript */
function onyx_js_src($js, $home = true, $async = null) {
	global $app;
	$static_path = onyx_get_static($app->dir);
	$path_file = '<script '.$async.' src="'.$static_path.'/src/js/'.$js.'"></script>' . "\n";
	if ($home): 
		echo $path_file;
	else:
		if (!is_home()) echo $path_file;
	endif;
}

/* adicionar url de javascript */
function onyx_js_url($js, $home = true, $async = null) {
$path_file = '<script '.$async.' src="'.$js.'"></script>' . "\n";
	if ($home): 
		echo $path_file;
	else:
		if (!is_home()) echo $path_file;
	endif;
}

/* função para o funcionamento do gulp-livereload */
function onyx_js_livereload($port = 3010, $host = null) {
	$host = ($host) ? $host : $_SERVER['HTTP_HOST'];
	onyx_js_url("//$host:$port/livereload.js?snipver=1");
}

/* adicionar google analytics */
function onyx_ganalytics($uax, $script = null) {
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

/* função alternativa do google analytics */
function onyx_analytics($uax, $script = null) {
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

/* retornar elemento de um svg na pasta images */
function onyx_svglink($file, $target, $viewbox = '0 0 48 48', $class = '') {
	global $app;
	$class = ($class) ? $class : $target;
	echo "
		<svg viewBox='$viewbox' class='$class'>
			<use xlink:href='$app->dir/assets/images/$file.svg#$target'></use>
		</svg>
	";
}

/* paginação */
function onyx_pagenavi($query = null) {
	global $wp_query;

	if (!$query) {
		$query = $wp_query;
	}

	$total = $query->max_num_pages;
	// only bother with the rest if we have more than 1 page!
	if ( $total > 1 )  {
		// get the current page
		if ( !$current_page = get_query_var('paged') ) {
			$current_page = 1;
		}
		// structure of "format" depends on whether we're using pretty permalinks
		$format = empty( get_option('permalink_structure') ) ? '&page=%#%' : 'page/%#%/';
		$pages = paginate_links(array(
			'base'					=> get_pagenum_link(1) . '%_%',
			'format'					=> $format,
			'current'				=> $current_page,
			'total'					=> $total,
			'mid_size'				=> 4,
			'end_size'				=> 1,
			'type'					=> 'array',
			'show_all'				=> false,
			'prev_next'				=> true,
			'prev_text'				=> __('« <span class="nav-text">Anterior</span>'),
			'next_text'				=> __('<span class="nav-text">Próxima</span> »'),
			'add_args'				=> false,
			'add_fragment'			=> '',
			'before_page_number'	=> '',
			'after_page_number'	=> ''
		));
		if( is_array( $pages ) ) {
			echo '<div class="onyx-pagination"><ul class="page-numbers">';
				foreach ($pages as $page) {
					$current = false;
					if (strpos($page, 'current') !== false) $current = ' class="current"';
					echo "<li$current>$page</li>";
				}
			echo '</ul></div>';
		}
	}
}

/* verifica se é uma página AMP */
function onyx_amp() {
	return function_exists('is_amp_endpoint') && is_amp_endpoint();
}

/* var_export: função para debugar */
function ve($data){
	highlight_string("<?php\n " . var_export($data, true) . "?>");
	echo '<script>document.getElementsByTagName("code")[0].getElementsByTagName("span")[1].remove() ;document.getElementsByTagName("code")[0].getElementsByTagName("span")[document.getElementsByTagName("code")[0].getElementsByTagName("span").length - 1].remove() ; </script>';
	die();
}

/* var_dump: função para debugar */
function vd($data) {
	echo "<pre>";
		var_dump($data);
	echo "</pre>";
}

?>

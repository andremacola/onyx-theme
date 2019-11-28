<?php

/**
 * Altera o $output padrão do shortcode de galeria do wp
 */

class OnyxNewGallery {

	/**
	 * Constructor
	 */
	public function __construct() {
		remove_shortcode('gallery');
		add_shortcode('gallery', array($this, 'new_gallery_shortcode'));
	}

	/**
	 * Alterar output da galeria
	 * 
	 * @param array $attr
	 */
	private function new_gallery_shortcode($attr) {
		$post = get_post();

		static $instance = 0;
		$instance++;

		if (!empty($attr['ids'])) {
			// 'ids' is explicitly ordered, unless you specify otherwise.
			if (empty($attr['orderby'])) {
				$attr['orderby'] = 'post__in';
			}
			$attr['include'] = $attr['ids'];
		}

		$output = apply_filters('post_gallery', '', $attr, $instance);
		if ($output != '') {
			return $output;
		}

		$html5 = current_theme_supports('html5', 'gallery');
		$atts = shortcode_atts(array(
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post ? $post->ID : 0,
			'itemtag'    => $html5 ? 'figure'     : 'dl',
			'icontag'    => $html5 ? 'div'        : 'dt',
			'captiontag' => $html5 ? 'figcaption' : 'dd',
			'columns'    => 3,
			'size'       => 'thumbnail',
			'include'    => '',
			'exclude'    => '',
			'link'       => ''
		), $attr, 'gallery');

		$id = intval($atts['id']);

		if (!empty($atts['include'])) {
			$_attachments = get_posts(array('include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby']));

			$attachments = array();
			foreach ($_attachments as $key => $val) {
				$attachments[$val->ID] = $_attachments[$key];
			}
		} elseif (!empty($atts['exclude'])) {
			$attachments = get_children(array('post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby']));
		} else {
			$attachments = get_children(array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby']));
		}

		if (empty($attachments)) {
			return '';
		}

		if (is_feed()) {
			$output = "\n";
			foreach ($attachments as $att_id => $attachment) {
				$output .= wp_get_attachment_link($att_id, $atts['size'], true) . "\n";
			}
			return $output;
		}

		$itemtag = tag_escape($atts['itemtag']);
		$captiontag = tag_escape($atts['captiontag']);
		$icontag = tag_escape($atts['icontag']);
		$valid_tags = wp_kses_allowed_html('post');
		if (!isset($valid_tags[$itemtag])) {
			$itemtag = 'dl';
		}
		if (!isset($valid_tags[$captiontag])) {
			$captiontag = 'dd';
		}
		if (!isset($valid_tags[$icontag])) {
			$icontag = 'dt';
		}

		$columns = intval($atts['columns']);
		$itemwidth = $columns > 0 ? floor(100 / $columns) : 100;
		$float = is_rtl() ? 'right' : 'left';

		$selector = "gallery-{$instance}";

		$gallery_style = '';

		if (apply_filters('use_default_gallery_style', !$html5)) {
			$gallery_style = "
			<style type='text/css'>
				#{$selector} {
					margin: auto;
				}
				#{$selector} .gallery-item {
					float: {$float};
					margin-top: 10px;
					text-align: center;
					width: {$itemwidth}%;
				}
				#{$selector} img {
					border: 2px solid #cfcfcf;
				}
				#{$selector} .gallery-caption {
					margin-left: 0;
				}
				/* see gallery_shortcode() in wp-includes/media.php */
			</style>\n\t\t";
		}

		$size_class = sanitize_html_class($atts['size']);
		$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";

		$output = apply_filters('gallery_style', $gallery_style . $gallery_div);

		$i = 0;
		$sgid = rand(0, 9999);
		foreach ($attachments as $id => $attachment) {

			$attr = (trim($attachment->post_excerpt)) ? array('aria-describedby' => "$selector-$id") : '';
			if (!empty($atts['link']) && 'file' === $atts['link']) {
				// $image_output = wp_get_attachment_link( $id, $atts['size'], false, false, false, $attr );
				$image_output = "<a data-fancybox='gallery-$sgid' data-caption='" . wptexturize($attachment->post_excerpt) . "' href='" . wp_get_attachment_url($id) . "'> " . wp_get_attachment_image($id, $atts['size'], false, $attr) . "</a>";
			} elseif (!empty($atts['link']) && 'none' === $atts['link']) {
				$image_output = wp_get_attachment_image($id, $atts['size'], false, $attr);
			} else {
				// $image_output = wp_get_attachment_link( $id, $atts['size'], true, false, false, $attr );
				$image_output = "<a data-fancybox='gallery-$sgid' data-caption='" . wptexturize($attachment->post_excerpt) . "' href='" . wp_get_attachment_url($id) . "'> " . wp_get_attachment_image($id, $atts['size'], false, $attr) . "</a>";
			}
			$image_meta  = wp_get_attachment_metadata($id);

			$orientation = '';
			if (isset($image_meta['height'], $image_meta['width'])) {
				$orientation = ($image_meta['height'] > $image_meta['width']) ? 'portrait' : 'landscape';
			}
			$output .= "<{$itemtag} class='gallery-item'>";
			$output .= "
			<{$icontag} class='gallery-icon {$orientation}'>
				$image_output
			</{$icontag}>";
			if ($captiontag && trim($attachment->post_excerpt)) {
				$output .= "
				<{$captiontag} class='wp-caption-text gallery-caption' id='$selector-$id'>
				" . wptexturize($attachment->post_excerpt) . "
				</{$captiontag}>";
			}
			$output .= "</{$itemtag}>";
			if (!$html5 && $columns > 0 && ++$i % $columns == 0) {
				$output .= '<br style="clear: both" />';
			}
		}

		if (!$html5 && $columns > 0 && $i % $columns !== 0) {
			$output .= "
			<br style='clear: both' />";
		}

		$output .= "
		</div>\n";

		return $output;
	}
}

?>

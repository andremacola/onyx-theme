<?php
/**
 * Onyx Base Controller
 *
 * @phpcs:disable PEAR.Functions.FunctionCallSignature.ContentAfterOpenBracket
 * @phpcs:disable PEAR.Functions.FunctionCallSignature.CloseBracketLine
 *
 * @package Onyx Theme
 */

namespace Onyx\Controllers;

use Timber\Timber;
use Timber\PostQuery;
use Timber\Post;

class Controller {

	/**
	 * Twig Templates
	 *
	 * @var array
	 */
	protected $templates;

	/**
	 * Global context for Timber
	 *
	 * @var object
	 */
	protected $context;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->add_timber_context_filter();
		$this->context = Timber::get_context();
	}

	/**
	 * Add Timber Context Filter
	 *
	 * @return void
	 */
	protected function add_timber_context_filter() {
		add_filter( 'timber/context', [ $this, 'set_global_context' ] );
	}

	/**
	 * Set Timber Global Context
	 *
	 * @param mixed $context Received from `timber/context` filter
	 */
	public function set_global_context( $context ) {
		$context['theme']->uri = \Onyx\O::conf( 'app' )->dir_uri;
		return $context;
	}

	/**
	 * Render Timber/Twig templates
	 *
	 * @return bool|string — The echoed output.
	 */
	protected function render_view() {
		return Timber::render( $this->templates, $this->context );
	}

	/**
	 * Get post
	 *
	 * @param int $pid Post ID [optional]
	 * @return object
	 */
	protected function get_post( $pid = false ) {
		return new Post( $pid );
	}

	/**
	 * Get posts from default/main loop
	 *
	 * @param array $args `WP_Query` array [optional]
	 * @return object
	 */
	protected function get_posts( $args = false ) {
		return new PostQuery( $args );
	}

	/**
	 * Set Page/Single controller templates hierarchy
	 *
	 * @param Post   $post The post object [required]
	 * @param string $prefix Default is `default` [optional]
	 * @param string $folder Default is `pages` [optional]
	 * @return void
	 */
	protected function set_page_templates( Post $post, $prefix = 'default', $folder = 'pages' ) {
		$this->templates = [
			"$folder/$prefix-$post->ID.twig",
			"$folder/$prefix-$post->post_type.twig",
			"$folder/$prefix-$post->slug.twig",
			"$folder/$prefix.twig",
		];
	}

	/**
	 * Set Archives templates for each type of archive
	 *
	 * @return void
	 */
	protected function set_archive_templates() {
		if ( is_post_type_archive() ) {
			$this->set_post_types_templates();
		} elseif ( is_date() ) {
			$this->set_date_templates();
		} else {
			$this->set_taxonomy_templates();
		}
	}

	/**
	 * Set Dates Archive controller templates hierarchy
	 *
	 * @param string $folder Default is `pages` [optional]
	 * @return void
	 */
	protected function set_date_templates( $folder = 'pages' ) {
		$this->templates = [
			"$folder/archive-date.twig",
			"$folder/archive.twig",
		];
	}

	/**
	 * Set Post Types Archive controller templates hierarchy
	 *
	 * @param string $folder Default is `pages` [optional]
	 * @return void
	 */
	protected function set_post_types_templates( $folder = 'pages' ) {
		$post_type = get_post_type();

		$this->templates = [
			"$folder/home-$post_type.twig",
			"$folder/index-$post_type.twig",
			"$folder/archive-$post_type.twig",
			"$folder/archive.twig",
		];
	}

	/**
	 * Set Category/Tags/Taxonomies controller templates hierarchy
	 *
	 * @param string $folder Default is `pages` [optional]
	 * @return void
	 */
	protected function set_taxonomy_templates( $folder = 'pages' ) {
		$term           = get_queried_object();
		$term->taxonomy = ('post_tag' === $term->taxonomy) ? 'tag' : $term->taxonomy;

		$this->templates = [
			"$folder/$term->taxonomy-$term->term_id.twig",
			"$folder/$term->taxonomy-$term->slug.twig",
			"$folder/$term->taxonomy.twig",
			"$folder/archive.twig",
		];
	}

}

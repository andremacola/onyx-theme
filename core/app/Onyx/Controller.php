<?php
/**
 * Onyx Base Controller
 *
 * @see https://andremacola.github.io/onyx-theme-doc/docs/controllers
 * @package Onyx Theme
 */

namespace Onyx;

use Onyx\Helpers as O;
use Timber\Timber;
use Timber\PostQuery;
use Timber\Post;

abstract class Controller {

	/**
	 * Twig Templates
	 *
	 * @var string|array
	 */
	protected $templates;

	/**
	 * Context for Timber
	 *
	 * @var array
	 */
	protected $context;

	/**
	 * Automatic render templates
	 * Default: true
	 *
	 * @var bool
	 */
	protected $render = true;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->get_timber_context();
		$this->initialize();
		$this->render_view();
	}

	/**
	 * Initialize function
	 *
	 * Required on child classes. Need to pass `context` and `template` parameters
	 */
	abstract public function initialize();

	/**
	 * Add and get Timber Context Filter
	 *
	 * @return void
	 */
	protected function get_timber_context() {
		add_filter( 'timber/context', [ $this, 'set_global_context' ] );
		$this->context = Timber::get_context();
	}

	/**
	 * Set Timber Global Context
	 *
	 * @param mixed $context Received from `timber/context` filter
	 */
	public function set_global_context( $context ) {
		$context['theme']->img = O::conf( 'env' )->dir_uri . '/assets/images';
		return $context;
	}

	/**
	 * Render Timber/Twig templates
	 *
	 * @return bool|string — The echoed output.
	 * @throws \Exception If no templates passed.
	 */
	protected function render_view() {
		if ( empty( $this->templates ) ) {
			throw new \Exception( 'No templates found', 1 );
		}

		if ( $this->render ) {
			return Timber::render( $this->templates, $this->context );
		}

		return false;
	}

	/**
	 * Disable Timber::render
	 *
	 * @return void
	 */
	protected function no_render() {
		$this->render = false;
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
	 * Set context parameter
	 *
	 * @param string|array $data Twig templates [required];
	 * @param mixed        $value Twig templates [optional];
	 * @return void
	 */
	protected function set_context( $data, $value = null ) {
		if ( is_string( $data ) ) {
			$this->context[$data] = $value;
		} else {
			$this->context = $data;
		}
	}

	/**
	 * Get context
	 *
	 * @param string $context [optional];
	 * @return mixed
	 */
	protected function get_context( $context = false ) {
		if ( isset( $context ) && is_string( $context ) ) {
			return (isset( $this->context[$context] )) ? $this->context[$context] : false;
		}

		return (isset( $this->context )) ? $this->context : false;
	}

	/**
	 * Set templates
	 *
	 * @param string|array $templates Twig templates [required];
	 * @return void
	 */
	protected function set_templates( $templates ) {
		$this->templates = $templates;
	}

	/**
	 * Set Page/Single controller templates hierarchy
	 *
	 * @param string $prefix Default is `default` [optional]
	 * @param string $folder Default is `pages` [optional]
	 * @return void
	 */
	protected function set_page_templates( $prefix = 'default', $folder = 'pages' ) {
		$post = $this->get_context( 'post' );

		if ( $post ) {
			$this->templates = [
				"$folder/$prefix-$post->ID.twig",
				"$folder/$prefix-$post->post_type.twig",
				"$folder/$prefix-$post->slug.twig",
				"$folder/$prefix.twig",
			];
		} else {
			$this->templates[] = "$folder/$prefix.twig";
		}
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
	 * @param string $prefix Default is `archive` [optional]
	 * @param string $folder Default is `pages` [optional]
	 * @return void
	 */
	protected function set_date_templates( $prefix = 'archive', $folder = 'pages' ) {
		$this->templates = [
			"$folder/$prefix-date.twig",
			"$folder/$prefix.twig",
		];
	}

	/**
	 * Set Post Types Archive controller templates hierarchy
	 *
	 * @param string $prefix Default is `archive` [optional]
	 * @param string $folder Default is `pages` [optional]
	 * @return void
	 */
	protected function set_post_types_templates( $prefix = 'archive', $folder = 'pages' ) {
		$post_type = get_post_type();

		$this->templates = [
			"$folder/home-$post_type.twig",
			"$folder/index-$post_type.twig",
			"$folder/$prefix-$post_type.twig",
			"$folder/$prefix.twig",
		];
	}

	/**
	 * Set Category/Tags/Taxonomies controller templates hierarchy
	 *
	 * @param string $prefix Default is `archive` [optional]
	 * @param string $folder Default is `pages` [optional]
	 * @return void
	 */
	protected function set_taxonomy_templates( $prefix = 'archive', $folder = 'pages' ) {
		$term           = get_queried_object();
		$term->taxonomy = ('post_tag' === $term->taxonomy) ? 'tag' : $term->taxonomy;

		$this->templates = [
			"$folder/$term->taxonomy-$term->term_id.twig",
			"$folder/$term->taxonomy-$term->slug.twig",
			"$folder/$term->taxonomy.twig",
			"$folder/$prefix.twig",
		];
	}

}

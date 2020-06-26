<?php
/**
 * Single Template.
 *
 * @package Onyx Theme
 */

get_header();

while ( have_posts() ) : the_post();

	the_content();

endwhile;

get_sidebar();
get_footer();

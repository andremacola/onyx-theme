<?php get_header(); ?>

<?php

	while(have_posts()): the_post();

	the_content();

	endwhile;

?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>

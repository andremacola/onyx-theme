<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Onyx Theme
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password,
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="post-comments <?php echo get_option( 'show_avatars' ) ? 'show-avatars' : ''; ?>">

<?php

if ( have_comments() ) {


	$label = (object) [
		'comment'      => __( 'Comment', 'onyx-theme' ),
		'comments_for' => __( 'comments for', 'onyx-theme' ),
	];

	$post_title    = get_the_title();
	$comment_count = get_comments_number();
	$comment_title = "$comment_count $label->comments_for &#8220; $post_title &#8221;";
	$comment_title = ( '1' === $comment_count ) ? "$comment_count $label->comment" : $comment_title;

	$comment_list = wp_list_comments([
		'avatar_size' => 48,
		'style'       => 'ol',
		'echo'        => false,
		'format'      => 'html5',
	]);

	$html = "
		<h6 class='comments-title'>$comment_title</h6>
		<ol class='comment-list'>$comment_list</ol>
	";

	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo $html;

	if ( ! comments_open() ) {
			echo '<p class="no-comments">' . esc_html__( 'Comments are closed.', 'onyx-theme' ) . '</p>';
	}

	// phpcs:disable
	// the_comments_pagination(
	// 	array(
	// 		'before_page_number' => esc_html__( 'Page', 'twentytwentyone' ) . ' ',
	// 		'mid_size'           => 0,
	// 		'prev_text'          => sprintf(
	// 			'%s <span class="nav-prev-text">%s</span>',
	// 			is_rtl() ? '»' : '«',
	// 			esc_html__( 'Previous', 'twentytwentyone' )
	// 		),
	// 		'next_text'          => sprintf(
	// 			'<span class="nav-next-text">%s</span> %s',
	// 			esc_html__( 'Recent', 'twentytwentyone' ),
	// 			is_rtl() ? '«' : '»'
	// 		),
	// 	)
	// );
	// phpcs:enable


}

comment_form([
	'logged_in_as'       => null,
	'title_reply'        => esc_html__( 'Leave a comment', 'onyx-theme' ),
	'title_reply_before' => '<h6 id="reply-title" class="comment-reply-title">',
	'title_reply_after'  => '</h6>',
]);

?>

</div>

<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
ob_start();
	if ( have_comments() ) {
			$comments_number = number_format_i18n( get_comments_number() );
			echo "<div class='comments_title ce_title'> " . esc_html__( "Comments", 'relish' ) . " <span>($comments_number)</span>" . "</div>";

			wp_list_comments( array(
				'walker' => new CWS_Walker_Comment(),
				'avatar_size' => 78,
			) );

			relish_comment_nav();

	} // have_comments()

	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) {
		echo apply_filters( 'the_content', "[cws_sc_msg_box type='info' is_closable='1' text='" . esc_html__( 'Comments are closed.', 'relish' ) . "'][/cws_sc_msg_box]" );
	}

	$comment_form_args = array(
		'label_submit' => esc_html__( 'Send', 'relish' )
	);
	ob_start();
	comment_form( $comment_form_args );
	$comment_form = ob_get_clean();
	echo trim( $comment_form );

$comments_section_content = ob_get_clean();
echo !empty( $comments_section_content ) ? "<div class='grid_row'><div class='grid_col grid_col_12'><div class='cols_wrapper'><div id='comments' class='comments-area'>$comments_section_content</div></div></div></div>" : "";
?>

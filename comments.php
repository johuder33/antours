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

global $domain, $loaderText;

if ( post_password_required() ) {
	return;
}

global $commentLimit;

?>

<div id="comments" class="comments-container">
	<?php if ( have_comments() ) : ?>
		<h1 class="page-header page-header-package">
			<?php
				printf( __( 'Comments title %s', $domain ), get_the_title(), get_comments_number() );
			?>
		</h1>

		<div class="comment-list">
			<?php
				$comments = get_comments(array('number' => $commentLimit, 'offset' => 0, 'order' => 'DESC' ));

				foreach($comments as $comment) {
					get_template_part('content', 'comment');
				}

				/*wp_list_comments( array(
					'reverse_top_level' => true,
					'callback' => 'render_comment_start',
					'end-callback' => 'render_comment_end',
					'reverse_children' => true,
					'page' => 1,
					'per_page' => 1
				) );*/
			?>
		</div><!-- .comment-list -->
		
		<?php
			if(get_comments_number() > $commentLimit) {
				?>
				<div class="center-block text-center load-btn-container">
					<button class="btn btn-default btnOrange" id="load-comments">
						<i class="fa fa-refresh fa-spin hide" id="progress-icon"></i>
						<span id="load-btn-text"><?php echo $loaderText; ?></span>
					</button>
				<div>
				<?php
			}
		?>

	<?php endif; // have_comments() ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php _e( 'Comments are closed.', 'antours' ); ?></p>
	<?php endif; ?>

	<?php //comment_form(); comment out our form to not allow users post their comments, it's only handle by admin site' ?>

</div><!-- .comments-area -->

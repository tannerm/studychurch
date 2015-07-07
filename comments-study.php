<?php
$data_type = get_post_meta( get_the_ID(), '_sc_data_type', true );

if ( ! in_array( $data_type, array( 'question_long', 'question_short' ) ) ) {
	return;
}

$answer = get_comments( array(
	'post_id'    => get_the_ID(),
	'number'     => 1,
	'author__in' => get_current_user_id(),
) );

$comment_id = ( empty( $answer[0]->comment_ID ) ) ? 0 : $answer[0]->comment_ID;
$answer     = ( empty( $answer[0]->comment_content ) ) ? '' : $answer[0]->comment_content;
?>

<div id="comments" class="comments-area">


	<?php if ( comments_open( get_the_ID() ) ) : ?>
		<div id="respond" class="comment-respond">
			<form action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post" class="comment-form" novalidate>
				<p class="comment-form-comment">
					<textarea id="comment" name="comment" cols="45" rows="<?php echo ( 'question_long' == $data_type ) ? '5' : '1'; ?>" aria-describedby="form-allowed-tags" aria-required="true" required="required" placeholder="Your answer"><?php echo esc_textarea( $answer ); ?></textarea>
				</p>
				<p class="form-submit">
					<input name="submit" type="submit" id="submit" class="submit" value="Save" />
					<input type='hidden' name='comment_post_ID' value='<?php the_ID(); ?>' id='comment_post_ID' />
					<input type='hidden' name='comment_ID' value='<?php echo esc_attr( $comment_id ); ?>' id='comment_ID' />
					<input type='hidden' name='comment_parent' id='comment_parent' value='0' />
				</p>
			</form>
		</div><!-- #respond -->
	<?php endif; ?>


</div><!-- #comments -->

<?php
$data_type = sc_get_data_type();

if ( ! in_array( $data_type, array( 'question_long', 'question_short' ) ) ) {
	return;
}

$answer = sc_study_get_answer();

$comment_id = ( empty( $answer->comment_ID ) ) ? 0 : $answer->comment_ID;
$answer     = ( empty( $answer->comment_content ) ) ? '' : $answer->comment_content;
?>

<div id="comments" class="comments-area" <?php echo ( $answer ) ? 'style="display:none"' : ''; ?> >


	<?php if ( comments_open( get_the_ID() ) ) : ?>
		<div id="respond" class="comment-respond">
			<form action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post" class="comment-form" novalidate>
				<textarea id="comment" name="comment" cols="45" rows="<?php echo ( 'question_long' == $data_type ) ? '5' : '1'; ?>" aria-describedby="form-allowed-tags" aria-required="true" required="required" placeholder="Your answer"><?php echo esc_textarea( $answer ); ?></textarea>
				<p class="form-submit small text-right">
					<a href="#" class="sudo-save">Save</a>
					<input name="submit" type="submit" id="submit-<?php the_ID(); ?>" class="submit screen-reader-text" value="Save" />
					<input type='hidden' name='comment_post_ID' value='<?php the_ID(); ?>' id='comment_post_ID' />
					<input type='hidden' name='comment_ID' value='<?php echo esc_attr( $comment_id ); ?>' id='comment_ID' />
					<input type='hidden' name='group_ID' value='<?php echo esc_attr( bp_get_group_id() ); ?>' id='group_ID' />
					<input type='hidden' name='comment_parent' id='comment_parent' value='0' />
				</p>
			</form>
		</div><!-- #respond -->
	<?php endif; ?>


</div><!-- #comments -->

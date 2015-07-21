<?php global $item, $study_id; ?>

<a href="<?php echo add_query_arg( array(
	'action' => 'edit',
	'study'  => $study_id,
), get_the_permalink() ); ?>"><?php _e( 'Back to the study.', 'sc' ); ?></a>

<form action="" method="post" id="study-edit">

	<label for="study-title"><?php _e( 'Study Title', 'sc' ); ?></label>
	<input type="text" id="study-title" name="study-title" value="<?php echo esc_attr( get_the_title( $item->ID ) ); ?>" placeholder="<?php _e( 'Enter study title here...', 'sc' ); ?>" />

	<label for="study-thesis"><?php _e( 'Study Description', 'sc' ); ?></label>
	<textarea id="study-thesis" name="study-thesis" rows="10"><?php echo esc_textarea( $item->post_excerpt ); ?></textarea>

	<label for="study-description"><?php _e( 'Study Introduction', 'sc' ); ?></label>
	<?php wp_editor( $item->post_content, 'study-description', array( 'teeny' => true, 'quicktags' => false ) ); ?>


	<input type="hidden" name="study_id" value="<?php echo $study_id; ?>" />
	<input type="hidden" name="step" value="<?php echo sc_study_edit_get_current_step( $study_id ); ?>" />
	<?php wp_nonce_field( 'study-save', 'study-save-nonce' ); ?>

	<input type="submit" class="button secondary expand" name="save" value="<?php _e( 'Save', 'sc' ); ?>" />

</form>

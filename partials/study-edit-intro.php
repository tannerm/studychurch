<?php $study_id = absint( sc_get( 'study', 0 ) ); ?>

<h3><?php echo get_the_title( $study_id ); ?></h3>

<h4><?php _e( 'Study Introduction', 'sc' ); ?></h4>
<form action="" method="post" id="study-edit">

	<?php wp_editor( '', 'study-description', array( 'teeny' => true, 'quicktags' => false ) ); ?>

	<input type="hidden" name="study_id" value="<?php echo $study_id; ?>" />
	<input type="hidden" name="step" value="<?php echo sc_study_edit_get_current_step( $study_id ); ?>" />
	<?php wp_nonce_field( 'study-save', 'study-save-nonce' ); ?>

	<input type="submit" value="Save & Next" class="button expand secondary" />

</form>
<?php $study_id = absint( sc_get( 'study', 0 ) ); ?>

<h3><?php echo get_the_title( $study_id ); ?></h3>

<h4><?php _e( 'Study Format', 'sc' ); ?></h4>
<form action="" method="post" id="study-edit">
	<label><input type="radio" name="study-format" value="lesson" /> Chapters do not have subsections.</label>
	<label><input type="radio" name="study-format" value="week-5" /> Each chapter has 5 subsections. Used for week studies with 5 days.</label>
	<label><input type="radio" name="study-format" value="week-7" /> Each chapter has 7 subsections. Used for week studies with 7 days.</label>

	<input type="hidden" name="study_id" value="<?php echo $study_id; ?>" />
	<input type="hidden" name="step" value="<?php echo sc_study_edit_get_current_step( $study_id ); ?>" />
	<?php wp_nonce_field( 'study-save', 'study-save-nonce' ); ?>

	<input type="submit" value="Save & Next" class="button expand secondary" />

</form>
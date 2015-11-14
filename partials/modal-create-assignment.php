<div id="create-assignment" class="reveal-modal small" data-reveal>

	<h2><?php printf( __( 'Create an assignment for %s', 'sc' ), bp_get_current_group_name() ); ?></h2>

	<form method="post" action="" class="create-assignments">

		<?php if ( $study_id = sc_get_group_study_id() ) : ?>
			<h4 class="no-margin"><?php _e( 'Lessons', 'sc' ); ?></h4>
			<ul>
				<?php foreach ( sc_study_get_navigation( $study_id ) as $chapter ) : ?>
					<li>
						<label><input type="checkbox" name="lessons[]" value="<?php echo $chapter->ID; ?>" /> <?php echo get_the_title( $chapter->ID ); ?>
						</label>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<h4><?php _e( 'Instructions', 'sc' ); ?></h4>

		<p>
			<textarea name="content" class="froala-inline" placeholder="<?php _e( 'Enter assignment instructions...', 'sc' ); ?>"></textarea>
		</p>

		<h4><?php _e( 'Due Date', 'sc' ); ?></h4>

		<div class="small-6 date fdatepicker" data-date="<?php echo date( 'm/d/Y', current_time( 'timestamp') + WEEK_IN_SECONDS ); ?>" data-date-format="mm/dd/yyyy">
			<input size="16" type="text" name="date" value="<?php echo date( 'm/d/Y', current_time( 'timestamp') + WEEK_IN_SECONDS ); ?>">
			<span class="postfix end calendar-button"><i class="fa fa-calendar"></i></span>
		</div>

		<br />

		<?php wp_nonce_field( 'create_new_assignment', 'new_assignment_nonce' ); ?>
		<input type="submit" class="button secondary expand" value="<?php _e( 'Save Assignment', 'sc' ); ?>" />

	</form>
	<a class="close-reveal-modal">&#215;</a>

</div>
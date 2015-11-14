<?php
$assignments     = new SC_Assignments_Query();
$old_assignments = new SC_Assignments_Query( array(
	'date_start' => date( DATE_RSS, 0 ),
	'date_finish' => date( DATE_RSS, time() )
) );
?>

<h1><?php _e( 'Assignments', 'sc' ); ?></h1>

<?php if ( sc_user_can_manage_group() ) : ?>
	<a href="#" data-reveal-id="create-assignment" class="button expand"><?php _e( "Add a new assignment.", 'sc' ); ?></a>
<?php endif;

if ( $assignments->have_assignments() ) : ?>
	<?php while ( $assignments->the_assignment() ) : ?>
		<div class="panel">
			<?php if ( sc_user_can_manage_group() ) : ?>
				<form action="" method="post" class="right">
					<input type="hidden" name="assignment" value="<?php $assignments->the_key(); ?>" />
					<?php wp_nonce_field( 'delete_assignment', 'delete_assignment_nonce' ); ?>
					<a href="#" onclick="if (window.confirm('Are you sure you want to delete this assignment?')) { jQuery(this).parent().submit(); } return false;"><i class="fa fa-trash"></i></a>
				</form>
			<?php endif; ?>
			<h4><?php _e( 'Assignment Due: ', 'sc' ); ?><?php $assignments->the_date_formatted(); ?></h4>
			<?php $assignments->the_lessons(); ?>
			<?php $assignments->the_content(); ?>
		</div>
	<?php endwhile; ?>
<?php else : ?>
	<p>
		<?php esc_html_e( 'There are no assignment for this group.', 'sc' ); ?>
	</p>
<?php endif; ?>

<?php if ( $old_assignments->have_assignments() ) : ?>
	<hr />

	<h4><?php _e( 'Old Assignments', 'sc' ); ?></h4>

	<?php while ( $old_assignments->the_assignment() ) : ?>
		<div class="panel">
			<?php if ( sc_user_can_manage_group() ) : ?>
				<form action="" method="post" class="right">
					<input type="hidden" name="assignment" value="<?php $old_assignments->the_key(); ?>" />
					<?php wp_nonce_field( 'delete_assignment', 'delete_assignment_nonce' ); ?>
					<a href="#" onclick="if (window.confirm('Are you sure you want to delete this assignment?')) { jQuery(this).parent().submit(); } return false;"><i class="fa fa-trash"></i></a>
				</form>
			<?php endif; ?>
			<h4><?php _e( 'Assignment Due: ', 'sc' ); ?><?php $old_assignments->the_date_formatted(); ?></h4>
			<?php $old_assignments->the_lessons(); ?>
			<?php $old_assignments->the_content(); ?>
		</div>
	<?php endwhile; ?>

<?php endif; ?>

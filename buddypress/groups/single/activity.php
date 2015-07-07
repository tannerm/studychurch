<?php
$assignments = new SC_Assignments_Query( array( 'count' => 1 ) );
?>

<?php if ( $study = groups_get_groupmeta( bp_get_group_id(), 'study_name', true ) ) : ?>
	<h2><?php _e( 'Current Study', 'sc' ); ?>: <?php echo esc_html( $study ); ?> </h2>
<?php endif; ?>

<div class="panel">

	<?php if ( sc_user_can_manage_group() ) : ?>
		<a href="<?php sc_the_assignment_permalink(); ?>" class="alignright ucase small"><?php _e( 'Manage assignments', 'sc' ); ?></a>
	<?php else: ?>
		<a href="<?php sc_the_assignment_permalink(); ?>" class="alignright ucase small"><?php _e( 'See all assignments', 'sc' ); ?></a>
	<?php endif; ?>

	<h3><?php _e( 'Assignments', 'sc' ); ?></h3>

	<?php if ( $assignments->have_assignments() ) : $assignments->the_assignment(); ?>
		<h4><?php _e( 'Assignment Due: ', 'sc' ); ?><?php $assignments->the_date_formatted(); ?></h4>
		<?php $assignments->the_content(); ?>
	<?php else : ?>
		<p>
			<?php esc_html_e( 'There are no assignment for this group.', 'sc' ); ?>
			<?php if ( sc_user_can_manage_group() ) : ?>
				<br /><a href="<?php sc_the_assignment_permalink(); ?>"><?php esc_html_e( 'Add an assignment.', 'sc' ); ?></a>
			<?php endif; ?>
		</p>
	<?php endif; ?>
</div>

<?php do_action( 'bp_before_group_activity_post_form' ); ?>

<?php if ( is_user_logged_in() && bp_group_is_member() ) : ?>

	<?php bp_get_template_part( 'activity/post-form' ); ?>

<?php endif; ?>

<?php do_action( 'bp_after_group_activity_post_form' ); ?>
<?php do_action( 'bp_before_group_activity_content' ); ?>

<div class="activity single-group" role="main">

	<?php bp_get_template_part( 'activity/activity-loop' ); ?>

</div><!-- .activity.single-group -->

<?php do_action( 'bp_after_group_activity_content' ); ?>

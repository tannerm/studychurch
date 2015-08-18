<?php
$assignments = new SC_Assignments_Query( array( 'count' => 1 ) );
?>

<?php if ( 0 && $study = groups_get_groupmeta( bp_get_group_id(), 'study_name', true ) ) : ?>
	<h2><?php _e( 'Current Study', 'sc' ); ?>: <?php echo esc_html( $study ); ?> </h2>
<?php endif; ?>

<div class="panel">

	<?php if ( sc_user_can_manage_group() ) : ?>
		<a href="<?php sc_the_assignment_permalink(); ?>" class="alignright ucase small"><?php _e( 'Manage assignments', 'sc' ); ?></a>
	<?php else: ?>
		<a href="<?php sc_the_assignment_permalink(); ?>" class="alignright ucase small"><?php _e( 'See all assignments', 'sc' ); ?></a>
	<?php endif; ?>

	<?php if ( $assignments->have_assignments() ) : ?>
		<?php while( $assignments->the_assignment() ) : ?>
			<h4><?php _e( 'Assignment Due: ', 'sc' ); ?><?php $assignments->the_date_formatted(); ?></h4>
			<?php $assignments->the_lessons(); ?>
			<?php $assignments->the_content(); ?>
		<?php endwhile; ?>
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

	<?php do_action( 'bp_before_activity_loop' ); ?>

	<?php if ( bp_has_activities( array( 'object' => 'groups' ) ) ) : ?>

		<?php if ( empty( $_POST['page'] ) ) : ?>

			<ul id="activity-stream" class="activity-list item-list">

		<?php endif; ?>

		<?php while ( bp_activities() ) : bp_the_activity(); ?>

			<?php bp_get_template_part( 'activity/entry' ); ?>

		<?php endwhile; ?>

		<?php if ( bp_activity_has_more_items() ) : ?>

			<li class="load-more">
				<a href="<?php bp_activity_load_more_link() ?>"><?php _e( 'Load More', 'buddypress' ); ?></a>
			</li>

		<?php endif; ?>

		<?php if ( empty( $_POST['page'] ) ) : ?>

			</ul>

		<?php endif; ?>

	<?php else : ?>

		<div id="message" class="info">
			<p><?php _e( 'Sorry, there was no activity found. Please try a different filter.', 'buddypress' ); ?></p>
		</div>

	<?php endif; ?>

	<?php do_action( 'bp_after_activity_loop' ); ?>

	<?php if ( empty( $_POST['page'] ) ) : ?>

		<form action="" name="activity-loop-form" id="activity-loop-form" method="post">

			<?php wp_nonce_field( 'activity_filter', '_wpnonce_activity_filter' ); ?>

		</form>

	<?php endif; ?>
</div><!-- .activity.single-group -->

<?php do_action( 'bp_after_group_activity_content' ); ?>

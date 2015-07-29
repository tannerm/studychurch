<?php
$groups = groups_get_user_groups();
$studies = get_pages( 'post_status=publish,pending,draft&post_type=sc_study&parent=0&authors=' . bp_displayed_user_id() )
?>

<?php if ( $groups['total'] ) : ?>
	<div class="panel">

		<h2><?php _e( 'Assignments', 'sc' ); ?></h2>

		<?php foreach ( $groups['groups'] as $group_id ) : $group = groups_get_group( 'group_id=' . $group_id ); ?>
			<?php $assignments                                    = new SC_Assignments_Query( array( 'count'    => 1,
			                                                                                         'group_id' => $group_id
			) ); ?>
			<?php if ( $assignments->have_assignments() ) : $assignments->the_assignment(); ?>
				<h4><?php _e( 'Assignment Due: ', 'sc' ); ?><?php $assignments->the_date_formatted(); ?></h4>
				<?php $assignments->the_content();
				?>
				<p class="text-right small">
					<a href="<?php echo get_the_permalink( sc_get_group_study_id( $group_id ) ); ?>"><?php echo get_the_title( sc_get_group_study_id( $group_id ) ); ?></a> |
					<a href="<?php bp_group_permalink( $group ); ?>"><?php bp_group_name( $group ); ?></a>
				</p>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
<?php endif; ?>

<h2><?php _e( 'Recent Activity', 'sc' ); ?></h2>

<div class="activity no-ajax" role="main">
	<?php if ( $groups['total'] && bp_has_activities( array(
			'user_id'    => false,
			'primary_id' => $groups['groups'],
			'object'     => 'groups',
			'per_page'   => 5
		) )
	) : ?>

		<ul id="activity-stream" class="activity-list item-list">
			<?php while ( bp_activities() ) : bp_the_activity(); ?>

				<?php bp_get_template_part( 'activity/entry' ); ?>

			<?php endwhile; ?>
		</ul>
	<?php else : ?>
		<p><?php _e( 'There is no activity to report. Please <a href="/groups">join a group</a> or <a href="#" data-reveal-id="group-create-modal">create one</a>.', 'sc' ); ?></p>
	<?php endif; ?>
</div>
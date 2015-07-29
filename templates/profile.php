<?php

/*
 * Template Name: Profile
 */

get_header(); ?>

	<div id="buddypress" class="row">
		<div class="small-4 column sidebar">

			<?php bp_get_template_part( 'members/single/member-header' ) ?>

			<hr />

			<?php sc_add_button( '#', 'Create new group', 'group-create right', ' data-reveal-id="group-create-modal"' ); ?>
			<h4><?php _e( 'Groups', 'sc' ); ?></h4>

			<ul>
				<?php
				$groups = groups_get_user_groups( get_current_user_id() );
				foreach ( $groups['groups'] as $group ) {
					$group = groups_get_group( array( 'group_id' => $group ) );
					printf( '<li><a href="%s">%s</a></li>', esc_url( trailingslashit( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/' . $group->slug . '/' ) ), esc_html( $group->name ) );
				}
				?>
			</ul>

			<hr />

			<?php sc_add_button( '#', 'Create new study', 'study-create right', ' data-reveal-id="study-create-modal"' ); ?>
			<h4><?php _e( 'Studies', 'sc' ); ?></h4>
			<ul>
				<?php
				if ( $studies = get_pages( 'post_status=publish,pending,draft&post_type=sc_study&parent=0&authors=' . get_current_user_id() ) ) {
					foreach ( $studies as $study ) {
						$title = get_the_title( $study->ID );

						if ( 'publish' != get_post_status( $study->ID ) ) {
							$title .= sprintf( ' (pending review)' );
						}

						printf( '<li><a href="/study-edit/?action=edit&study=%d">%s</a></li>', $study->ID, esc_html( $title ) );
					}
				} else {
					printf( '<a href="#">Write your first study</a>' );
				}

				?>
			</ul>

			<hr />

			<?php sc_add_button( '#', 'Create new organization', 'organization-create right', ' data-reveal-id="organization-create-modal"' ); ?>
			<h4><?php _e( 'Organizations', 'sc' ); ?></h4>
			<ul>
				<li>Christ's Community Church</li>
			</ul>
		</div>

		<!-- role="main" -->

		<div class="small-8 column" role="main">

			<?php if ( $groups['total'] ) : ?>
				<div class="panel">

					<h2><?php _e( 'Assignments', 'sc' ); ?></h2>

					<?php foreach ( $groups['groups'] as $group_id ) : $group = groups_get_group( 'group_id=' . $group_id ); ?>
						<?php $assignments = new SC_Assignments_Query( array( 'count' => 1, 'group_id' => $group_id ) ); ?>
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
		</div>
	</div>

<?php get_footer(); ?>
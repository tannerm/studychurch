<div id="buddypress" class="row">

	<?php if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); ?>

		<div class="columns medium-4">

			<?php if ( bp_is_item_mod() || bp_is_item_admin() ) : ?>
				<a href="<?php bp_group_admin_permalink(); ?>" class="group-edit-link right"><i class="fa fa-pencil"></i></a>
			<?php endif; ?>

			<h3><a href="<?php bp_group_permalink(); ?>"><?php bp_group_name(); ?></a></h3>

			<p><?php bp_group_description(); ?></p>

			<?php if ( $study_id = sc_get_group_study_id() ) : ?>
				<h3><?php _e( 'Study:', 'sc' ); ?> <a href="<?php echo get_the_permalink( $study_id ); ?>"><?php echo get_the_title( $study_id ); ?></a></h3>
				<p><?php echo apply_filters( 'the_excerpt', get_post( $study_id )->post_excerpt ); ?></p>
			<?php elseif( $study = groups_get_groupmeta( bp_get_group_id(), 'study_name', true ) ) : ?>
				<h3><?php _e( 'Study:', 'sc' ); ?> <?php echo esc_html( $study ); ?></h3>
			<?php endif; ?>

			<hr />

			<h3><?php _e( 'Members', 'sc' ); ?></h3>
			<ul class="item-list">
				<?php if ( bp_group_has_members( array(
					'per_page'            => 1000,
					'exclude_admins_mods' => 0
				) ) ) : while ( bp_group_members() ) : bp_group_the_member(); ?>
					<li>
						<?php bp_group_member_avatar_thumb(); ?>
						<?php bp_group_member_name(); ?>
					</li>
				<?php endwhile; endif; ?>
			</ul>

		</div>

		<div class="columns medium-8">
			<?php

			if ( bp_is_group_admin_page() ) {
				bp_get_template_part( 'groups/single/admin' );
			} elseif ( 'assignments' == bp_current_action() ) {
				bp_get_template_part( 'groups/single/assignments' );
			} else {
				bp_get_template_part( 'groups/single/activity' );
			} ?>
		</div>

	<?php endwhile; endif; ?>

</div><!-- #buddypress -->

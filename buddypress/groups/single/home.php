<div id="buddypress" class="row">

	<?php if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); ?>

		<div class="columns medium-4">
			<h3><?php bp_group_name(); ?></h3>

			<p><?php bp_group_description(); ?></p>

			<hr />

			<h3>Members</h3>
			<ul>
				<?php if ( bp_group_has_members( array( 'per_page' => 1000, 'exclude_admins_mods' => 0 ) ) ) : while ( bp_group_members() ) : bp_group_the_member(); ?>
					<li>
						<?php bp_group_member_avatar_thumb(); ?>
						<?php bp_group_member_name(); ?>
					</li>
				<?php endwhile; endif; ?>
			</ul>

		</div>

		<div class="columns medium-8">
			<?php if ( $study = groups_get_groupmeta( bp_get_group_id(), 'study_name', true ) ) : ?>
				<h4><?php _e( 'Current Study', 'sc' ); ?>: <?php echo esc_html( $study ); ?> </h4>
			<?php endif; ?>
			<?php bp_get_template_part( 'groups/single/activity' ); ?>

		</div>

		<?php do_action( 'bp_after_group_home_content' ); ?>

	<?php endwhile; endif; ?>

</div><!-- #buddypress -->

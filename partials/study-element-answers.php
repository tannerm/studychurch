<?php $answer = sc_study_get_answer(); ?>
<div class="study-answers activity">

	<?php if ( sc_answer_is_private( $answer->comment_post_ID ) ) : ?>
		<ul id="activity-stream" class="activity-list item-list">
			<li>
				<div class="activity-header">
					<div class="activity-avatar">
						<?php echo bp_core_fetch_avatar( 'item_id=' . $answer->user_id ); ?>
					</div>
					<p class="small">
						<span class="time-since"><?php echo bp_core_time_since( $answer->comment_date ); ?></span><br />
						<?php _e( 'Your answer', 'sc' ); ?>
					</p>
				</div>

				<div class="activity-inner">
					<?php echo $answer->comment_content; ?>
				</div>

				<p class="small text-right">
					<a href="#"><?php _e( 'Edit', 'sc' ); ?></a>
				</p>
			</li>
		</ul>
	<?php else : ?>

		<ul id="activity-stream" class="activity-list-mine item-list">
			<?php if ( bp_has_activities( 'include=' . sc_answer_get_activity_id( $answer->comment_ID ) ) ) : ?>
				<?php while ( bp_activities() ) : bp_the_activity(); ?>
					<?php bp_get_template_part( 'activity/entry', 'study' ); ?>
				<?php endwhile; ?>
			<?php endif ;?>
		</ul>
		<ul id="activity-stream" class="activity-list-others item-list">
			<p class="text-right small group-answer-toggle-container">
				<a href="#" class="hide-answers toggle-answers"><?php _e( 'Hide Group Answers', 'sc' ); ?></a>
				<a href="#" class="show-answers toggle-answers"><?php _e( 'Show Group Answers', 'sc' ); ?></a>
			</p>
			<?php if ( bp_has_activities( array( 'primary_id' => sc_get_study_user_group_id(), 'secondary_id' => $answer->comment_post_ID, 'action' => 'answer_update', 'exclude' => sc_answer_get_activity_id( $answer->comment_ID ) ) ) ) : ?>
				<?php while ( bp_activities() ) : bp_the_activity(); ?>
					<?php bp_get_template_part( 'activity/entry', 'study' ); ?>
				<?php endwhile; ?>
			<?php endif ;?>
		</ul>
	<?php endif; ?>

</div>
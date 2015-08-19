<?php
global $sc_answer;
if ( empty( $sc_answer ) ) {
	return;
}

$activity_args = array(
	'primary_id'   => sc_get_study_user_group_id( $sc_answer->comment_post_ID ),
	'secondary_id' => $sc_answer->comment_post_ID,
	'action'       => 'answer_update',
	'exclude'      => sc_answer_get_activity_id( $sc_answer->comment_ID )
);
?>
<div class="study-answers activity">
	<?php if ( sc_answer_is_private( $sc_answer->comment_post_ID ) ) : ?>
		<ul id="activity-stream" class="activity-list item-list">
			<li>
				<div class="activity-header">
					<div class="activity-avatar left">
						<?php echo bp_core_fetch_avatar( 'item_id=' . $sc_answer->user_id ); ?>
					</div>
					<p class="small">
						<span class="time-since"><?php echo bp_core_time_since( $sc_answer->comment_date ); ?></span><br />
						<?php _e( 'Your answer', 'sc' ); ?>
					</p>
				</div>

				<div class="activity-inner">
					<?php echo $sc_answer->comment_content; ?>
				</div>

				<p class="small text-right">
					<a href="#" class="edit-answer"><?php _e( 'Edit', 'sc' ); ?></a>
				</p>
			</li>
		</ul>
	<?php else : ?>

		<ul id="activity-stream" class="activity-list activity-list-mine item-list">
			<?php if ( bp_has_activities( 'include=' . sc_answer_get_activity_id( $sc_answer->comment_ID ) ) ) : ?>
				<?php while ( bp_activities() ) : bp_the_activity(); ?>
					<?php bp_get_template_part( 'activity/entry', 'study' ); ?>
				<?php endwhile; ?>
			<?php endif; ?>
		</ul>

		<?php if ( bp_has_activities( $activity_args ) ) : ?>
			<ul id="activity-stream" class="activity-list activity-list-others item-list answers-hide">
				<p class="text-right small group-answer-toggle-container">
					<a href="#" class="hide-answers toggle-answers button expand tiny no-margin"><?php _e( 'Hide Group Answers', 'sc' ); ?></a>
					<a href="#" class="show-answers toggle-answers button expand tiny no-margin"><?php _e( 'Show Group Answers', 'sc' ); ?> (<?php bp_activity_count(); ?>)</a>
				</p>
				<?php while ( bp_activities() ) : bp_the_activity(); ?>
					<?php bp_get_template_part( 'activity/entry', 'study' ); ?>
				<?php endwhile; ?>
			</ul>
		<?php else : ?>
			<p class="small description"><?php _e( 'No other group answers yet, please check back later.', 'sc' ); ?></p>
		<?php endif; ?>

	<?php endif; ?>

</div>
<?php

/**
 * BuddyPress - Activity Stream (Single Item)
 *
 * This template is used by activity-loop.php and AJAX functions to show
 * each activity.
 *
 * @package    BuddyPress
 * @subpackage bp-legacy
 */

global $activities_template;
?>

<?php do_action( 'bp_before_activity_entry' ); ?>

<li class="<?php bp_activity_css_class(); ?>" id="activity-<?php bp_activity_id(); ?>">

	<div class="activity-content">

		<div class="activity-header">
			<div class="activity-avatar">
				<?php bp_activity_avatar(); ?>
			</div>

			<?php if ( 'sc_study' == get_post_type() ) : ?>
				<p class="small">
					<span class="time-since"><?php echo bp_core_time_since( $activities_template->activity->date_recorded ); ?></span><br />
					<?php if ( get_current_user_id() == bp_get_activity_user_id() ) : ?>
						<?php _e( 'Your answer', 'sc' ); ?>
					<?php else : ?>
						<?php echo bp_core_get_user_displayname( bp_get_activity_user_id() ); ?> <?php _e( 'answered', 'sc' ); ?>
					<?php endif; ?>
				</p>
			<?php else : ?>
				<p class="small">
					<?php if ( bp_is_user_profile() && $group = groups_get_group( 'group_id=' . bp_get_activity_item_id() ) ) : ?>
						<span class="action"><?php echo $activities_template->activity->action; ?></span>&nbsp;|&nbsp;<a href="<?php bp_group_permalink( $group ); ?>"><?php bp_group_name( $group ); ?></a>
					<?php else : ?>
						<span class="action one-line"><?php echo $activities_template->activity->action; ?></span>
					<?php endif; ?>
					<span class="time-since block"><?php echo bp_core_time_since( $activities_template->activity->date_recorded ); ?></span>
				</p>
			<?php endif; ?>
		</div>

		<?php // only show answer update content when there is a discussion on it. ?>
		<?php if ( bp_activity_has_content() && ( 'sc_study' == get_post_type() || 'answer_update' != bp_get_activity_type() || bp_activity_get_comment_count() ) ) : ?>

			<div class="activity-inner">

				<?php bp_activity_content_body(); ?>

			</div>

		<?php endif; ?>

		<?php do_action( 'bp_activity_entry_content' ); ?>

		<?php if ( 'answer_update' != bp_get_activity_type() || bp_activity_get_comment_count() || 'sc_study' == get_post_type() ) : ?>
			<p class="activity-meta small">

				<?php if ( bp_get_activity_type() == 'activity_comment' ) : ?>

					<a href="<?php bp_activity_thread_permalink(); ?>" class="view" title="<?php esc_attr_e( 'View Conversation', 'buddypress' ); ?>"><?php _e( 'View Conversation', 'buddypress' ); ?></a>&nbsp;|&nbsp;

				<?php endif; ?>

				<?php if ( is_user_logged_in() ) : ?>

					<?php if ( bp_activity_get_comment_count() ) : ?>
						<a href="#" class="toggle-comments" id="toggle-comments-<?php bp_activity_id(); ?>"><?php printf( __( 'Hide Replies (%s)', 'sc' ), bp_activity_get_comment_count() ); ?></a>&nbsp;|&nbsp;
					<?php endif; ?>

					<?php if ( bp_activity_can_comment() ) : ?>

						<a href="<?php bp_activity_comment_link(); ?>" class="acomment-reply" id="acomment-comment-<?php bp_activity_id(); ?>"><?php _e( 'Add your comment', 'sc' ) ?></a>

					<?php endif; ?>

					<?php if ( bp_activity_user_can_delete() ) : ?>
						&nbsp;|&nbsp;<a href="<?php bp_activity_delete_url(); ?>"><?php _e( 'Delete', 'sc' ); ?></a>
					<?php endif; ?>

					<?php do_action( 'bp_activity_entry_meta' ); ?>

				<?php endif; ?>

			</p>
		<?php endif; ?>

	</div>

	<?php do_action( 'bp_before_activity_entry_comments' ); ?>

	<?php if ( ( bp_activity_get_comment_count() || bp_activity_can_comment() ) || bp_is_single_activity() ) : ?>

		<div class="activity-comments">

			<?php bp_activity_comments(); ?>

			<?php if ( is_user_logged_in() && bp_activity_can_comment() ) : ?>

				<form action="<?php bp_activity_comment_form_action(); ?>" method="post" id="ac-form-<?php bp_activity_id(); ?>" class="ac-form"<?php bp_activity_comment_form_nojs_display(); ?>>
					<div class="ac-reply-avatar"><?php bp_loggedin_user_avatar( 'width=' . BP_AVATAR_THUMB_WIDTH . '&height=' . BP_AVATAR_THUMB_HEIGHT ); ?></div>
					<div class="ac-reply-content">
						<div class="ac-textarea">
							<textarea id="ac-input-<?php bp_activity_id(); ?>" class="ac-input bp-suggestions" name="ac_input_<?php bp_activity_id(); ?>"></textarea>
						</div>
						<input type="submit" name="ac_form_submit" class="button small secondary" value="<?php esc_attr_e( 'Post', 'buddypress' ); ?>"  /> &nbsp;
						<a href="#" class="ac-reply-cancel"><?php _e( 'Cancel', 'buddypress' ); ?></a>
						<input type="hidden" name="comment_form_id" value="<?php bp_activity_id(); ?>" />
					</div>

					<?php do_action( 'bp_activity_entry_comments' ); ?>

					<?php wp_nonce_field( 'new_activity_comment', '_wpnonce_new_activity_comment' ); ?>

				</form>

			<?php endif; ?>

		</div>

	<?php endif; ?>

	<?php do_action( 'bp_after_activity_entry_comments' ); ?>

</li>

<?php do_action( 'bp_after_activity_entry' ); ?>

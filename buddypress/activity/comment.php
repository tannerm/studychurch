<?php

/**
 * BuddyPress - Activity Stream Comment
 *
 * This template is used by bp_activity_comments() functions to show
 * each activity.
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>
<li id="acomment-<?php bp_activity_comment_id(); ?>">

	<div class="acomment-meta">
		<div class="activity-avatar">
			<?php bp_activity_avatar( 'type=thumb&user_id=' . bp_get_activity_comment_user_id() ); ?>
		</div>
		<p class="small">
			<?php printf( __( '%s commented on this', 'sc' ), bp_get_activity_comment_name() ); ?>  <br />
			<span class="time-since"><?php bp_activity_comment_date_recorded(); ?></span>
		</p>
	</div>

	<div class="activity-inner"><?php bp_activity_comment_content(); ?></div>
</li>
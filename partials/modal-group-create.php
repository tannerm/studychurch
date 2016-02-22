<?php
global $wpdb, $bp;
$count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(DISTINCT id) FROM {$bp->groups->table_name} WHERE `creator_id` = %d", get_current_user_id() ) );
?>
<div id="group-create-modal" class="reveal-modal small" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog" >
	<h2 id="modalTitle"><?php _e( 'Create a new group.', 'sc' ); ?></h2>

	<?php if ( rcp_is_active() || $count < 2 ) : ?>
	<form id="group-create" class="ajax-form" action="" method="post" >
		<label>
			<?php _e( 'Group Name', 'sc' ); ?>
			<input name="group-name" type="text" placeholder="<?php _e( 'Name of this group', 'sc' ); ?>" />
		</label>
		<label>
			<?php _e( 'Group Description', 'sc' ); ?>
			<textarea name="group-desc" rows="8" placeholder="<?php _e( 'A brief description of this group so others have an idea of what it is...', 'sc' ); ?>"></textarea>
		</label>

		<?php wp_nonce_field( 'group-create', 'security' ); ?>
		<input type="hidden" name="action" value="sc_group_create" />
		<input type="submit" class="button expand" value="<?php _e( 'Create Group', 'sc' ); ?>" />
	</form>
	<?php else: ?>
		<p>Upgrade to <a href="/register/leader/">Leader</a> to create more small groups.</p>
	<?php endif; ?>
	<a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>
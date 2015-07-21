<div id="group-create-modal" class="reveal-modal small" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog" >
	<h2 id="modalTitle"><?php _e( 'Create a new group.', 'sc' ); ?></h2>

	<form id="group-create" action="" method="post" >
		<label>
			<?php _e( 'Group Name', 'sc' ); ?>
			<input name="group-name" type="text" placeholder="Wednesday Night Bible Study" />
		</label>
		<label>
			<?php _e( 'Group Description', 'sc' ); ?>
			<textarea name="group-desc" rows="8"></textarea>
		</label>
		<input type="submit" class="button expand" value="<?php _e( 'Create Group', 'sc' ); ?>" />
	</form>
	<a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>
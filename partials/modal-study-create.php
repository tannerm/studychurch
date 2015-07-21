<div id="study-create-modal" class="reveal-modal small ajax-form" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog" data-action="sc_study_create" >
	<h2 id="modalTitle"><?php _e( 'Create a new study.', 'sc' ); ?></h2>

	<form id="study-create" action="" method="post" >
		<label>
			<?php _e( 'Study Name', 'sc' ); ?>
			<input name="study-name" type="text" placeholder="<?php _e( 'Put the title of this study here...', 'sc' ); ?>" />
		</label>
		<label>
			<?php _e( 'Study Description', 'sc' ); ?>
			<textarea name="study-desc" rows="8" placeholder="<?php _e( 'Enter a brief description of this study here...', 'sc' ); ?>"></textarea>
		</label>
		<?php wp_nonce_field( 'study-create', 'study-create-nonce' ); ?>
		<input type="submit" class="button expand" value="<?php _e( 'Create Study', 'sc' ); ?>" />
	</form>
	<a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>
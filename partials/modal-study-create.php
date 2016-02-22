<div id="study-create-modal" class="reveal-modal small" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog" >
	<h2 id="modalTitle"><?php _e( 'Create a new study.', 'sc' ); ?></h2>

	<?php if ( rcp_is_active() ) : ?>
		<form id="study-create" class="ajax-form" action="" method="post" data-action="sc_study_create">
			<label>
				<?php _e( 'Study Name', 'sc' ); ?>
				<input name="study-name" type="text" placeholder="<?php _e( 'Put the title of this study here...', 'sc' ); ?>" />
			</label>
			<label>
				<?php _e( 'Study Description', 'sc' ); ?>
				<textarea name="study-desc" rows="8" placeholder="<?php _e( 'Enter a brief description of this study here...', 'sc' ); ?>"></textarea>
			</label>

			<?php wp_nonce_field( 'study-create', 'security' ); ?>
			<input type="hidden" name="action" value="sc_study_create" />
			<input type="submit" class="button expand" value="<?php _e( 'Create Study', 'sc' ); ?>" />
		</form>
		<?php else : ?>

		<p>Upgrade to <a href="/register/leader/">Leader</a> to create your own custom studies.</p>

	<?php endif; ?>
	<a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>
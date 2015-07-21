<script type="text/template" id="tmpl-chapter-template">
	<input type="text" class="chapter-title" id="chapter-{{{ data.id }}}-title" name="chapter-{{{ data.id }}}-title" placeholder="<?php _e( 'Enter chapter title here...', 'sc' ); ?>" value="{{{ data.title.rendered }}}" />
	<p class="text-right">
		<a href="#" id="new-section" class="ucase small"><i class="fa fa-plus-circle"></i> <?php _e( 'Create New Section', 'sc' ); ?>
		</a></p>
</script>
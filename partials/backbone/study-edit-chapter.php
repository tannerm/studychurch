<script type="text/template" id="tmpl-chapter-template">
	<a href="#" class="right ucase small chapter-delete alert"><i class="fa fa-minus-circle"></i> <?php _e( 'Delete this chapter', 'sc' ); ?></a>
	<div class="clearfix"></div>
	<input type="text" class="chapter-title" id="chapter-{{{ data.id }}}-title" name="chapter-{{{ data.id }}}-title" placeholder="<?php _e( 'Enter chapter title here...', 'sc' ); ?>" value="{{{ data.title.rendered }}}" />
	<section id="chapter-items"></section>
	<p class="text-right">
		<a href="#" id="new-item" class="ucase small"><i class="fa fa-plus-circle"></i> <?php _e( 'Create New Item', 'sc' ); ?>
		</a></p>
</script>
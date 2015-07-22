<script type="text/template" id="tmpl-chapter-sidebar-template">
	<# if (data.title.rendered) { #>
		<a href="#">{{{ data.title.rendered }}}</a>
	<# } else { #>
		<a href="#"><?php _e( 'New Chapter', 'sc' ); ?></a>
	<# } #>
</script>
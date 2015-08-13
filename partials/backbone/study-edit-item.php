<script type="text/template" id="tmpl-item-template">
	<div class="panel-buttons">
		<a href="#" class="item-content-delete"><i class="fa fa-trash"></i></a>
		<a href="#" class="item-content-edit"><i class="fa fa-pencil"></i></a>
		<a href="#" class="item-expand"><i class="fa fa-expand"></i></a>
		<a href="#" class="item-compress"><i class="fa fa-compress"></i></a>
		<a href="#" class="item-reorder"><i class="fa fa-reorder"></i></a>
	</div>
	<div class="item-content" id="item-content-{{{ data.id }}}">{{{ data.content.rendered }}}</div>

	<label class="tiny ucase button item-privacy-button">
		<# if ( data.is_private ) { #>
			<input type="checkbox" class="item-privacy no-margin" checked="checked" />
		<# } else { #>
			<input type="checkbox" class="item-privacy no-margin" />
		<# } #>
		&nbsp;&nbsp;<?php _e( 'Make this a private question.', 'sc' ); ?>
	</label>

	<select class="item-data-type no-margin">
		<?php foreach (sc_get_data_types() as $type => $label) : ?>
			<# if ( data.data_type == '<?php echo $type; ?>' ) { #>
				<option value="<?php echo $type; ?>" selected="selected"><?php esc_html_e( $label ); ?></option>
			<# } else { #>
				<option value="<?php echo $type; ?>"><?php esc_html_e( $label ); ?></option>
			<# } #>
		<?php endforeach; ?>
	</select>

	<div class="clearfix"></div>
</script>
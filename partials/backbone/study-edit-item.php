<script type="text/template" id="tmpl-item-template">
	<article class="panel item-{{{ data.id }}}">
		<div class="panel-buttons">
			<a href="#" class="item-content-delete"><i class="fa fa-trash"></i></a>
			<a href="#" class="item-content-edit"><i class="fa fa-pencil"></i></a>
			<a href="#" class="item-expand"><i class="fa fa-expand"></i></a>
			<a href="#" class="item-compress"><i class="fa fa-compress"></i></a>
		</div>
		<div class="item-content" id="item-content-{{{ data.id }}}">{{{ data.content.rendered }}}</div>
		<select class="item-data-type">
			<?php foreach( sc_get_data_types() as $type => $label ) : ?>
			<# if ( data.data_type == '<?php echo $type; ?>' ) { #>
				<option value="<?php echo $type; ?>" selected="selected" ><?php esc_html_e( $label ); ?></option>
			<# } else { #>
				<option value="<?php echo $type; ?>"><?php esc_html_e( $label ); ?></option>
			<# } #>
			<?php endforeach; ?>
		</select>

		<div class="hide">
			<a href="#" class="button secondary disabled right ucase tiny no-margin"><?php _e( 'Nothing to save', 'sc' ); ?></a>
			<label class="tiny ucase button left no-margin">
				<# if ( data.private ) { #>
					<input type="checkbox" class="item-privacy no-margin" checked="checked" />
				<# } else { #>
					<input type="checkbox" class="item-privacy no-margin" />
				<# } #>
				&nbsp;&nbsp;<?php _e( 'Make this a private question.', 'sc' ); ?></label>
		</div>
		<div class="clearfix"></div>
	</article>
</script>
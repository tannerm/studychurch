<script type="text/template" id="tmpl-item-template">
	<div class="panel-header text-right">
		<table>
			<tr>
				<td class="reorder">
					<a href="#" class="item-reorder left" title="Drag and drop"><i class="fa fa-reorder"></i></a>
				</td>

				<td class="item-data-type-cont">
					<select class="item-data-type no-margin">
						<?php foreach (sc_get_data_types() as $type => $label) : ?>
						<# if ( data.data_type == '<?php echo $type; ?>' ) { #>
							<option value="<?php echo $type; ?>" selected="selected"><?php esc_html_e( $label ); ?></option>
							<# } else { #>
								<option value="<?php echo $type; ?>"><?php esc_html_e( $label ); ?></option>
								<# } #>
									<?php endforeach; ?>
					</select>
				</td>

				<td class="buttons">
					<a href="#" class="item-compress" title="compress"><i class="fa fa-caret-up"></i></a>
					<a href="#" class="item-expand" title="expand"><i class="fa fa-caret-down"></i></a>
					<span class="sep"></span>
					<a href="#" class="item-content-delete" title="Delete this item"><i class="fa fa-trash"></i></a>
					<a href="#" class="item-content-save" title="Save"><i class="fa fa-save"></i><i class="fa fa-spinner fa-spin"></i></a>
					<a href="#" class="item-content-edit" title="Edit the text"><i class="fa fa-pencil"></i></a>
					<span class="sep"></span>
					<# if ( data.is_private ) { #>
						<a href="#" class="item-public item-privacy" title="Public question"><i class="fa fa-eye"></i></a>
						<a href="#" class="item-private item-privacy current" title="Private question"><i class="fa fa-eye-slash"></i></a>
					<# } else { #>
						<a href="#" class="item-public item-privacy current" title="Public question"><i class="fa fa-eye"></i></a>
						<a href="#" class="item-private item-privacy" title="Private question"><i class="fa fa-eye-slash"></i></a>
					<# } #>
				</td>
			</tr>
		</table>
	</div>

	<div class="item-content" id="item-content-{{{ data.id }}}">{{{ data.content.rendered }}}</div>

	<div class="clearfix"></div>
</script>
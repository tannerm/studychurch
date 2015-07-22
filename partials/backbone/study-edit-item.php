<?php
$types = array(
	'question_short' => __( 'Short Answer Question', 'sc' ),
	'question_long'  => __( 'Long Answer Question', 'sc' ),
	'content'        => __( 'Content', 'sc' ),
	'assignment'     => __( 'Assignment', 'sc' ),
);
?>
<script type="text/template" id="tmpl-item-template">
	<article class="panel item-{{{ data.id }}}">
		<div class="panel-buttons">
			<a href="#" class="item-content-edit"><i class="fa fa-pencil"></i></a>
			<a href="#" class="item-expand"><i class="fa fa-compress"></i></a>
		</div>
		<div class="item-content" id="item-content-{{{ data.id }}}">{{{ data.content.rendered }}}</div>
		<select class="item-data-type">
			<?php foreach( $types as $type => $label ) : ?>
			<# if ( data.data_type == '<?php echo $type; ?>' ) { #>
				<option value="<?php echo $type; ?>" selected="selected" ><?php esc_html_e( $label ); ?></option>
			<# } else { #>
				<option value="<?php echo $type; ?>"><?php esc_html_e( $label ); ?></option>
			<# } #>
			<?php endforeach; ?>
		</select>
	</article>
</script>
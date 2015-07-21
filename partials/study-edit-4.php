<?php foreach( get_pages( 'post_type=sc_study&child_of=' . $item_id ) as $item ) : ?>
	<div class="panel">
		<?php wp_editor( $item->post_content, 'content_' . $item->ID, array( 'teeny' => true ) ); ?>
		<select>
			<?php foreach( $types as $type => $label ) : ?>
				<option value="<?php echo esc_attr( $type ); ?>" <?php selected( sc_get_data_type( $item->ID ), $type ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
	</div>
<?php endforeach; ?>
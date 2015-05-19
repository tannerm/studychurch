<?php

function sc_add_button( $link = '#', $text = '', $classes = '', $data_attr = '' ) {
	printf(
		'<a href="%s" class="%s" %s><span class="screen-reader">%s</span>+</a>',
		esc_url( $link ),
		$classes . ' add-button',
		$data_attr,
		esc_html__( $text, 'sc' )
	);
}
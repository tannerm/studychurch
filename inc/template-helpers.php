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

/**
 * Is the current user online
 *
 * @param $user_id
 *
 * @return bool
 */
function sc_is_user_online( $user_id ) {
	$last_activity = strtotime( bp_get_user_last_activity( $user_id ) );

	if ( empty( $last_activity ) ) {
		return false;
	}

	// the activity timeframe is 15 minutes
	$activity_timeframe = 15 * MINUTE_IN_SECONDS;
	return ( time() - $last_activity <= $activity_timeframe );
}
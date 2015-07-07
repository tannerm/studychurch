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

/**
 * Can the current user moderate the current group?
 *
 * @return bool
 */
function sc_user_can_manage_group() {
	return ( bp_group_is_mod() || bp_group_is_admin() );
}

/**
 * Get the top level id for this study
 *
 * @param null $id
 *
 * @return bool|int|mixed|null
 */
function sc_get_study_id( $id = null ) {

	if ( ! $id ) {
		$id = get_the_ID();
	}

	if ( $parent_id = get_post_meta( $id, '_sc_study_id', true ) ) {
		return $parent_id;
	}

	$this_id = $id;

	// keep getting parents until there are no more to get.
	while ( $parent_id = wp_get_post_parent_id( $id ) ) {
		$id = $parent_id;
	}

	// cache results
	update_post_meta( $this_id, '_sc_study_id', $id );

	return $id;
}
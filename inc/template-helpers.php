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
 * Return data type for this element
 *
 * @param $id
 *
 * @return mixed
 */
function sc_get_data_type( $id = null ) {
	if ( ! $id ) {
		$id = get_the_ID();
	}

	return get_post_meta( $id, '_sc_data_type', true );
}

/**
 * Save data type
 *
 * @param      $data_type
 * @param null $id
 *
 * @return bool|int
 */
function sc_save_data_type( $data_type, $id = null ) {
	if ( ! $id ) {
		$id = get_the_ID();
	}

	return update_post_meta( $id, '_sc_data_type', sanitize_text_field( $data_type ) );
}

/**
 * Get valid data types
 *
 * @return array
 */
function sc_get_data_types() {
	return array(
		'question_short' => __( 'Short Answer Question', 'sc' ),
		'question_long'  => __( 'Long Answer Question', 'sc' ),
		'content'        => __( 'Content', 'sc' ),
		'assignment'     => __( 'Assignment', 'sc' ),
	);
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

/**
 * Get the id of the group that licensed this study
 *
 * @param null $study_id
 * @param null $user_id
 *
 * @return bool
 */
function sc_get_study_user_group_id( $study_id = null, $user_id = null ) {

	if ( ! $study_id ) {
		$study_id = get_the_ID();
	}

	$study_id = sc_get_study_id( $study_id );

	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	if ( empty( $study_id ) || empty( $user_id ) ) {
		return false;
	}

	foreach( groups_get_groups( 'show_hidden=true&user_id=' . $user_id )['groups'] as $group ) {
		if ( sc_get_group_study_id( $group->id ) == $study_id ) {
			return $group->id;
		}
	}

	return false;

}

/**
 * Can the user access this study?
 *
 * @param null $study_id
 * @param null $user_id
 *
 * @return bool
 */
function sc_user_can_access_study( $study_id = null, $user_id = null ) {
	return (bool) sc_get_study_user_group_id( $study_id, $user_id );
}

function sc_study_index( $id = null ) {
	echo walk_page_tree( sc_study_get_navigation( $id ), 0, get_queried_object_id(), array() );
}

function sc_study_manage_index( $study_id, $current_item = 0 ) {
	echo walk_page_tree( sc_study_get_navigation( $study_id ), 0, $current_item, array( 'walker' => new SC_Study_Manage_Walker ) );
}

/**
 * Return list of study navigation elements (everything that is not a bottom level
 * item) in a hierachical list
 *
 * @param null $id
 *
 * @return array
 */
function sc_study_get_navigation( $id = null ) {

	$study_id = sc_get_study_id( $id );

	$elements = get_pages( array(
		'sort_column' => 'menu_order',
		'post_type'   => 'sc_study',
		'child_of'    => sc_get_study_id( $study_id ),
	) );

	// unset study elements
	foreach( $elements as $key => $element ) {
		if ( get_post_meta( $element->ID, '_sc_data_type', true ) ) {
			unset( $elements[ $key ] );
		}
	}

	return array_values( $elements );
}

/**
 * Print out next/prev page links
 * @param null $id
 */
function sc_study_navigation( $id = null ) {
	$output = '';

	if ( ! $id ) {
		$id = get_the_ID();
	}

	$navigation = sc_study_get_navigation( $id );

	$key = -1;
	if ( $id != sc_get_study_id( $id ) ) {
		foreach( $navigation as $key => $item ) {
			if ( get_queried_object_id() == $item->ID ) {
				break;
			}
		}
	}


	if ( isset( $navigation[ $key - 1 ] ) ) {
		$prev = $navigation[ $key - 1 ];
		$output .= sprintf( '<span class="left prev"><a href="%s" title="%s"><i class="fa fa-caret-left"></i> %s</a></span>', get_the_permalink( $prev->ID ), the_title_attribute( 'echo=0&post=' . $prev->ID ), get_the_title( $prev->ID ) );
	}


	if ( isset( $navigation[ $key + 1 ] ) ) {
		$next = $navigation[ $key + 1 ];
		$output .= sprintf( '<span class="right next"><a href="%s" title="%s">%s <i class="fa fa-caret-right"></i></a></span>', get_the_permalink( $next->ID ), the_title_attribute( 'echo=0&post=' . $next->ID ), get_the_title( $next->ID ) );
	}

	printf( '<p class="clearfix lesson-nav">%s</p>', $output );

}

/**
 * Return the current users comment answer to the current element
 *
 * @return array|int
 */
function sc_study_get_answer() {
	if ( ! bp_get_group_id() ) {
		return false;
	}

	$answer = get_comments( array(
		'post_id'    => get_the_ID(),
		'meta_key'   => 'group_id',
		'meta_value' => bp_get_group_id(),
		'number'     => 1,
		'author__in' => get_current_user_id(),
	) );

	if ( isset( $answer[0] ) ) {
		return $answer[0];
	} else {
		return false;
	}
}
<?php


SC_Assignments_Create::get_instance();
class SC_Assignments_Create {

	/**
	 * @var
	 */
	protected static $_instance;

	/**
	 * @var
	 */
	protected static $_errors;

	/**
	 * Only make one instance of the SC_Assignments_Create
	 *
	 * @return SC_Assignments_Create
	 */
	public static function get_instance() {
		if ( ! self::$_instance instanceof SC_Assignments_Create ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Add Hooks and Actions
	 */
	protected function __construct() {
		add_action( 'init',      array( $this, 'save_assignment' ) );
		add_action( 'init',      array( $this, 'delete_assignment' ) );
	}

	public function delete_assignment() {
		if ( ! isset( $_POST['delete_assignment_nonce'] ) ) {
			return;
		}

		if ( empty( $_POST['assignment'] ) || ! wp_verify_nonce( $_POST['delete_assignment_nonce'], 'delete_assignment' ) ) {
			return;
		}

		if ( sc_delete_group_assignment( absint( $_POST['assignment'] ) ) ) {
			bp_core_add_message( __( 'Success! Assignment was deleted.', 'sc' ), 'success' );
			wp_safe_redirect( $_SERVER['REQUEST_URI'] );
			exit();
		} else {
			bp_core_add_message( __( 'Ooops. Something went wrong, please try again.', 'sc' ), 'error' );
		}
	}

	public function save_assignment() {

		// If no form submission, bail
		if ( ! isset( $_POST['new_assignment_nonce'] ) ) {
			return;
		}

		// make sure this user can manage assignments
		if ( ! sc_user_can_manage_group() ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['new_assignment_nonce'], 'create_new_assignment' ) ) {
			bp_core_add_message( __( 'Ooops. Something went wrong, please try again.', 'sc' ), 'error' );
			return;
		}

		if ( sc_add_group_assignment( $_POST, bp_get_current_group_id() ) ) {
			bp_core_add_message( __( 'Success! Created a new assignment', 'sc' ), 'success' );
			wp_safe_redirect( $_SERVER['REQUEST_URI'] );
			exit();
		} else {
			bp_core_add_message( __( 'Ooops. Something went wrong, please make sure you have specified content or lessons and a due date.', 'sc' ), 'error' );
		}

	}

}

/**
 * Get group assignments
 *
 * @param bool $group_id
 *
 * @return mixed | array | false - array of assignments or false if none exist
 */
function sc_get_group_assignments( $group_id = null ) {

	if ( ! $group_id ) {
		$group_id = bp_get_current_group_id();
	}

	return new WP_Query( array(
		'post_type' => 'sc_assignment',
		'post_status' => array( 'publish', 'future' ),
		'date_query'  => array(
			'date_query' => array( 'after' => date( 'RSS', time() ) )
		),
		'tax_query'   => array(
			'taxonomy' => 'sc_group',
			'field'    => 'slug',
			'terms'    => $group_id,
		),
	) );
}

/**
 * Add an assignment to the group
 *
 * @param $assignment
 * @param $group_id
 *
 * @return bool|int
 */
function sc_add_group_assignment( $assignment, $group_id ) {

	if ( ( empty( $assignment['content'] ) && empty( $assignment['lessons'] ) ) || empty( $assignment['date'] ) ) {
		return false;
	}

	$ass_id = wp_insert_post( array(
		'post_type'    => 'sc_assignment',
		'post_status'  => 'publish',
		'post_title'   => '',
		'post_content' => wp_filter_post_kses( $assignment['content'] ),
		'post_date'    => date( 'Y-m-d H:i:s', strtotime( $assignment['date'] ) ),
	) );

	if ( ! $ass_id ) {
		return false;
	}

	update_post_meta( $ass_id, 'assignment', $assignment['lessons'] );
	update_post_meta( $ass_id, 'group_id', $group_id );

	wp_set_post_terms( $ass_id, $group_id, 'sc_group' );

	do_action( 'sc_assignment_create', $assignment, $group_id );

	return true;
//	return groups_update_groupmeta( $group_id, SC_Assignments_Query::$_key, $assignments );
}

/**
 * Delete a group assignment
 *
 * @param $assignment
 * @param $group_id
 *
 * @return bool|int
 */
function sc_delete_group_assignment( $assignment, $group_id = null ) {

	if ( ! $group_id ) {
		$group_id = bp_get_current_group_id();
	}

	if ( ! $assignments = sc_get_group_assignments( $group_id ) ) {
		return false;
	}

	if ( ! isset( $assignments[ $assignment ] ) ) {
		return false;
	}

	unset( $assignments[ $assignment ] );

	return groups_update_groupmeta( $group_id, SC_Assignments_Query::$_key, $assignments );
}
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
		add_action( 'init', array( $this, 'ass_cpt' ) );
		add_action( 'template_redirect', array( $this, 'save_assignment'   ) );
		add_action( 'template_redirect', array( $this, 'delete_assignment' ) );
	}

	public function ass_cpt() {
		register_post_type( 'sc_assignment' );
		register_taxonomy( 'sc_group', 'sc_assignment', array(
			'public' => false,
		) );
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
 * @param array $args
 *
 * @return mixed | array | false - array of assignments or false if none exist
 */
function sc_get_group_assignments( $args = array() ) {
	return new SC_Assignments_Query( $args );
}

function sc_get_group_assignment( $id ) {
	if ( ! $assignment = get_post( $id ) ) {
		return false;
	}

	return array(
		'id'      => $id,
		'content' => $assignment->post_content,
		'lessons' => get_post_meta( $id, 'lessons', true ),
		'date'    => get_the_date( '', $id ),
	);
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

	$date = new DateTime( $assignment['date'] . '23:59:59', new DateTimeZone( get_option( 'timezone_string', 'America/Los_Angeles' ) ) );

	$assignment['id'] = wp_insert_post( array(
		'post_author'  => get_current_user_id(),
		'post_type'    => 'sc_assignment',
		'post_status'  => 'publish',
		'post_title'   => 'Assignment',
		'post_content' => $assignment['content'],
		'post_date'    => $date->format( 'Y-m-d H:i:s' ),
	) );

	if ( ! $assignment['id'] ) {
		return false;
	}

	wp_set_post_terms( $assignment['id'], $group_id, 'sc_group' );

	if ( ! empty( $assignment['lessons'] ) ) {
		update_post_meta( $assignment['id'], 'lessons', array_map( 'absint', (array) $assignment['lessons'] ) );
	}
	
	do_action( 'sc_assignment_create', $assignment, $group_id );

	return $assignment['id'];
}

/**
 * Delete a group assignment
 *
 * @param $assignment
 *
 * @return bool|int
 */
function sc_delete_group_assignment( $assignment ) {
	return wp_delete_post( $assignment, true );
}
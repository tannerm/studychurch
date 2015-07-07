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
		add_action( 'cmb2_init', array( $this, 'register_form'   ) );
		add_action( 'wp',      array( $this, 'save_assignment' ) );
	}

	public function register_form() {
		$cmb = new_cmb2_box( array(
			'id'          => 'sc_assignments',
			'hookup'      => false,
			'save_fields' => false,
		) );

		$cmb->add_field( array(
			'name' => 'Assignment',
			'id'   => 'content',
			'type' => 'textarea',
		) );

		$cmb->add_field( array(
			'name' => 'Due Date',
			'id'   => 'date',
			'type' => 'text_date',
		) );

	}

	public function save_assignment() {

		$cmb = cmb2_get_metabox( 'sc_assignments' );

		// If no form submission, bail
		if ( empty( $_POST ) ) {
			return;
		}

		// check required $_POST variables and security nonce
		if (
			! isset( $_POST['submit-cmb'], $_POST['object_id'], $_POST[ $cmb->nonce() ] )
			|| ! wp_verify_nonce( $_POST[ $cmb->nonce() ], $cmb->nonce() )
		) {
			self::$_errors = new WP_Error( 'security_fail', __( 'Security check failed.' ) );
			return;
		}

		if ( empty( $_POST['content'] ) ) {
			self::$_errors = new WP_Error( 'post_data_missing', __( 'New assignment requires content.', 'sc' ) );
			return;
		}

		if ( empty( $_POST['date'] ) ) {
			self::$_errors = new WP_Error( 'post_data_missing', __( 'New assignment requires date.', 'sc' ) );
			return;
		}

		/** get the sanitized values */
		$sanitized_values = $cmb->get_sanitized_values( $_POST );

		foreach ( $sanitized_values as $key => $value ) {
			if ( ! in_array( $key, array( 'content', 'date' ) ) ) {
				unset( $sanitized_values[ $key ] );
			}
		}

		sc_add_group_assignment( $sanitized_values, bp_get_current_group_id() );

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

	return groups_get_groupmeta( $group_id, SC_Assignments_Query::$_key, true );
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

	if ( ! $assignments = sc_get_group_assignments( $group_id ) ) {
		$assignments = array();
	}

	if ( empty( $assignment['content'] ) || empty( $assignment['date'] ) ) {
		return false;
	}

	$key = absint( strtotime( $assignment['date'] ) . rand( 1000, 9999 ) );

	/** Make sure the key is unique. Support multiple assignments due the same day */
	while ( isset( $assignments[ $key ] ) ) {
		$key = absint( strtotime( $assignment['date'] ) . rand( 1000, 9999 ) );
	}

	$assignments[ $key ] = $assignment;

	return groups_update_groupmeta( $group_id, SC_Assignments_Query::$_key, $assignments );
}
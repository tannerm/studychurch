<?php

SC_BP_Filter::get_instance();
class SC_BP_Filter {

	/**
	 * @var
	 */
	protected static $_instance;

	/**
	 * Only make one instance of the SC_BP_Filter
	 *
	 * @return SC_BP_Filter
	 */
	public static function get_instance() {
		if ( ! self::$_instance instanceof SC_BP_Filter ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Add Hooks and Actions
	 */
	protected function __construct() {
		add_filter( 'groups_activity_new_update_action', array( $this, 'group_activity_action' ) );
	}

	public function group_activity_action( $action ) {
		return sprintf( __( '%1$s posted an update', 'buddypress'), bp_get_mentioned_user_display_name( bp_loggedin_user_id() ) );
	}

}
<?php

SC_BP_Online::get_instance();
class SC_BP_Online {

	/**
	 * @var
	 */
	protected static $_instance;

	/**
	 * Only make one instance of the SC_BP_Online
	 *
	 * @return SC_BP_Online
	 */
	public static function get_instance() {
		if ( ! self::$_instance instanceof SC_BP_Online ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Add Hooks and Actions
	 */
	protected function __construct() {}

	public function is_user_online( $user_id ) {
		if ( ! $online_users = get_transient( 'sc_online_users' ) ) {
			$online_users = $this->query_online_users();

			// set transient for 5 minutes
			set_transient( 'sc_online_users', $online_users, 5 * MINUTE_IN_SECONDS );
		}

		return in_array( $user_id, $online_users );
	}

	protected function query_online_users() {
		global $bp, $wpdb;

		$uid_name = 'user_id';
		$uid_table = $bp->members->table_name_last_activity;
		$sql['select']  = "SELECT u.{$uid_name} as id FROM {$uid_table} u";
		$sql['where']   = $wpdb->prepare( "u.component = %s AND u.type = 'last_activity' ", buddypress()->members->id );
		$sql['where']  .= $wpdb->prepare( "u.date_recorded >= DATE_SUB( UTC_TIMESTAMP(), INTERVAL %d MINUTE )", apply_filters( 'bp_user_query_online_interval', 15 ) );
		$sql['orderby'] = "ORDER BY u.date_recorded";
		$sql['order']   = "DESC";

		return $wpdb->get_col( implode( ' ', $sql ) );
	}

}
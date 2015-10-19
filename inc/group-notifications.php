<?php

SC_Group_Notifications::get_instance();
class SC_Group_Notifications {

	/**
	 * @var
	 */
	protected static $_instance;

	/**
	 * Only make one instance of the SC_Group_Notifications
	 *
	 * @return SC_Group_Notifications
	 */
	public static function get_instance() {
		if ( ! self::$_instance instanceof SC_Group_Notifications ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Add Hooks and Actions
	 */
	protected function __construct() {	}

	public function user_inactivity() {}

}
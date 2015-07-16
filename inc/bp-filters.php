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
		add_filter( 'bp_get_activity_css_class',         array( $this, 'no_mini_class'         ) );
		add_filter( 'bp_get_loggedin_user_avatar',       array( $this, 'current_user_avatar_container' ) );
		add_filter( 'bp_core_fetch_avatar',              array( $this, 'user_avatar_container' ), 10, 2 );
	}

	public function group_activity_action( $action ) {
		return sprintf( __( '%1$s posted an update', 'buddypress'), bp_get_mentioned_user_display_name( bp_loggedin_user_id() ) );
	}

	public function current_user_avatar_container( $avatar ) {
		return sprintf( '<div class="avatar-container online">%s</div>', $avatar );
	}

	public function user_avatar_container( $avatar, $params ) {
		if ( 'group' == $params['object'] ) {
			return $avatar;
		}

		$online_class = ( sc_is_user_online( (int) $params['item_id'] ) ) ? 'online' : '';

		return sprintf( '<div class="avatar-container %s">%s</div>', $online_class, $avatar );
	}

	/**
	 * Remove the mini class from activities
	 *
	 * @param $classes
	 *
	 * @return mixed
	 */
	public function no_mini_class( $classes ) {
		return str_replace( ' mini', '', $classes );
	}

}
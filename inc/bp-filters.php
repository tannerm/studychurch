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
//		add_filter( 'bp_get_loggedin_user_avatar',       array( $this, 'current_user_avatar_container' ) );
		add_filter( 'bp_core_fetch_avatar',              array( $this, 'user_avatar_container' ), 10, 2 );
		add_filter( 'bp_avatar_is_front_edit',           array( $this, 'avatar_is_front_edit'  ) );
		add_filter( 'bp_displayed_user_id',              array( $this, 'displayed_user_id'     ) );
		add_action( 'bp_activity_before_save',           array( $this, 'activity_mentions'     ), 9 );
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

	public function avatar_is_front_edit( $val ) {
		if ( $val ) {
			return $val;
		}

		return bp_is_user_settings();
	}

	/**
	 * If we don't have a displayed user, get the current user id.
	 *
	 * @param $id
	 *
	 * @return int
	 */
	public function displayed_user_id( $id ) {
		return $id;

		if ( $id ) {
			return $id;
		}

		return get_current_user_id();
	}

	/**
	 * Redefine bp_activity_at_name_filter_updates to remove links
	 *
	 * @param $activity
	 */
	public function activity_mentions( $activity ) {
		remove_action( 'bp_activity_before_save', 'bp_activity_at_name_filter_updates' );

		// Are mentions disabled?
		if ( ! bp_activity_do_mentions() ) {
			return;
		}

		// If activity was marked as spam, stop the rest of this function.
		if ( ! empty( $activity->is_spam ) )
			return;

		// Try to find mentions
		$usernames = bp_activity_find_mentions( $activity->content );

		// We have mentions!
		if ( ! empty( $usernames ) ) {
			// Replace @mention text with userlinks
			foreach( (array) $usernames as $user_id => $username ) {
				$activity->content = preg_replace( '/(@' . $username . '\b)/', "<span class='username'>@$username</span>", $activity->content );
			}

			// Add our hook to send @mention emails after the activity item is saved
			add_action( 'bp_activity_after_save', 'bp_activity_at_name_send_emails' );

			// temporary variable to avoid having to run bp_activity_find_mentions() again
			buddypress()->activity->mentioned_users = $usernames;
		}
	}
}
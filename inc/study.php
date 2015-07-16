<?php

SC_Study::get_instance();
class SC_Study {

	/**
	 * @var
	 */
	protected static $_instance;

	/**
	 * Only make one instance of the SC_Study
	 *
	 * @return SC_Study
	 */
	public static function get_instance() {
		if ( ! self::$_instance instanceof SC_Study ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Add Hooks and Actions
	 */
	protected function __construct() {
		add_action( 'wp_ajax_sc_save_answer', array( $this, 'save_answer' ) );
		add_action( 'template_redirect', array( $this, 'setup_study_group' ) );
	}

	/**
	 * Save study answers via ajax
	 */
	public function save_answer() {

		$user = wp_get_current_user();

		if ( ! $user->exists() ) {
			wp_send_json_error();
		}

		$data = array(
			'comment_post_ID'      => absint( $_POST['post_id'] ),
			'comment_ID'           => absint( $_POST['comment_id'] ),
			'comment_author'       => wp_slash( $user->display_name ),
			'comment_author_email' => wp_slash( $user->user_email ),
			'comment_author_url'   => wp_slash( $user->user_url ),
			'comment_content'      => $_POST['answer'],
			'comment_parent'       => 0,
			'user_id'              => $user->ID,
		);

		$lesson_id = wp_get_post_parent_id( $data['comment_post_ID'] );

		$activity_meta = array(
			'action'            => sprintf( __( '%s answered a question in <a href="%s#post-%s">%s</a>' ), $user->display_name, get_permalink( $lesson_id ), $data['comment_post_ID'], get_the_title( $lesson_id ) ),
			'content'           => wp_filter_kses( $data['comment_content'] ),
			'component'         => buddypress()->groups->id,
			'type'              => 'answer_update',
			'user_id'           => $user->ID,
			'item_id'           => 3,
			'recorded_time'     => bp_core_current_time(),
			'secondary_item_id' => $data['comment_post_ID'],
			'hide_sitewide'     => false,
		);

		if ( $data['comment_ID'] ) {
			wp_update_comment( $data );
			$activity_meta['id'] = sc_answer_get_activity_id( $data['comment_ID'] );
		} else {
			$data['comment_ID'] = wp_new_comment( $data );
		}

		update_comment_meta( $data['comment_ID'], 'group_id', absint( $_POST['group_id'] ) );

		if ( ! sc_answer_is_private( $data['comment_post_ID'] ) ) {
			$activity_id = bp_activity_add( $activity_meta );
			update_comment_meta( $data['comment_ID'], 'activity_id', $activity_id );
		}

		wp_send_json_success( $data );
	}

	public function setup_study_group() {
		if ( ! is_singular( 'sc_study' ) ) {
			return;
		}

		bp_has_groups( 'include=' . sc_get_study_user_group_id() );
		bp_groups();
		bp_the_group();

		if ( ! bp_get_group_id() ) {
			wp_die( 'no access' );
		}
	}

}

/**
 * Is this answer private?
 *
 * @param $post_id
 *
 * @return bool
 */
function sc_answer_is_private( $post_id ) {
	return ( 'private' == get_post_meta( $post_id, '_sc_privacy', true ) );
}

function sc_answer_get_activity_id( $comment_id ) {
	return get_comment_meta( $comment_id, 'activity_id', true );
}
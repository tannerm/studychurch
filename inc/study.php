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
	}

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
			'user_id'              => get_current_user_id(),
		);

		if ( $data['comment_ID'] ) {
			wp_update_comment( $data );
		} else {
			$data['comment_ID'] = wp_new_comment( $data );
		}

		wp_send_json_success( $data );
	}

}
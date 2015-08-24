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
		add_action( 'template_redirect',      array( $this, 'setup_study_group' ) );
		add_action( 'template_redirect',      array( $this, 'redirect_on_empty' ) );
		add_action( 'wp_head',                array( $this, 'print_styles'      ) );

		add_filter( 'private_title_format',   array( $this, 'private_title_format' ), 10, 2 );
		add_filter( 'user_has_cap',           array( $this, 'private_study_cap'    ), 10, 4 );
	}

	public function redirect_on_empty() {
		if ( 'sc_study' != get_post_type() ) {
			return;
		}

		// if we are not on the main study page continue
		if ( get_the_ID() != sc_get_study_id( get_the_ID() ) ) {
			return;
		}

		if ( get_the_content() ) {
			return;
		}

		$nav = sc_study_get_navigation( get_the_ID() );

		// if we have no content for this page, redirect to the first item
		if ( ! empty( $nav[0] ) ) {
			wp_safe_redirect( get_the_permalink( $nav[0]->ID ) );
			die();
		}

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

		global $post;
		$post = get_post( $data['comment_post_ID'] );

		$lesson_id = wp_get_post_parent_id( $data['comment_post_ID'] );

		$activity_meta = array(
			'action'            => sprintf( __( '%s answered a question in <a href="%s#post-%s">%s</a>' ), $user->display_name, get_permalink( $lesson_id ), $data['comment_post_ID'], get_the_title( $lesson_id ) ),
			'content'           => wp_filter_kses( $data['comment_content'] ),
			'component'         => buddypress()->groups->id,
			'type'              => 'answer_update',
			'user_id'           => $user->ID,
			'item_id'           => absint( $_POST['group_id'] ),
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

		$this->setup_study_group( $data['comment_post_ID'] );

		ob_start();
		global $sc_answer;
		$sc_answer = get_comment( $data['comment_ID'] );
		get_template_part( 'partials/study-element', 'answers' );
		$data['answers'] = ob_get_clean();

		wp_send_json_success( $data );
	}

	/**
	 * Setup global for current group
	 */
	public function setup_study_group( $study_id = false ) {
		if ( empty( $study_id ) && ! is_singular( 'sc_study' ) ) {
			return;
		}

		if ( ! $group_id = sc_get_study_user_group_id( $study_id ) ) {

			// allow editors and up to proceed
			if ( current_user_can( 'edit_post', sc_get_study_id( $study_id ) ) ) {
				return;
			}

			wp_safe_redirect( bp_loggedin_user_domain() );
			die();
		}

		bp_has_groups( 'include=' . $group_id );
		bp_groups();
		bp_the_group();

		if ( ! bp_get_group_id() ) {
			wp_safe_redirect( bp_loggedin_user_domain() );
			die();
		}

	}

	/**
	 * Remove "Private:" label from private sc_study posts
	 *
	 * @param $format
	 * @param $post
	 *
	 * @return string
	 */
	public function private_title_format( $format, $post ) {
		if ( 'sc_study' != $post->post_type ) {
			return $format;
		}

		return '%s';
	}

	public function private_study_cap( $allcaps, $caps, $args, $user ) {
		if ( empty( $user->ID ) ) {
			return $allcaps;
		}

		// we are only interested in private posts capability
		if ( ! in_array( 'read_private_posts', $caps ) ) {
			return $allcaps;
		}

		// this user can already ready private posts
		if ( isset( $allcaps['read_private_posts'] ) && $allcaps['read_private_posts'] ) {
			return $allcaps;
		}

		// make sure this is a study
		if ( empty( $args[2] ) || 'sc_study' != get_post_type( absint( $args[2] ) ) ) {
			return $allcaps;
		}

		// make sure this user has access to this study
		if ( ! sc_user_can_access_study( absint( $args[2] ), $user->ID ) ) {
			return $allcaps;
		}

		$allcaps['read_private_posts'] = true;

		return $allcaps;
	}

	public function print_styles() {
		if ( ! is_singular( 'sc_study' ) ) {
			return;
		} ?>
		<style>
			@page {
				size: 8.5in 11in;
				margin: 10%;
			}
		</style>
		<?php
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

function sc_get_privacy( $post_id ) {
	return get_post_meta( $post_id, '_sc_privacy', true );
}

function sc_set_privacy( $privacy, $post_id ) {
	if ( 'private' != $privacy ) {
		return delete_post_meta( $post_id, '_sc_privacy' );
	}

	return update_post_meta( $post_id, '_sc_privacy', 'private' );
}

function sc_answer_get_activity_id( $comment_id ) {
	return get_comment_meta( $comment_id, 'activity_id', true );
}
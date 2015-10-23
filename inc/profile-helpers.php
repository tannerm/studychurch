<?php

SC_Profile::get_instance();
class SC_Profile {

	/**
	 * @var
	 */
	protected static $_instance;

	/**
	 * Only make one instance of the SC_Profile
	 *
	 * @return SC_Profile
	 */
	public static function get_instance() {
		if ( ! self::$_instance instanceof SC_Profile ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Add Hooks and Actions
	 */
	protected function __construct() {
		add_action( 'wp_footer',          array( $this, 'modals' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'localize'           ), 20 );
		add_action( 'sc_ajax_form_sc_group_create', array( $this, 'handle_group_create' ) );
		add_action( 'sc_ajax_form_sc_study_create', array( $this, 'handle_study_create' ) );

		add_filter( 'groups_group_slug_before_save', array( $this, 'id_as_slug' ), 10, 2 );
	}

	/**
	 * Handle study create modal form
	 */
	public function handle_study_create( $data ) {

		if ( empty( $data['security'] ) || ! wp_verify_nonce( $data['security'], 'study-create' ) ) {
			wp_send_json_error();
		}

		$study = apply_filters( 'sc_study_insert_args', array(
			'post_type'    => 'sc_study',
			'post_title'   => sanitize_text_field( $data['study-name'] ),
			'post_excerpt' => wp_filter_kses( $data['study-desc'] ),
			'post_status'  => 'private',
		) );

		$study_id = wp_insert_post( $study );

		if ( ! $study_id ) {
			wp_send_json_error();
		}

		wp_send_json_success( array(
			'message' => __( 'Success! Redirecting you to your new study.', 'sc' ),
			'url'     => sprintf( '/study-edit/?action=edit&study=%d', $study_id )
		) );

	}

	public function handle_group_create( $data ) {

		if ( empty( $data['security'] ) || ! wp_verify_nonce( $data['security'], 'group-create' ) ) {
			wp_send_json_error( array(
				'message' => __( 'Something went wrong. Please refresh and try again.', 'sc' )
			) );
		}

		if ( empty( $data['group-name'] ) ) {
			wp_send_json_error( array(
				'message' => __( 'Please enter a group name', 'sc' )
			) );
		}

		$id = groups_create_group( array(
			'name'        => sanitize_text_field( $data['group-name'] ),
			'description' => esc_textarea( $data['group-desc'] ),
			'status'      => 'hidden',
		) );

		if ( ! $id ) {
			wp_send_json_error( array(
				'message' => __( 'Something went wrong. Please refresh and try again.', 'sc' )
			) );
		}

		if ( ! empty( $_POST['study-name'] ) ) {
			groups_add_groupmeta( $id, 'study_name', sanitize_text_field( $_POST['study-name'] ) );
		}

		$group = groups_get_group( array( 'group_id' => $id ) );

		$url = esc_url( trailingslashit( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/' . $group->slug . '/' ) );

		wp_send_json_success( array(
			'message'  => __( 'Success! Taking you to your new group.', 'sc' ),
			'url'      => $url
		) );

	}

	public function modals() {
		if ( ! bp_is_user_profile() ) {
			return;
		}

		if ( current_user_can( 'manage_groups' ) ) {
			get_template_part( 'partials/modal', 'group-create' );
		}

		if ( current_user_can( 'edit_posts' ) ) {
			get_template_part( 'partials/modal', 'study-create' );
		}

	}

	public function localize() {
		wp_localize_script( 'sc', 'scGroupCreateData', array(
			'security' => wp_create_nonce( 'group-create' ),
			'success'  => esc_html__( 'Success! Taking you to your group...', 'sc' ),
			'error'    => esc_html__( 'Something went wrong, please try again', 'sc' ),
		) );
	}

	/**
	 * Use the group id as the slug
	 *
	 * @param $slug
	 * @param $id
	 *
	 * @return mixed
	 */
	public function id_as_slug( $slug, $id ) {
		if ( is_numeric( $slug ) ) {
			return $slug;
		}

		return time();
	}
}
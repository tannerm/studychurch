<?php

SC_Group_Create::get_instance();
class SC_Group_Create {

	/**
	 * @var
	 */
	protected static $_instance;

	/**
	 * Only make one instance of the SC_Group_Create
	 *
	 * @return SC_Group_Create
	 */
	public static function get_instance() {
		if ( ! self::$_instance instanceof SC_Group_Create ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Add Hooks and Actions
	 */
	protected function __construct() {
		add_action( 'wp_footer',          array( $this, 'create_group_modal' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'localize'           ), 20 );
		add_action( 'wp_ajax_sc_group_create', array( $this, 'handle_group_create' ) );

		add_filter( 'groups_group_slug_before_save', array( $this, 'id_as_slug' ), 10, 2 );
	}

	public function handle_group_create() {
		check_ajax_referer( 'group-create', 'security' );

		if ( empty( $_POST['group-name'] ) ) {
			wp_send_json_error();
		}

		$id = groups_create_group( array(
			'name'        => sanitize_text_field( $_POST['group-name'] ),
			'description' => esc_textarea( $_POST['group-desc'] ),
			'status'      => 'private',
		) );

		if ( ! $id ) {
			wp_send_json_error();
		}

		if ( ! empty( $_POST['study-name'] ) ) {
			groups_add_groupmeta( $id, 'study_name', sanitize_text_field( $_POST['study-name'] ) );
		}

		$group = groups_get_group( array( 'group_id' => $id ) );

		$url = esc_url( trailingslashit( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/' . $group->slug . '/' ) );

		wp_send_json_success( $url );

	}

	public function create_group_modal() {
		if ( ! is_page_template( 'templates/profile.php' ) ) {
			return;
		}

		get_template_part( 'partials/modal', 'group-create' );
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
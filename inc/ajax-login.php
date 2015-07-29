<?php

new SC_Ajax_Login;

class SC_Ajax_Login {

	static $success = false;

	public function __construct () {
		add_action( 'sc_ajax_form_nopriv_sc_login', array( $this, 'login' ) );
		add_action( 'wp_enqueue_scripts',           array( $this, 'localize'   ), 20 );
	}

	public function login( $data ) {

		if ( empty( $data['sc_login_key'] ) ) {
			wp_send_json_error();
		}

		// Do it this way to catch weird nonce issue.
		if ( ! wp_verify_nonce( $data['sc_login_key'], 'sc-login' ) ) {
			wp_logout();
			wp_send_json_error();
		}

		$status = wp_signon( $data );

		if ( is_wp_error( $status ) ) {
			if ( 'incorrect_password' == $status->get_error_code() ) {
				$message = sprintf( "The password you entered doesn't match our records. Please try again or <a href='%s' title='Password Lost and Found'>reset your password</a>.", wp_lostpassword_url() );
			} else {
				$message = str_replace( '<strong>ERROR</strong>: ', '', $status->get_error_message() );
			}
			wp_send_json_error( $message );
		}

		wp_send_json_success( array(
			'message' => __( 'Success! Taking you to your profile.', 'sc' ),
			'url'     => bp_core_get_user_domain( $status->ID ),
		) );
	}

	public function localize() {
		wp_localize_script( 'sc', 'scAjaxLogin', array(
			'security' => wp_create_nonce( 'sc-login' ),
			'success'  => esc_html__( 'Success! Refreshing the page...', 'sc' ),
			'error'    => esc_html__( 'Something went wrong, please try again', 'sc' ),
		) );
	}

}

new SC_Ajax_Register;
class SC_Ajax_Register {

	static $success = false;

	public function __construct () {
		add_action( 'sc_ajax_form_nopriv_sc_register', array( $this, 'ajax_register' ) );
		add_action( 'init', array( $this, 'remove_default' ) );

		add_filter( 'rcp_return_url', array( $this, 'return_url' ), 10, 2 );
	}

	public function ajax_register( $data ) {

		$_POST = $data;

		add_filter( 'wp_redirect', array( $this, 'register_success' ) );
		rcp_process_registration();
		remove_filter( 'wp_redirect', array( $this, 'register_success' ) );

		wp_send_json_error( array(
			'message' => implode( '<br />', rcp_errors()->get_error_messages() ),
		) );
	}

	public function register_success( $location ) {
		remove_filter( 'wp_redirect', array( $this, 'register_success' ) );
		wp_send_json_success( array(
			'message' => __( 'Success! Taking you to your profile.', 'sc' ),
			'url'     => $location
		) );
	}

	public function remove_default() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			remove_action( 'init', 'rcp_process_registration', 100 );
		}
	}

	public function return_url( $return, $user_id ) {
		return bp_core_get_user_domain( $user_id );
	}

}
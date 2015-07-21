<?php

require dirname( __FILE__ ) . '/groups.php';
require dirname( __FILE__ ) . '/study-edit.php';

function study_api_init() {
	global $study_api_sc_study;

	require_once dirname( __FILE__ ) . '/study-api.php';
	$study_api_sc_study = new Study_API_SC_Study;
	$study_api_sc_study->register_routes();
}
add_action( 'rest_api_init', 'study_api_init', 0 );

function sc_study_extra_api_post_type_arguments() {
	global $wp_post_types;

	$wp_post_types['sc_study']->show_in_rest = true;
	$wp_post_types['sc_study']->rest_base = 'study';
	$wp_post_types['sc_study']->rest_controller_class = 'WP_REST_Posts_Controller';
}
add_action( 'init', 'sc_study_extra_api_post_type_arguments', 11 );

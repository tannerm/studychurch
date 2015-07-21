<?php

// class-myplugin-api-mytype.php
class Study_API_SC_Study extends WP_REST_Posts_Controller {
	protected $base = 'study';
	protected $post_type = 'sc_study';

	public function __construct() {
		parent::__construct( $this->post_type );
	}

	public function register_routes() {

		$base = $this->get_post_type_base( $this->post_type );

		$posts_args = array(
			'context'          => array(
				'default'      => 'view',
			),
			'page'            => array(
				'default'           => 0,
				'sanitize_callback' => 'absint'
			),
		);

		register_rest_route( 'study', '(?P<study_id>\d+)/chapters', array(
			array(
				'methods'  => WP_REST_Server::READABLE,
				'callback' => array( $this, 'get_chapters' ),
				'args'     => $posts_args,
			),
			array(
				'methods'  => WP_REST_Server::CREATABLE | WP_REST_Server::ACCEPT_JSON,
				'callback' => array( $this, 'create_post' ),
				'args'     => $this->get_endpoint_args_for_item_schema( true ),
			),
		) );

		register_rest_route( $this->base, '(?P<study_id>\d+)/chapters/(?P<chapter_id>\d+)', array(
			array(
				'methods'  => WP_REST_Server::READABLE,
				'callback' => array( $this, 'get_chapter' ),
				'args'     => $posts_args,
			),
		) );

	}


	public function get_chapter( $request, $context = 'view' ) {

		$args = (array) $request->get_params();
		$chapter_id = $args['chapter_id'];
		if ( empty( $chapter_id ) || ! $chapter = get_post( $chapter_id ) ) {
			return new WP_Error( 'json_post_invalid_id', __( 'Invalid post ID.' ), array( 'status' => 404 ) );
		}

		$chapter = $this->prepare_item_for_response( $chapter, $request );

		$query = array(
			'post_type'      => 'sc_study',
			'order'          => 'ASC',
			'orderby'        => 'menu_order',
			'post_parent'    => $chapter_id,
			'post_status'    => array( 'draft', 'pending', 'publish' ),
			'posts_per_page' => 10000,
		);

		$chapter_query = new WP_Query();
		$chapter_parts = $chapter_query->query( $query );

		$struct = array();

		foreach ( $chapter_parts as $part ) {

			// Do we have permission to read this post?
			if ( ! $this->check_read_permission( $part ) ) {
				continue;
			}

			$part_data = $this->prepare_item_for_response( $part, $request );

			// @todo patch real issue later. See class-wp-json-post.php line 906
			if ( is_wp_error( $part_data ) ) {
				continue;
			}

			/** Now get children for this chapter */
			$query['post_parent'] = $part['id'];
			$child_query          = new WP_Query();
			$children             = $child_query->query( $query );

			foreach ( $children as $child ) {

				// Do we have permission to read this post?
				if ( ! $this->check_read_permission( $child ) ) {
					continue;
				}

				$child_data = $this->prepare_item_for_response( $child, $request );

				if ( is_wp_error( $child_data ) ) {
					continue;
				}

				if ( sc_get_data_type( $child_data['id'] ) ) {
					$part_data['elements'][] = array(
						'id'      => $child_data['id'],
						'content' => $child_data['content'],
						'type'    => esc_html( sc_get_data_type( $child['id'] ) ),
						'private' => sc_answer_is_private( $child['id'] ),
					);
				} else {
					$part_data['sections'][] = array(
						'id'    => $child_data['id'],
						'title' => $child_data['title'],
					);
				}

			}

			$struct[] = $part_data;
		}

		$chapter = $this->prepare_response_for_collection( $chapter );
		$chapter['elements'] = $struct;

		$response      = rest_ensure_response( $chapter );
		$response->query_navigation_headers( $chapter_query );

		return $response;
	}

	public function get_chapters( $request, $context = 'view' ) {

		$args = (array) $request->get_params();
		$study_id = $args['study_id'];

		$study_id = sc_get_study_id( $study_id );

		if ( empty( $study_id ) ) {
			return new WP_Error( 'json_post_invalid_id', __( 'Invalid post ID.' ), array( 'status' => 404 ) );
		}

		$query = array(
			'post_type'      => 'sc_study',
			'order'          => 'ASC',
			'orderby'        => 'menu_order',
			'post_parent'    => $study_id,
			'post_status'    => array( 'draft', 'pending', 'publish' ),
			'posts_per_page' => 10000,
		);

		$study_query    = new WP_Query();
		$study_chapters = $study_query->query( $query );

		if ( 0 === $study_query->found_posts ) {
			return rest_ensure_response( array() );
		}

		$struct = array();

		foreach ( $study_chapters as $chapter ) {

			// Do we have permission to read this post?
			if ( ! $this->check_read_permission( $chapter ) ) {
				continue;
			}

			$chapter_data = $this->prepare_item_for_response( $chapter, $request );
			$chapter_data = $this->prepare_response_for_collection( $chapter_data );

			/** Now get children for this chapter */
			$query['post_parent'] = $chapter_data['id'];
			$child_query          = new WP_Query();
			$children             = $child_query->query( $query );

			foreach ( $children as $child ) {

				// Do we have permission to read this post?
				if ( ! $this->check_read_permission( $child ) ) {
					continue;
				}

				$child_data = $this->prepare_item_for_response( $child, $request );
				$child_data = $this->prepare_response_for_collection( $child_data );

				if ( sc_get_data_type( $child_data['id'] ) ) {
					$chapter_data['elements'][] = array(
						'id'      => $child_data['id'],
						'content' => $child_data['content'],
						'type'    => esc_html( sc_get_data_type( $child_data['id'] ) ),
						'private' => sc_answer_is_private( $child_data['id'] ),
					);
				} else {
					$chapter_data['sections'][] = array(
						'id'    => $child_data['id'],
						'title' => $child_data['title'],
					);
				}

			}

			$struct[] = $chapter_data;
		}

		$response      = rest_ensure_response( $struct );
		$response->query_navigation_headers( $study_query );

		return $response;
	}

}
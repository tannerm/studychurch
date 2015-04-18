<?php

SC_Custom_Post_Types::get_instance();
class SC_Custom_Post_Types {

	/**
	 * @var
	 */
	protected static $_instance;

	/**
	 * Only make one instance of the SC_Custom_Post_Types
	 *
	 * @return SC_Custom_Post_Types
	 */
	public static function get_instance() {
		if ( ! self::$_instance instanceof SC_Custom_Post_Types ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Add Hooks and Actions
	 */
	protected function __construct() {
		add_action( 'init', array( $this, 'router' ) );
	}

	public function router() {
		$this->cpt_study();
	}

	protected function  cpt_study() {
		$labels = array(
			'name'               => _x( 'Studies', 'post type general name', 'sc' ),
			'singular_name'      => _x( 'Study', 'post type singular name', 'sc' ),
			'add_new_item'       => __( 'Add New Study', 'sc' ),
			'new_item'           => __( 'New Study', 'sc' ),
			'edit_item'          => __( 'Edit Study', 'sc' ),
			'view_item'          => __( 'View Study', 'sc' ),
			'all_items'          => __( 'All Studies', 'sc' ),
			'search_items'       => __( 'Search Studies', 'sc' ),
			'not_found'          => __( 'No studies found.', 'sc' ),
			'not_found_in_trash' => __( 'No studies found in Trash.', 'sc' )
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'rewrite'            => array(
				'slug'       => 'studies',
				'with_front' => false,
			),
			'hierarchical'       => true,
			'has_archive'        => false,
			'menu_position'      => 5,
			'menu_icon'          => 'dashicons-welcome-write-blog',
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'page-attributes' )
		);

		register_post_type( 'sc_study', $args );
	}

}
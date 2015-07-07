<?php

SC_Meta_Boxes::get_instance();
class SC_Meta_Boxes {

	/**
	 * @var
	 */
	protected static $_instance;

	/**
	 * @var string
	 */
	protected static $_prefix = '_sc_';

	/**
	 * Only make one instance of the SC_Meta_Boxes
	 *
	 * @return SC_Meta_Boxes
	 */
	public static function get_instance() {
		if ( ! self::$_instance instanceof SC_Meta_Boxes ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Add Hooks and Actions
	 */
	protected function __construct() {
		add_action( 'cmb2_init', array( $this, 'init_meta_boxes' ) );
	}

	public function init_meta_boxes() {
		$this->landing_page();
		$this->study_page();
	}

	protected function study_page() {

		$cmb = new_cmb2_box( array(
			'id'            => 'study_meta',
			'title'         => __( 'Advanced', 'sc' ),
			'object_types'  => array( 'sc_study' ), // Post type
			'context'       => 'normal',
			'priority'      => 'high',
			'show_names'    => true, // Show field names on the left
		) );

		$cmb->add_field( array(
			'name'    => __( 'Data Type', 'sc' ),
			'id'      => self::$_prefix . 'data_type',
			'type'    => 'select',
			'options' => array(
				''               => __( 'None', 'sc' ),
				'question_short' => __( 'Short Answer Question', 'sc' ),
				'question_long'  => __( 'Long Answer Question', 'sc' ),
				'content'        => __( 'Content', 'sc' ),
				'assignment'     => __( 'Assignment', 'sc' ),
			),
		) );

		$cmb->add_field( array(
			'name'    => __( 'Privacy', 'sc' ),
			'desc'    => __( 'This question is private.', 'sc' ),
			'id'      => self::$_prefix . 'privacy',
			'type'    => 'checkbox',
		) );

	}

	protected function landing_page() {
		$prefix = self::$_prefix;

		/**
		 * Metabox to be displayed on a single page ID
		 */
		$cmb_about_page = new_cmb2_box( array(
			'id'           => $prefix . 'metabox',
			'title'        => __( 'About Page Metabox', 'cmb2' ),
			'object_types' => array( 'page', ), // Post type
			'context'      => 'normal',
			'show_on'      => array( 'key' => 'page-template', 'value' => 'templates/landing.php' ),
			'priority'     => 'high',
			'show_names'   => true, // Show field names on the left
		) );

		$cmb_about_page->add_field( array(
			'name' => __( 'Section 1 Title', 'cmb2' ),
			'id'   => $prefix . 'title_1',
			'type' => 'text',
		) );

		$cmb_about_page->add_field( array(
			'name' => __( 'Section 1 Content', 'cmb2' ),
			'id'   => $prefix . 'content_1',
			'type' => 'wysiwyg',
		) );

		$cmb_about_page->add_field( array(
			'name' => __( 'Section 2 Title', 'cmb2' ),
			'id'   => $prefix . 'title_2',
			'type' => 'text',
		) );

		$cmb_about_page->add_field( array(
			'name' => __( 'Section 2 Content', 'cmb2' ),
			'id'   => $prefix . 'content_2',
			'type' => 'wysiwyg',
		) );

		$cmb_about_page->add_field( array(
			'name' => __( 'Section 3 Title', 'cmb2' ),
			'id'   => $prefix . 'title_3',
			'type' => 'text',
		) );

		$cmb_about_page->add_field( array(
			'name' => __( 'Section 3 Content', 'cmb2' ),
			'id'   => $prefix . 'content_3',
			'type' => 'wysiwyg',
		) );

		$cmb_about_page->add_field( array(
			'name' => __( 'Section 4 Title', 'cmb2' ),
			'id'   => $prefix . 'title_4',
			'type' => 'text',
		) );

		$cmb_about_page->add_field( array(
			'name' => __( 'Section 4 Content', 'cmb2' ),
			'id'   => $prefix . 'content_4',
			'type' => 'wysiwyg',
		) );
	}

}
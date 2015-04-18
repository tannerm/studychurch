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
/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB directory)
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */

/**
 * Conditionally displays a field when used as a callback in the 'show_on_cb' field parameter
 *
 * @param  CMB2_Field object $field Field object
 *
 * @return bool                     True if metabox should show
 */
function cmb2_hide_if_no_cats( $field ) {
	// Don't show this field if not in the cats category
	if ( ! has_tag( 'cats', $field->object_id ) ) {
		return false;
	}
	return true;
}

add_filter( 'cmb2_meta_boxes', 'cmb2_sample_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function cmb2_sample_metaboxes( array $meta_boxes ) {

	// Start with an underscore to hide fields from custom fields list
	$prefix = '_sc_';

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$meta_boxes['study_meta'] = array(
		'id'            => 'study_meta',
		'title'         => __( 'Advanced', 'sc' ),
		'object_types'  => array( 'sc_study' ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
		'fields'        => array(
			array(
				'name'    => __( 'Data Type', 'sc' ),
				'id'      => $prefix . 'data_type',
				'type'    => 'select',
				'options' => array(
					'parent'   => __( 'Parent', 'sc' ),
					'question' => __( 'Question', 'sc' ),
					'assign'   => __( 'Assignment', 'sc' ),
					'content'  => __( 'Content', 'sc' ),
				),
			),
			array(
				'name'    => __( 'Input Type', 'sc' ),
				'id'      => $prefix . 'input_type',
				'type'    => 'select',
				'options' => array(
					'0'        => __( 'None', 'sc' ),
					'short'    => __( 'Short Answer', 'sc' ),
					'long'     => __( 'Long Answer', 'sc' ),
				),
			),
		),
	);

	return $meta_boxes;
}



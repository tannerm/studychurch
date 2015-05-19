<?php

SC_Front_End_Forms::get_instance();
class SC_Front_End_Forms {

	/**
	 * @var
	 */
	protected static $_instance;

	/**
	 * @var string
	 */
	protected static $_prefix = '_sc_';

	/**
	 * Only make one instance of the SC_Front_End_Forms
	 *
	 * @return SC_Front_End_Forms
	 */
	public static function get_instance() {
		if ( ! self::$_instance instanceof SC_Front_End_Forms ) {
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
		$this->login();
		$this->register();
	}

	protected function login() {
		$prefix = self::$_prefix;

		/**
		 * Metabox to be displayed on a single page ID
		 */
		$cmb = new_cmb2_box( array(
			'id'          => 'login-form',
			'hookup'      => false,
			'save_fields' => false,
		) );

		$cmb->add_field( array(
			'name' => __( 'Username', 'sc' ),
			'id'   => 'submitted_username',
			'type' => 'text',
		) );

	}

}
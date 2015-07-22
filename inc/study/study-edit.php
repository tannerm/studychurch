<?php

SC_Study_Edit::get_instance();
class SC_Study_Edit {

	/**
	 * @var
	 */
	protected static $_instance;

	/**
	 * Only make one instance of the SC_Study_Edit
	 *
	 * @return SC_Study_Edit
	 */
	public static function get_instance() {
		if ( ! self::$_instance instanceof SC_Study_Edit ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Add Hooks and Actions
	 */
	protected function __construct() {
		add_action( 'init', array( $this, 'maybe_study_save' ) );
		add_action( 'wp', array( $this, 'study_edit_actions' ) );
		add_filter( 'map_meta_cap', array( $this, 'can_user_edit_study' ), 10, 4 );
		add_filter( 'json_dispatch_args', array( $this, 'can_user_edit_study' ), 10, 4 );
	}

	public function maybe_study_save() {

		if ( ! isset( $_POST['study_id'], $_POST['study-save-nonce'], $_POST['step'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['study-save-nonce'], 'study-save' ) ) {
			return;
		}

		// make sure this user is the post author
		if ( get_current_user_id() != get_post( $_POST['study_id'] )->post_author ) {
			return;
		}

		$function = 'handle_save_' . $_POST['step'];

		if ( ! method_exists( $this, $function ) ) {
			return;
		}

		$this->$function();

	}

	/**
	 * Save study format
	 */
	protected function handle_save_format() {

		$study_id = absint( $_POST['study_id'] );

		$format = sc_get( 'study-format', 'lesson' );

		// make sure we have an expected value;
		$format = ( in_array( $format, array( 'lesson', 'week-5', 'week-7' ) ) ) ? $format : 'lesson';

		update_post_meta( $study_id, '_sc_study_format', $format );

		wp_safe_redirect( get_permalink() );
		exit();

	}

	/**
	 * Save study format
	 */
	protected function handle_save_intro() {

		$study_id = absint( $_POST['study_id'] );

		$study = get_object_vars( get_post( $study_id ) );
		$study['post_content'] = wp_kses_post( $_POST['study-description'] );

		if ( empty( $study['post_content'] ) ) {
			update_post_meta( $study_id, '_sc_study_no_intro', 1 );
		} else {
			wp_update_post( $study );
		}

		wp_safe_redirect( $_SERVER['REQUEST_URI'] );
		exit();

	}

	public function study_edit_actions() {
		if ( 'templates/study-manage.php' != get_page_template_slug() ) {
			return;
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'study_edit_scripts' ) );
		add_action( 'wp_footer', array( $this, 'study_edit_templates' ) );
	}

	public function study_edit_scripts() {
		wp_enqueue_style( 'froala-content', get_template_directory_uri() . '/assets/css/froala/froala_content.css' );
		wp_enqueue_style( 'froala-editor', get_template_directory_uri() . '/assets/css/froala/froala_editor.css' );
		wp_enqueue_style( 'froala-style', get_template_directory_uri() . '/assets/css/froala/froala_style.css' );

		wp_enqueue_script( 'froala-editor', get_template_directory_uri() . '/assets/js/lib/froala/froala_editor.min.js', array( 'jquery' ) );
		wp_enqueue_script( 'froala-fullscreen', get_template_directory_uri() . '/assets/js/lib/froala/plugins/fullscreen.min.js', array( 'jquery', 'froala-editor' ) );
	}

	/**
	 * Print Backbone templates
	 */
	public function study_edit_templates() {
		$slug = 'partials/backbone/study-edit';
		get_template_part( $slug, 'chapter' );
		get_template_part( $slug, 'chapter-sidebar' );
		get_template_part( $slug, 'item' );
	}

	public function can_user_edit_study( $caps = array(), $cap = '', $user_id = 0, $args = array() ) {

		// everyone can edit their own posts
		switch( $cap ) {
			case 'edit_posts' :
			case 'publish_posts' :
				$caps = array( 'exist' );
				break;
			case 'edit_post' :
			case 'read_post' :
				if ( isset( $args[0] ) && get_post( $args[0] )->post_author == $user_id ) {
					$caps = array( 'exist' );
				}
				break;
		}

		return $caps;
	}

}

/**
 * Get the current step for this study
 *
 * @param $study_id
 *
 * @return string
 */
function sc_study_edit_get_current_step( $study_id ) {

	if ( sc_get( 'step' ) ) {
		return sc_get( 'step' );
	}

	if ( ! get_the_title( $study_id ) ) {
		return 'title';
	}

	if ( ! get_post_meta( $study_id, '_sc_study_format') ) {
		return 'format';
	}

	if ( ( ! get_post( $study_id )->post_content ) && ( ! get_post_meta( $study_id, '_sc_study_no_intro', true ) ) ) {
		return 'intro';
	}

	return 'content';
}

/**
 * Does the current study edit step have the sidebar?
 *
 * @return bool
 */
function sc_study_edit_step_has_sidebar( $step = null ) {
	if ( ! $step ) {
		$step = sc_study_edit_get_current_step( sc_get( 'study' ) );
	}

	return ( in_array( $step, array( 'content' ) ) );
}

/**
 * Footer nav map for study edit
 *
 * @param $study_id
 * @param $current_step
 *
 * @return array
 */
function sc_study_get_manage_map( $study_id, $current_step ) {
	$nav = array();

	$study_map = array(
		'title'   => __( 'Title & Description', 'sc' ),
		'format'  => __( 'Format', 'sc' ),
		'intro'   => __( 'Introduction', 'sc' ),
		'content' => __( 'Content', 'sc' ),
	);

	if ( 'publish' == get_post_status( $study_id ) ) {
		unset( $study_map['publish'] );
	}

	foreach( $study_map as $step => $label ) {
		$class = ( $current_step == $step ) ? ' class="current"' : '';
		$nav[] = sprintf( '<span %s >%s</span>', $class, esc_html( $label ) );
	}

	return $nav;
}
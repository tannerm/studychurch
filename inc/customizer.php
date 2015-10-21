<?php
/**
 * sc Theme Customizer
 *
 * @package sc
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function sc_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'sc_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function sc_customize_preview_js() {
	wp_enqueue_script( 'sc_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'sc_customize_preview_js' );

SC_Customizer::get_instance();
class SC_Customizer {

	/**
	 * @var
	 */
	protected static $_instance;

	/**
	 * Only make one instance of the SC_Customizer
	 *
	 * @return SC_Customizer
	 */
	public static function get_instance() {
		if ( ! self::$_instance instanceof SC_Customizer ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Add Hooks and Actions
	 */
	protected function __construct() {
		add_action( 'customize_register', array( $this, 'router' ) );
	}

	public function router( $wp_customize ) {
		$this->branding( $wp_customize );
	}

	protected function branding( $wp_customize ) {

		$wp_customize->add_setting( 'primary_color', array(
			'default'   => '#3ebeb2',
			'transport' => 'refresh',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'primary_color', array(
			'label'    => __( 'Primary Color', 'sc' ),
			'section'  => 'colors',
			'settings' => 'primary_color'
		) ) );

		$wp_customize->add_setting( 'success_color', array(
			'default'   => '#3ebeb2',
			'transport' => 'refresh',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'success_color', array(
			'label'    => __( 'Success Color', 'sc' ),
			'section'  => 'colors',
			'settings' => 'success_color'
		) ) );

		$wp_customize->add_setting( 'warning_color', array(
			'default'   => '#fcb017',
			'transport' => 'refresh',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'warning_color', array(
			'label'    => __( 'Warning Color', 'sc' ),
			'section'  => 'colors',
			'settings' => 'warning_color'
		) ) );

		$wp_customize->add_setting( 'error_color', array(
			'default'   => '#e94141',
			'transport' => 'refresh',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'error_color', array(
			'label'    => __( 'Error Color', 'sc' ),
			'section'  => 'colors',
			'settings' => 'error_color'
		) ) );


		// Logos

		$wp_customize->add_section( 'sc_logos', array(
			'priority'   => 90,
			'capability' => 'edit_theme_options',
			'title'      => "Logos"
		) );

		$wp_customize->add_setting( 'sc_logo', array(
			'transport'  => 'refresh',
		) );

		$wp_customize->add_control( new WP_Customize_Image_Control(
			$wp_customize,
			'sc_logo',
			array(
				'label'  => 'Logo',
				'settings' => 'sc_logo',
				'section' => 'sc_logos',
			)
		) );

		$wp_customize->add_setting( 'sc_icon', array(
			'transport'  => 'refresh',
		) );

		$wp_customize->add_control( new WP_Customize_Image_Control(
			$wp_customize,
			'sc_icon',
			array(
				'label'  => 'Icon',
				'settings' => 'sc_icon',
				'section' => 'sc_logos',
			)
		) );
	}
}

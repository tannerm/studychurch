<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( class_exists( 'BP_Group_Extension' ) ) :

	class SC_Group_Study extends BP_Group_Extension {

		public $screen  = null;

		public static $_slug = 'study';

		public static $_name = 'Study';

		/**
		 * Constructor
		 */
		public function __construct() {

			/**
			 * Init the Group Extension vars
			 */
			$this->init_vars();

			/**
			 * Add actions and filters
			 */
			$this->setup_hooks();

		}

		public function setup_hooks() {
//			add_filter();
		}

		/** Group extension methods ***************************************************/

		/**
		 * Registers the and sets some globals
		 *
		 * @uses buddypress()                         to get the BuddyPress instance
		 * @uses BP_Group_Extension::init()
		 */
		public function init_vars() {
			$args = array(
				'slug'              => self::$_slug,
				'name'              => self::$_name,
				'visibility'        => 'private',
				'nav_item_position' => 80,
				'enable_nav_item'   => false,
				'screens'           => array(
					'admin' => array(
						'name'             => self::$_name,
						'enabled'          => true,
						'metabox_context'  => 'side',
						'metabox_priority' => 'core'
					),
					'create' => array(
						'enabled'  => false,
					),
					'edit' => array(
						'name'     => self::$_name,
						'position' => 80,
						'enabled'  => true,
					),
				)
			);

			parent::init( $args );
		}

		/**
		 * Group extension settings form
		 *
		 * Used in Group Administration, Edit and Create screens
		 *
		 *
		 * @param  int                                   $group_id the group ID
		 * @uses   is_admin()                            to check if we're in WP Administration
		 * @uses   checked()                             to add a checked attribute to checkbox if needed
		 * @uses   SC_Group_Study::group_get_option() to get the needed group metas.
		 * @uses   bp_is_group_admin_page()              to check if the group edit screen is displayed
		 * @uses   wp_nonce_field()                      to add a security token to check upon once submitted
		 * @return string                                html output
		 */
		public function edit_screen( $group_id = null ) {

			$is_admin = is_admin();

			if ( ! $is_admin ) : ?>

				<h4><?php printf( esc_html__( 'Group %s', 'sc' ), $this->name ); ?></h4>

			<?php endif; ?>

			<?php if ( $is_admin ) : ?>

				<legend class="screen-reader-text"><?php printf( esc_html__( 'Group %s', 'sc' ), $this->name ); ?></legend>

			<?php endif; ?>

			<p><?php _e( 'Select a study for this group or head over to your profile and write a new one!', 'sc'); ?></p>

			<div class="studies radio">
				<table style="width: 100%;">
					<tbody>
					<?php foreach ( get_posts( 'post_status=publish,private&post_type=sc_study&numberposts=-1&post_parent=0&author=' . get_current_user_id() ) as $study ) : ?>
						<tr>
							<td>
								<input type="radio" id="study-<?php echo absint( $study->ID ); ?>" name="_sc_study" value="<?php echo absint( $study->ID ); ?>" <?php checked( self::group_get_option( $group_id, '_sc_study' ), $study->ID ); ?>/>
							</td>
							<td>
								<label for="study-<?php echo absint( $study->ID ); ?>">
									<h4><?php echo get_the_title( $study->ID ); ?></h4>

									<p class="small"><?php echo apply_filters( 'get_the_excerpt', $study->post_excerpt ); ?></p>
								</label>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
				</div>

				<?php if ( bp_is_group_admin_page() ) : ?>
					<input type="submit" name="save" value="<?php _e( 'Save Settings', 'sc' );?>" class="button secondary expand" />
				<?php endif; ?>

			<?php
			wp_nonce_field( 'groups_settings_save_' . $this->slug, 'sc_group_study_admin' );
		}


		/**
		 * Save the settings for the current the group
		 *
		 * @param int                       $group_id the group id we save settings for
		 * @uses  check_admin_referer()     to check the request was made on the site
		 * @uses  bp_get_current_group_id() to get the group id
		 * @uses  wp_parse_args()           to merge args with defaults
		 * @uses  groups_update_groupmeta() to set the extension option
		 * @uses  bp_is_group_admin_page()  to check the group edit screen is displayed
		 * @uses  bp_core_add_message()     to give a feedback to the user
		 * @uses  bp_core_redirect()        to safely redirect the user
		 * @uses  bp_get_group_permalink()  to build the group permalink
		 */
		public function edit_screen_save( $group_id = null ) {

			if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) ) {
				return;
			}

			check_admin_referer( 'groups_settings_save_' . $this->slug, 'sc_group_study_admin' );

			if ( empty( $group_id ) ) {
				$group_id = bp_get_current_group_id();
			}

			$settings = array(
				'_sc_group_activate' => 0,
				'_sc_study' => '',
			);

			if ( ! empty( $_POST['_sc_group_activate'] ) ) {
				$settings['_sc_group_activate'] = absint( $_POST['_sc_group_activate'] );
			}

			if ( ! empty( $_POST['_sc_study'] ) ) {
				$settings['_sc_study'] = absint( $_POST['_sc_study'] );
			}

			// Save group settings
			foreach ( $settings as $meta_key => $meta_value ) {
				groups_update_groupmeta( $group_id, $meta_key, $meta_value );
			}

			if ( bp_is_group_admin_page() || is_admin() ) {

				// Only redirect on Manage screen
				if ( bp_is_group_admin_page() ) {
					bp_core_add_message( __( 'Settings saved successfully', 'sc' ) );
					bp_core_redirect( bp_get_group_permalink( buddypress()->groups->current_group ) . 'admin/' . $this->slug );
				}
			}
		}

		/**
		 * Adds a Meta Box in Group's Administration screen
		 *
		 * @param  int                              $group_id  the group id
		 * @uses   SC_Group_Study->edit_screen() to display the group extension settings form
		 */
		public function admin_screen( $group_id = null ) {
			$this->edit_screen( $group_id );
		}

		/**
		 * Saves the group settings (set in the Meta Box of the Group's Administration screen)
		 *
		 * @param  int                                   $group_id  the group id
		 * @uses   SC_Group_Study->edit_screen_save() to save the group extension settings
		 */
		public function admin_screen_save( $group_id = null ) {
			$this->edit_screen_save( $group_id );
		}

		/**
		 * We do not use group widgets
		 *
		 * @return boolean false
		 */
		public function widget_display() {
			return false;
		}

		/**
		 * Gets the group meta, use default if meta value is not set
		 *
		 * @param  int                    $group_id the group ID
		 * @param  string                 $option   meta key
		 * @param  mixed                  $default  the default value to fallback with
		 * @uses   groups_get_groupmeta() to get the meta value
		 * @uses   apply_filters()        call "SC_Group_Studys_option{$option}" to override the group meta value
		 * @return mixed                  the meta value
		 */
		public static function group_get_option( $group_id = 0, $option = '', $default = '' ) {
			if ( empty( $group_id ) || empty( $option ) ) {
				return false;
			}

			$group_option = groups_get_groupmeta( $group_id, $option );

			if ( '' === $group_option ) {
				$group_option = $default;
			}

			/**
			 * @param   mixed $group_option the meta value
			 * @param   int   $group_id     the group ID
			 */
			return apply_filters( "SC_Group_Studys_option{$option}", $group_option, $group_id );
		}

	}

endif ;

function sc_register_group_study_extension() {

	// if we aren't in a group, don't bother
	if ( ! bp_is_group() ) {
		return;
	}

	bp_register_group_extension( 'SC_Group_Study' );

}
add_action( 'bp_init', 'sc_register_group_study_extension' );

/**
 * Get study id for group
 *
 * @param null $group_id
 *
 * @return mixed
 */
function sc_get_group_study_id( $group_id = null ) {
	if ( ! $group_id ) {
		$group_id = bp_get_current_group_id();
	}

	return groups_get_groupmeta( $group_id, '_sc_study', true );
}

function sc_get_group_invite_key( $group_id = null ) {
	if ( ! $group_id ) {
		$group_id = bp_get_current_group_id();
	}

	$group = groups_get_group( 'group_id=' . $group_id );

	return md5( $group->date_created );
}
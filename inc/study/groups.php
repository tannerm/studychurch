<?php
/**
 * Trackbar Groups
 *
 * Groups component
 *
 * @package PB_Track
 * @subpackage Groups
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( class_exists( 'BP_Group_Extension' ) ) :

	/**
	 * Trackbar group class
	 *
	 * @package PB_Track
	 * @subpackage Groups
	 *
	 * @since Trackbar (1.1.0)
	 */
	class SC_Group_Study extends BP_Group_Extension {

		public $screen  = null;

		public static $_slug = 'study';

		public static $_name = 'Study';

		/**
		 * Constructor
		 *
		 * @package PB_Track
		 * @subpackage Groups
		 *
		 * @since Trackbar (1.1.0)
		 */
		public function __construct() {

			/**
			 * Init the Group Extension vars
			 */
			$this->init_vars();

			/**
			 * Add actions and filters to extend trackbar
			 */
			$this->setup_hooks();

		}

		/** Group extension methods ***************************************************/

		/**
		 * Registers the trackbar group extension and sets some globals
		 *
		 * @package PB_Track
		 * @subpackage Groups
		 *
		 * @since Trackbar (1.1.0)
		 *
		 * @uses buddypress()                         to get the BuddyPress instance
		 * @uses SC_Group_Study->enable_nav_item() to display or not the trackbar nav item for the group
		 * @uses BP_Group_Extension::init()
		 */
		public function init_vars() {
			$args = array(
				'slug'              => self::$_slug,
				'name'              => self::$_name,
				'visibility'        => 'private',
				'nav_item_position' => 80,
				'enable_nav_item'   => $this->enable_nav_item(),
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
		 * Loads Trackbar navigation if the group activated the extension
		 *
		 * @subpackage Groups
		 *
		 * @uses   bp_get_current_group_id()             to get the group id
		 * @uses   SC_Group_Study::group_get_option()    to check if extension is active for the group.
		 * @return bool                                  true if the extension is active for the group, false otherwise
		 */
		public function enable_nav_item( $group_id = null ) {

			if ( empty( $group_id ) ) {
				$group_id = bp_get_current_group_id();
			}

			if ( empty( $group_id ) ){
				return false;
			}

			if ( ! groups_is_user_member( get_current_user_id(), $group_id ) ) {
				return false;
			}

			return (bool) self::group_get_option( $group_id, '_sc_group_study_activate', true );
		}

		/**
		 * Group extension settings form
		 *
		 * Used in Group Administration, Edit and Create screens
		 *
		 * @package PB_Track
		 * @subpackage Groups
		 *
		 * @since Trackbar (1.1.0)
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

				<h4><?php printf( esc_html__( 'Group %s Settings', 'sc' ), $this->name ); ?></h4>

			<?php endif; ?>

				<?php if ( $is_admin ) : ?>

					<legend class="screen-reader-text"><?php printf( esc_html__( 'Group %s Settings', 'sc' ), $this->name ); ?></legend>

				<?php endif; ?>

				<p><?php _e( 'Studies allow the group go through a StudyChurch study together.' , 'sc'); ?></p>
				<div class="checkbox">
					<label>
						<input type="checkbox" id="_sc_group_activate" name="_sc_group_activate" value="1" <?php checked( self::group_get_option( $group_id, '_sc_group_activate', true ) ); ?> />
						<?php _e( 'Use studies with this group.', 'sc' ); ?>
					</label>
				</div>

				<div class="studies radio">
					<?php foreach( get_posts( 'post_type=sc_study&numberposts=-1&post_parent=0' ) as $study ) : ?>
						<label><input type="radio" name="_sc_study" value="<?php echo absint( $study->ID ); ?>" <?php checked( self::group_get_option( $group_id, '_sc_study' ), $study->ID ); ?>/> <?php echo get_the_title( $study->ID ); ?></label>
					<?php endforeach; ?>
				</div>

				<?php if ( bp_is_group_admin_page() ) : ?>
					<input type="submit" name="save" value="<?php _e( 'Save Settings', 'sc' );?>" />
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
		 * @package PB_Track
		 * @subpackage Groups
		 *
		 * @since Trackbar (1.1.0)
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
		 * @package PB_Track
		 * @subpackage Groups
		 *
		 * @since Trackbar (1.1.0)
		 *
		 * @param  int                                   $group_id  the group id
		 * @uses   SC_Group_Study->edit_screen_save() to save the group extension settings
		 */
		public function admin_screen_save( $group_id = null ) {
			$this->edit_screen_save( $group_id );
		}

		/**
		 * Perform actions about trackbar (insert/edit/delete/save prefs)
		 *
		 * @package PB_Track
		 * @subpackage Groups
		 *
		 * @since Trackbar (1.1.0)
		 *
		 * @uses  SC_Group_Study->is_trackbar()   Checks whether we're on a trackbar page of a group
		 * @uses  trackbar()                         to get the plugin's instance
		 * @uses  trackbar_handle_actions()          to insert/edit/delete/save prefs about a trackbar
		 * @uses  bp_get_current_group_id()             to get the group id
		 * @uses  SC_Group_Study::group_get_option() to get the needed group metas.
		 * @uses  groups_is_user_member()               to check the organizer is still a member of the group
		 * @uses  delete_post_meta()                    to remove a trackbar from a group
		 * @uses  trackbar_get_single_link()         to get the trackbar link
		 * @uses  bp_core_add_message()                 to give a feedback to the user
		 * @uses  do_action()                           call 'SC_Group_Studys_component_deactivated' or
		 *                                                   'SC_Group_Studys_member_removed' to perform custom actions
		 * @uses  bp_core_redirect()                    to safely redirect the user
		 * @uses  bp_is_current_component()             to check for a BuddyPress component
		 * @uses  bp_current_item()                     to make sure a group item is requested
		 * @uses  bp_do_404()                           to set the WP Query to a 404.
		 */
		public function group_handle_screens() {
			if ( $this->is_trackbar() ) {
				$trackbar = pbtrack();
				$this->screen                 = pbtrack_handle_actions();
				$trackbar->screens->screen = $this->screen;
			} else if ( bp_is_current_component( 'groups' ) && bp_is_current_action( $this->slug ) && bp_current_item() ) {
				bp_do_404();
				return;
			}
		}

		/**
		 * Loads needed trackbar template parts
		 *
		 * @package PB_Track
		 * @subpackage Groups
		 *
		 * @since Trackbar (1.1.0)
		 *
		 * @uses   trackbar_edit_title()     to output the title in edit context
		 * @uses   trackbar_edit_content()   to output the form to edit the trackbar
		 * @uses   trackbar_single_title()   to output the title in display context
		 * @uses   trackbar_single_content() to output the trackbar content
		 * @uses   trackbar_editor()         to load the trackbar BackBone editor
		 * @uses   bp_get_current_group_id()    to get the current group id
		 * @uses   trackbar_loop()           to output the trackbar for the group
		 * @return string                       html output
		 */
		public function display( $group_id = null ) {
			$bp = buddypress();

			if ( empty( $group_id ) ) {
				$group_id = bp_get_current_group_id();
			}

			$components = $bp->pbtrack->get_components();
			reset( $components );
			$default_action = key( $components );

			$link = trailingslashit( bp_get_group_permalink() . pbtrack()->get_component_slug() ); ?>

			<div class="item-list-tabs no-ajax" id="subnav" role="navigation">
				<ul>
					<?php foreach( $components as $slug => $component ) : $component_link = ( $default_action == $slug ) ? $link : $link . $slug; ?>
						<?php $bp->pbtrack->get_component_count[ $slug ]; ?>
						<li id="trackbar-<?php echo $slug; ?>-groups-li" <?php echo ( $slug == self::$_trackbar_action ) ? 'class="current selected"' : ''; ?>>
							<a href="<?php echo esc_url( $component_link ) ?>" class="trackbar-<?php echo $slug; ?>"><?php echo esc_html( $component['name'] ); ?> (<?php echo absint( PBTrack_Component::get_component_count( $slug ) ); ?>)</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div><!-- .item-list-tabs -->

			<?php
			include( PBTRACK_PATH . 'views/trackbar_loop.php' );

		}

		/**
		 * We do not use group widgets
		 *
		 * @package PB_Track
		 * @subpackage Groups
		 *
		 * @since Trackbar (1.1.0)
		 *
		 * @return boolean false
		 */
		public function widget_display() {
			return false;
		}

		/**
		 * Gets the group meta, use default if meta value is not set
		 *
		 * @package PB_Track
		 * @subpackage Groups
		 *
		 * @since Trackbar (1.1.0)
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

		/**
		 * Checks whether we're on a trackbar page of a group
		 *
		 * @package PB_Track
		 * @subpackage Groups
		 *
		 * @since Trackbar (1.1.0)
		 *
		 * @param  bool                   $retval
		 * @uses   bp_is_group()          to check we're in a group
		 * @uses   bp_is_current_action() to be sure we're on a trackbar group page
		 * @return bool                   true if on trackbar page of a group, false otherwise
		 */
		public function is_trackbar( $retval = false ) {
			if ( bp_is_group() && bp_is_current_action( $this->slug ) ) {
				$retval = true;
			}

			return $retval;
		}

		/**
		 * Update the last activity of the group when a trackbar attached to it is saved
		 *
		 * @package PB_Track
		 * @subpackage Groups
		 *
		 * @since Trackbar (1.1.0)
		 *
		 * @param  trackbar_Item              $trackbar the trackbar object
		 * @uses   groups_update_last_activity() to update group's latest activity
		 */
		public function group_last_activity( $trackbar = null ) {
			if ( empty( $trackbar->group_id ) ) {
				return;
			}

			// Update group's latest activity
			groups_update_last_activity( $trackbar->group_id );
		}

		/**
		 * Set up group's hooks
		 *
		 * @package PB_Track
		 * @subpackage Groups
		 *
		 * @since Trackbar (1.1.0)
		 *
		 * @uses  add_action() to perform custom actions at key points
		 * @uses  add_filter() to override trackbar key vars
		 */
		public function setup_hooks() {
			add_action( 'bp_screens',                             array( $this, 'group_handle_screens' ),        20    );
			add_action( 'pbtrack_after_save',                     array( $this, 'group_last_activity' ),         10, 1 );
			add_filter( 'pbtrack_load_scripts',                   array( $this, 'is_trackbar' ),              10, 1 );
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
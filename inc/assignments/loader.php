<?php

require get_template_directory() . '/inc/assignments/create.php';
require get_template_directory() . '/inc/assignments/template.php';
require get_template_directory() . '/inc/assignments/notifications.php';

/**
 * The class_exists() check is recommended, to prevent problems during upgrade
 * or when the Groups component is disabled
 */
if ( class_exists( 'BP_Group_Extension' ) ) :

	class SC_Group_Assignments extends BP_Group_Extension {
		/**
		 * Here you can see more customization of the config options
		 */
		function __construct() {
			parent::init( array(
				'slug' => 'assignments',
				'name' => 'Assignments',
				'nav_item_position' => 105,
				'screens' => array(
					'edit' => false,
					'create' => false,
				),
			) );

		}

		function display( $group_id = NULL ) {
			include( get_template_directory() . '/inc/assignments/edit-template.php' );
		}

	}
	bp_register_group_extension( 'SC_Group_Assignments' );

endif;
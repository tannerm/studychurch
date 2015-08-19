<?php

/**
 * BuddyPress - Users Profile
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<?php do_action( 'bp_before_profile_content' ); ?>

<div class="profile" role="main">

<?php switch ( bp_current_action() ) :

	// Edit
	case 'edit'   :
		bp_get_template_part( 'members/single/profile/edit' );
		break;

	// Change Avatar
	case 'change-avatar' :
		bp_get_template_part( 'members/single/profile/change-avatar' );
		break;

	case 'welcome' :
		bp_get_template_part( 'members/single/profile/welcome' );
		break;
	// Compose
	case 'public' :

		if ( 'welcome' == sc_get('page') || ( bp_is_user_profile() && ! bp_has_groups( array( 'user_id' => get_current_user_id() ) ) ) ) {
			bp_get_template_part( 'members/single/profile/welcome' );
		} else {
			bp_get_template_part( 'members/single/profile/home' );
		}

//		// Display XProfile
//		if ( bp_is_active( 'xprofile' ) )
//			bp_get_template_part( 'members/single/profile/profile-loop' );
//
//		// Display WordPress profile (fallback)
//		else
//			bp_get_template_part( 'members/single/profile/profile-wp' );

		break;

	// Any other
	default :
		bp_get_template_part( 'members/single/plugins' );
		break;
endswitch; ?>
</div><!-- .profile -->

<?php do_action( 'bp_after_profile_content' ); ?>
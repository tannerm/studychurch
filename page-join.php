<?php
/**
 * Template for managing the join page
 */

$valid = false;
$group = sc_get( 'group' );
$key   = sc_get( 'key' );

$group_id = groups_get_id( $group );
$group    = groups_get_group( 'group_id=' . $group_id );

/**
 * Make sure this is a valid link
 */
if ( $group_id && sc_get_group_invite_key( $group_id ) == $key ) {
	$valid = true;
}

/**
 * if the user is already logged in, add them to the group and carry on
 */
if ( is_user_logged_in() && $valid ) {
	groups_join_group( $group_id, get_current_user_id() );

	wp_safe_redirect( bp_get_group_permalink( $group ) );
	die();
}

get_header(); ?>

	<div id="restricted-message" class="row">

		<?php if ( ! $valid ) : ?>

			<div class="small-12 column">
				<div class="container">
					<h1><?php _e( 'Looks like you got the wrong link.', 'sc' ); ?></h1>

					<p><?php _e( 'Contact the admin of the group you are trying to join and ask him to verify the link you received.', 'sc' ); ?></p>
				</div>
			</div>

		<?php else : ?>
			<div class="small-12 column">
				<div class="container text-center">
					<h1><?php _e( 'You have been invited to join', 'sc' ); ?> <?php echo bp_get_group_name( $group ); ?></h1>

					<p><?php _e( sprintf( 'Please log in to join %s. Don\'t have an account? You can register for free!', bp_get_group_name( $group ) ), 'sc' ); ?></p>
				</div>
			</div>

			<div class="medium-6 small-centered columns" role="main">
				<div id="login">
					<?php get_template_part( 'partials/login' ); ?>
					<p>
						<a href="#" class="switch"><?php _e( "Need an account? You can register for free", 'sc' ); ?> &rarr;</a>
					</p>
				</div>

				<div id="start-now" class="hide">
					<?php get_template_part( 'partials/register' ); ?>
					<p><a href="#" class="switch"><?php _e( 'Already have an account? Login' ); ?> &rarr;</a></p>
				</div>
			</div>

		<?php endif; ?>

	</div>
<?php get_footer(); ?>
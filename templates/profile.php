<?php

/*
 * Template Name: Profile
 */

get_header();


$ud = wp_get_current_user();
?>

<div class="row">
	<div class="small-4 column">
		<?php bp_loggedin_user_avatar( 'type=full' ); ?>
		<h1><?php bp_loggedin_user_fullname(); ?></h1>

		<hr />

		<?php sc_add_button( '#', 'Create new group', 'group-create right', ' data-reveal-id="group-create-modal"' ); ?>
		<h3><?php _e( 'Groups', 'sc' ); ?></h3>

		<ul>
		<?php
		$groups = groups_get_user_groups( bp_loggedin_user_id() );
		foreach( $groups['groups'] as $group ) {
			$group = groups_get_group( array( 'group_id' => $group ) );
			printf( '<li><a href="%s">%s</a></li>', esc_url( trailingslashit( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/' . $group->slug . '/' ) ), esc_html( $group->name ) );
		}
		?>
		</ul>

		<hr />

		<h3><?php _e( 'Studies', 'sc' ); ?></h3>
	</div>
	<!-- role="main" -->

	<div class="small-8 column" role="main"></div>
</div>

<?php get_footer(); ?>
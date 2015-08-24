<?php

/**
 * BuddyPress - Users Header
 *
 * @package    BuddyPress
 * @subpackage bp-legacy
 */

?>

<?php do_action( 'bp_before_member_header' ); ?>

	<div id="item-header-content">

		<?php ; ?>
		<a href="<?php echo esc_url( trailingslashit( bp_displayed_user_domain() ) . bp_get_settings_slug() ); ?>" class="profile-edit-link"><i class="fa fa-pencil"></i></a>
		<?php bp_displayed_user_avatar( 'type=full' ); ?>

		<h2 class="no-margin"><?php bp_displayed_user_fullname(); ?></h2>

		<p><em class="user-nicename">@<?php echo bp_activity_get_user_mentionname( bp_displayed_user_id() ); ?></em></p>

	</div><!-- #item-header-content -->

<?php if ( 'public' == bp_current_action() ) : ?>
	<hr />

	<?php sc_add_button( '#', 'Create new group', 'group-create right', ' data-reveal-id="group-create-modal"' ); ?>
	<h4><?php _e( 'Groups', 'sc' ); ?></h4>

	<ul class="side-nav">
		<?php
		$groups = groups_get_user_groups();
		foreach ( $groups['groups'] as $group ) {
			$group = groups_get_group( array( 'group_id' => $group ) );
			printf( '<li><a href="%s">%s</a></li>', esc_url( trailingslashit( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/' . $group->slug . '/' ) ), esc_html( $group->name ) );
		}

		if ( empty( $groups['groups'] ) ) {
			printf( '<li><a href="#" class="group-create" data-reveal-id="group-create-modal">%s</a></li>', __( 'Start a study group', 'sc' ) );
		}
		?>
	</ul>

	<hr />

	<?php sc_add_button( '#', 'Create new study', 'study-create right', ' data-reveal-id="study-create-modal"' ); ?>
	<h4><?php _e( 'Studies', 'sc' ); ?></h4>
	<ul class="side-nav">
		<?php
		if ( $studies = get_pages( 'post_status=publish,pending,draft,private&post_type=sc_study&parent=0&authors=' . bp_displayed_user_id() ) ) {
			foreach ( $studies as $study ) {
				$title = get_the_title( $study->ID );

				if ( ! in_array( get_post_status( $study->ID ), array( 'publish', 'private' ) ) ) {
					$title .= sprintf( ' (pending review)' );
				}

				printf( '<li><a href="/study-edit/?action=edit&study=%d">%s</a></li>', $study->ID, esc_html( $title ) );
			}
		} else {
			printf( '<li><a href="#" class="study-create" data-reveal-id="study-create-modal">%s</a></li>', __( 'Write a study', 'sc' ) );
		}

		?>
	</ul>

<?php else : ?>
	<a href="<?php echo bp_displayed_user_domain(); ?>">&larr; <?php _e( 'Back to Profile', 'sc' ); ?></a>
<?php endif; ?>
<?php

/**
 * BuddyPress - Group Header
 *
 * @package    BuddyPress
 * @subpackage BuddyBoss
 */

?>

<?php do_action( 'bp_before_group_header' ); ?>

	<div id="item-actions">

			<div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
				<ul>

					<?php bp_get_options_nav(); ?>

					<?php do_action( 'bp_group_options_nav' ); ?>

				</ul>
			</div>

		<?php if ( bp_group_is_visible() ) : ?>

			<h3><?php _e( 'Group Admins', 'buddyboss' ); ?></h3>

			<?php bp_group_list_admins();

			do_action( 'bp_after_group_menu_admins' );

			if ( bp_group_has_moderators() ) :
				do_action( 'bp_before_group_menu_mods' ); ?>

				<h3><?php _e( 'Group Mods', 'buddyboss' ); ?></h3>

				<?php bp_group_list_mods();

				do_action( 'bp_after_group_menu_mods' );

			endif;

		endif; ?>

	</div><!-- #item-actions -->

	<div id="item-header-content">

		<?php do_action( 'bp_before_group_header_meta' ); ?>

		<div id="item-meta">

			<?php bp_group_description(); ?>
			<?php do_action( 'bp_group_header_meta' ); ?>

		</div>
	</div><!-- #item-header-content -->
	<div id="item-buttons" class="group">

		<?php do_action( 'bp_group_header_actions' ); ?>

	</div><!-- #item-buttons -->
<?php
do_action( 'bp_after_group_header' );
do_action( 'template_notices' );
?>
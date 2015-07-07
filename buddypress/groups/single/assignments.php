<?php
/**
 * Template for rendering assignment content
 */

$assignments = new SC_Assignments_Query();

?>
<h1 class="h4"><?php esc_html_e( 'Assignments', 'sc' ); ?></h1>

<?php if ( sc_user_can_manage_group() ) : ?>
	<?php cmb2_metabox_form( 'sc_assignments' ); ?>
<?php endif; ?>

<?php if ( $assignments->have_assignments() ) : $assignments->the_assignment(); ?>
	<h4><?php _e( 'Assignment Due: ', 'sc' ); ?><?php $assignments->the_date_formatted(); ?></h4>
	<?php $assignments->the_content(); ?>
<?php else : ?>
	<p>
		<?php esc_html_e( 'There are no assignment for this group.', 'sc' ); ?>
	</p>
<?php endif; ?>
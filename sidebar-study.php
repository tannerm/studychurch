<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package sc
 */

$study_id = sc_get_study_id();
?>
<div id="secondary" class="widget-area medium-4 small-12 columns sidebar" role="complementary">

	<?php if ( bp_get_group_id() ) : ?>
		<p><a href="<?php echo bp_get_group_permalink(); ?>"><i class="fa fa-users"></i> <?php _e( 'Back to ', 'sc' ); bp_group_name(); ?></a></p>
	<?php endif; ?>

	<h3><?php echo get_the_title( $study_id ); ?></h3>

	<ul class="table-of-contents">
		<?php sc_study_index(); ?>
	</ul>

	<a href="#" onclick="window.print(); return false;" class="button expand small"><i class="fa fa-print"></i> <?php _e( 'Print this lesson', 'sc' ); ?></a>

	<?php if ( current_user_can( 'edit_post', $study_id ) ) : ?>
		<a href="/study-edit/?action=edit&study=<?php echo absint( $study_id ); ?>" class="button expand small secondary"><i class="fa fa-pencil"></i> <?php _e( 'Edit this study', 'sc' ); ?></a>
	<?php endif; ?>

</div><!-- #secondary large-3-->

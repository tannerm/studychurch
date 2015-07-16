<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package sc
 */

$study_id = sc_get_study_id();
?>
<div id="secondary" class="widget-area large-4 small-12 columns" role="complementary">
	<p><a href="<?php echo bp_get_group_permalink(); ?>"><i class="fa fa-users"></i> <?php _e( 'Back to ', 'sc' ); bp_group_name(); ?></a></p>
	<h3><?php echo get_the_title( $study_id ); ?></h3>

	<ul class="table-of-contents">
		<?php sc_study_index(); ?>
	</ul>

</div><!-- #secondary large-3-->

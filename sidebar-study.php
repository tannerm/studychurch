<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package sc
 */

$study_id = sc_get_study_id();
?>
<div id="secondary" class="widget-area large-4 small-12 columns" role="complementary">
	<h3><?php echo get_the_title( $study_id ); ?></h3>

	<ul class="table-of-contents">
		<?php wp_list_pages( array( 'depth' => 1, 'child_of' => $study_id, 'post_type' => 'sc_study', 'title_li' => '' ) ); ?>
	</ul>
</div><!-- #secondary large-3-->

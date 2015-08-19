<?php $study_id = sc_get( 'study', 0 ); ?>
<div id="secondary" class="widget-area medium-4 small-12 columns" role="complementary">
	<?php if ( 'pending' == get_post_status( $study_id ) ) : ?>
		<p class="description"><?php _e( 'This study is pending approval.', 'sc' ); ?></p>
	<?php elseif ( 'draft' == get_post_status( $study_id ) ) : ?>
		<p class="description"><?php _e( 'This study is in draft mode.', 'sc' ); ?></p>
	<?php endif; ?>
	<a href="<?php echo add_query_arg( array(
		'action' => 'edit',
		'study'  => absint( $study_id ),
		'step'   => 'study'
	), get_the_permalink() ); ?>" class="right"><i class="fa fa-pencil"></i></a>

	<h3><?php echo get_the_title( $study_id ); ?></h3>

	<ul id="chapter-list" class="table-of-contents chapter-list">
		<!--				--><?php //sc_study_manage_index( $study_id ); ?>
	</ul>

	<p>
		<a href="#" id="new-chapter" class="ucase small"><i class="fa fa-plus-circle"></i> <?php _e( 'Create New Chapter', 'sc' ); ?></a>
	</p>

	<a href="<?php echo get_the_permalink( $study_id ); ?>" class="button expand small"><?php _e( 'View this study', 'sc' ); ?></a>

</div>
<!-- #secondary large-3-->
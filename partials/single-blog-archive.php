<?php

global $post;
if ( ! $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'post-header' ) ) {
	$image_src = get_template_directory_uri() . '/assets/images/study_group.jpg';
} else {
	$image_src = $image[0];
}

?>
<article>

	<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><h3><?php the_title(); ?></h3></a>

	<?php if ( has_post_thumbnail() ) : ?>
		<?php if ( sc_has_featured_size() ) : ?>
			<p>
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail( 'post-header' ); ?></a>
			</p>
		<?php else : ?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="alignright image"><?php the_post_thumbnail( 'thumbnail' ); ?></a>
		<?php endif; ?>
	<?php endif; ?>

	<div class="row">
		<div class="small-6 columns">
			<div class="left author-avatar">

				<p>
					<?php
					remove_filter( 'bp_core_fetch_avatar', array(
						SC_BP_Filter::get_instance(),
						'user_avatar_container'
					), 10, 2 );
					echo get_avatar( $post->post_author, 150 );
					add_filter( 'bp_core_fetch_avatar', array(
						SC_BP_Filter::get_instance(),
						'user_avatar_container'
					), 10, 2 );
					?>
					<?php echo get_userdata( $post->post_author )->display_name; ?>
				</p>

			</div>
		</div>

		<div class="small-6 columns">
			<p class="date"><?php the_date( 'M d, Y' ); ?> / <?php _e( 'Filed under:' ); ?> <?php the_category( ', ' ); ?></p>
		</div>
	</div>

	<?php the_excerpt(); ?>
	<p>
		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="link-next">Continue Reading</a>
	</p>
</article>
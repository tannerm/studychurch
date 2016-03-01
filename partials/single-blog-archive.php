<?php

global $post;
remove_filter( 'bp_core_fetch_avatar', array( SC_BP_Filter::get_instance(), 'user_avatar_container' ), 10, 2 ); ?>
<article>

	<?php if ( has_post_thumbnail() ) : ?>
			<p><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail( 'post-header' ); ?></a></p>
	<?php endif; ?>

	<div class="row">
		<div class="small-6 columns">
			<div class="left author-avatar">

				<p>
					<?php echo get_avatar( $post->post_author, 150 ); ?>
					<?php echo get_userdata( $post->post_author )->display_name; ?>
				</p>

			</div>
		</div>

		<div class="small-6 columns">
			<p class="date"><?php the_date( 'M d, Y' ); ?> / <?php _e( 'Filed under:' ); ?> <?php the_category( ', ' ); ?></p>
		</div>
	</div>

	<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><h3><?php the_title(); ?></h3></a>

	<?php the_excerpt(); ?>
	<p>
		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="link-next">Continue Reading</a>
	</p>
</article>
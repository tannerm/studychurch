<?php

/**
 * Template Name: Blog
 */

remove_filter( 'the_excerpt', 'sharing_display', 19 );

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

$blog = new WP_Query( array(
	'posts_per_page' => 5,
	'post_type'      => 'post',
	'paged'          => $paged,
) );

get_header(); ?>

	<header>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; ?>
	</header>

	<section id="blog-main" class="row">

		<main id="site-main" class="site-main small-12 medium-8 columns blogroll" role="main">

			<?php if ( $blog->have_posts() ) : ?>
				<?php while ( $blog->have_posts() ) : $blog->the_post(); ?>
					<?php get_template_part( 'partials/archive' ); ?>
				<?php endwhile; ?>

				<div class="paging">
					<?php previous_posts_link( 'Newer Posts', $blog->max_num_pages ); ?>
					<?php next_posts_link( 'Older Posts', $blog->max_num_pages ); ?>
				</div>

				<?php wp_reset_postdata(); ?>
			<?php endif; ?>

		</main>

		<?php get_sidebar(); ?>

	</section>



<?php get_footer(); ?>
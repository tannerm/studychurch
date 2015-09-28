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

	<section id="blog-main" class="row">
		<header class="small-12 column">
			<?php while ( have_posts() ) : the_post(); ?>
				<div class="text-center page-header">
					<h1><?php the_title(); ?></h1>
					<?php the_content(); ?>
				</div>
			<?php endwhile; ?>
		</header>

		<main id="site-main" class="site-main small-12 medium-8 large-9 columns blogroll" role="main">

			<?php if ( $blog->have_posts() ) : ?>
				<?php while ( $blog->have_posts() ) : $blog->the_post(); ?>
					<?php get_template_part( 'partials/single', 'blog-archive' ); ?>
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
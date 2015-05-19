<?php
/*
 * Template Name: Full Width
 */

get_header(); ?>

<div class="row">
	<div class="small-12 column" role="main">
		<?php while ( have_posts() ) : the_post(); ?>

			<?php if ( is_buddypress() ) : ?>
				<?php get_template_part( 'partials/content', 'bp' ); ?>
			<?php else : ?>
				<?php get_template_part( 'content', 'page' ); ?>

				<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() ) {
					comments_template();
				}
				?>

			<?php endif; ?>

		<?php endwhile; // end of the loop. ?>

	</div>
	<!-- role="main" -->
</div>
<?php get_footer(); ?>

<?php
/**
 * The Template for displaying all single posts.
 *
 * @package    WordPress
 * @subpackage BuddyBoss
 * @since      BuddyBoss 3.0
 */

get_header();

$elements = new WP_Query( array(
	'post_type'      => 'sc_study',
	'post_parent'    => get_the_ID(),
	'posts_per_page' => - 1,
) ); ?>

	<div class="row">
		<?php get_sidebar( 'study' ); ?>
		<div class="large-9 small-12 columns" role="main">
			<div id="content" role="main">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php get_template_part( 'partials/study' ); ?>

					<?php while ( $elements->have_posts() ) : $elements->the_post(); ?>

						<?php if ( 'parent' == get_post_meta( get_the_ID(), '_sc_data_type', true ) ) : ?>
							<?php get_template_part( 'partials/study', 'sub' ); ?>
						<?php else : ?>
							<?php get_template_part( 'partials/study', 'element' ); ?>
						<?php endif; ?>

					<?php endwhile; ?>

					<?php wp_reset_postdata(); ?>

				<?php endwhile; // end of the loop. ?>
			</div>
		</div>
	</div>
<?php get_footer(); ?>
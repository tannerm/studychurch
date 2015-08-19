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
	'meta_key'       => '_sc_data_type',
	'posts_per_page' => 9999,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
) );

get_comments();?>

	<div class="row">
		<?php get_sidebar( 'study' ); ?>
		<div id="buddypress" class="medium-8 small-12 columns" role="main">
			<div id="content" role="main">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php sc_study_navigation(); ?>

					<?php get_template_part( 'partials/study' ); ?>

					<?php while ( $elements->have_posts() ) : $elements->the_post(); ?>

						<?php get_template_part( 'partials/study', 'element' ); ?>

					<?php endwhile; ?>

					<?php wp_reset_postdata(); ?>

				<?php endwhile; // end of the loop. ?>
			</div>

			<?php sc_study_navigation(); ?>
		</div>
	</div>
<?php get_footer(); ?>
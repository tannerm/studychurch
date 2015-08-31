<?php
/**
 * The Template for displaying all single posts.
 *
 * @package sc
 */

if ( ! $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'post-header' ) ) {
	$image_src = get_template_directory_uri() . '/assets/images/study_group.jpg';
} else {
	$image_src = $image[0];
}

get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>

	<div class="post-header" style="background-image: url('<?php echo esc_url( $image_src ); ?>');">
		<h1><?php the_title(); ?></h1>
	</div>

	<div class="small-12 medium-9 large-7 medium-centered columns">

		<?php get_template_part( 'content', 'single' ); ?>

		<?php sc_content_nav( 'nav-below' ); ?>

		<?php
		// If comments are open or we have at least one comment, load up the comment template
		if ( comments_open() || '0' != get_comments_number() ) {
			comments_template();
		}
		?>

	</div><!-- column -->

<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
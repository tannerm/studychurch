<?php
/**
 * The Template for displaying all single posts.
 *
 * @package sc
 */

//remove_filter( 'the_content', 'sharing_display', 19 );

if ( ! $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'post-header' ) ) {
	$image_src = get_template_directory_uri() . '/assets/images/study_group.jpg';
} else {
	$image_src = $image[0];
}

global $post;

get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="post-header" style="background-image: url('<?php echo esc_url( $image_src ); ?>');">
			<div class="row post-header-content"></div>
		</div>

		<div class="row post-meta">
			<div class="small-12 columns">
				<div class="left author-avatar">
					<?php remove_filter( 'bp_core_fetch_avatar', array( SC_BP_Filter::get_instance(), 'user_avatar_container' ), 10, 2 ); ?>
					<?php echo get_avatar( $post->post_author, 150 ); ?>
				</div>
				<p class="date alignright"><?php the_date( 'F d, Y' ); ?></p>
				<p class="author-name">By: <?php echo get_userdata( $post->post_author )->display_name; ?></p>
			</div>
		</div>

		<div class="row">
			<div class="small-12 medium-8 columns">

				<div class="entry-content">
					<h1><?php the_title(); ?></h1>

					<p><?php _e( 'Filed under:'); ?> <?php the_category( ', ' ); ?></p>

					<?php the_content(); ?>
					<?php
					wp_link_pages( array(
						'before' => '<div class="page-links">' . __( 'Pages:', 'sc' ),
						'after'  => '</div>',
					) );
					?>
				</div>
				<!-- .entry-content -->

				<?php dynamic_sidebar( 'post-content-after' ); ?>

				<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() ) {
					comments_template();
				}
				?>

			</div>

			<?php get_sidebar(); ?>
		</div>
		<!-- column -->
	</article><!-- #post-## -->

<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
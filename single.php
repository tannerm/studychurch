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
			<div class="row post-header-content">
				<div class="small-12 column">
					<h1><?php the_title(); ?></h1>
				</div>
			</div>
		</div>

		<div class="row post-meta">
			<div class="large-2 medium-1 small-6 columns">
				<div class="left author-avatar">

					<p>
						<?php
						remove_filter( 'bp_core_fetch_avatar', array( SC_BP_Filter::get_instance(), 'user_avatar_container' ), 10, 2 );
						echo get_avatar( $post->post_author, 150 );
						add_filter( 'bp_core_fetch_avatar', array( SC_BP_Filter::get_instance(), 'user_avatar_container' ), 10, 2 );
						?>
						<?php echo get_userdata( $post->post_author )->display_name; ?>
					</p>

				</div>
			</div>
			<div class="large-8 medium-8 small-6 columns">
				<p class="date"><?php the_date( 'M d, Y' ); ?> / <?php _e( 'Filed under:'); ?> <?php the_category( ', ' ); ?></p>
			</div>
			<div class="large-2 medium-3 small-4 columns text-right">
				<?php if ( function_exists( 'sharing_display' ) ) : ?>
					<?php sharing_display( '', true ); ?>
				<?php endif; ?>
			</div>
		</div>

		<div class="row">
			<div class="small-12 medium-9 large-7 medium-centered columns">

				<div class="entry-content">
					<?php the_content(); ?>
					<?php
					wp_link_pages( array(
						'before' => '<div class="page-links">' . __( 'Pages:', 'sc' ),
						'after'  => '</div>',
					) );
					?>
				</div>
				<!-- .entry-content -->

				<?php sc_content_nav( 'nav-below' ); ?>

				<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() ) {
					comments_template();
				}
				?>

			</div>

		</div>
		<!-- column -->
	</article><!-- #post-## -->

<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
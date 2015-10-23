<?php
global $sc_item_count;

if ( ! $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'medium' ) ) {
	$thumbnail = get_stylesheet_directory_uri() . '/assets/images/default-study-cover.png';
} else {
	$thumbnail = array_shift( $thumbnail );
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( array( 'column', 'small-12', 'medium-6', 'large-4' ) ); ?>>

	<p><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><img title="<?php the_title_attribute(); ?>" alt="<?php the_title_attribute(); ?>" src="<?php echo esc_url( $thumbnail ); ?>" /></a></p>

	<h3><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>

	<p><?php the_excerpt(); ?></p>

</article>

<?php if ( 0 == $sc_item_count % 3 ) : ?>
	<div class="clearfix show-for-large-only"></div>
<?php endif; ?>

<?php if ( 0 == $sc_item_count % 2 ) : ?>
	<div class="clearfix show-for-medium-only"></div>
<?php endif; ?>
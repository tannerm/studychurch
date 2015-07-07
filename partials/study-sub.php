<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package    WordPress
 * @subpackage BuddyBoss
 * @since      BuddyBoss 3.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<!-- Title -->
	<header class="entry-header">
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	</header>
	<!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
	</div>
	<!-- .entry-content -->

	<footer class="entry-meta">

		<?php edit_post_link( __( 'Edit', 'buddyboss' ), '<span class="edit-link">', '</span>' ); ?>

	</footer>
	<!-- .entry-meta -->

</article><!-- #post -->

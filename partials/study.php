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
		<h2 class="entry-title"><?php the_title(); ?></h2>
	</header>
	<!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
	</div>

</article><!-- #post -->

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
		<h3 class="entry-title"><?php the_title(); ?></h3>
	</header>
	<!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>

		<?php comments_template( '/comments-study.php', true ); ?>

		<?php if ( sc_study_get_answer() ) : ?>
			<?php get_template_part( 'partials/study-element', 'answers' ); ?>
		<?php endif; ?>
	</div>
	<!-- .entry-content -->

</article><!-- #post -->
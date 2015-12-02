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
		<?php
		$content = get_the_content();
		$content = stripslashes( $content );
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );
		echo $content;
		?>

		<?php if ( in_array( sc_get_data_type(), array( 'question_long', 'question_short' ) ) ) : ?>
			<div class="study-answers-container">

				<?php comments_template( '/comments-study.php', true ); ?>

				<?php if ( sc_study_get_answer() ) : ?>
					<?php global $sc_answer; $sc_answer = sc_study_get_answer(); ?>
					<?php get_template_part( 'partials/study-element', 'answers' ); ?>
				<?php endif; ?>

			</div>
		<?php endif; ?>

	</div>
	<!-- .entry-content -->

</article><!-- #post -->
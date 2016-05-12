<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package sc
 */
?>
<div id="secondary" class="widget-area medium-4 small-12 columns" role="complementary">

	<?php if ( is_singular( 'post' ) ) : ?>
		<div class="text-center author-avatar widget-container">
			<?php remove_filter( 'bp_core_fetch_avatar', array( SC_BP_Filter::get_instance(), 'user_avatar_container' ), 10, 2 ); ?>

			<?php echo get_avatar( $post->post_author, 150 ); ?>

			<h4><?php echo the_author_link()?></h4>

			<p><?php echo nl2br( get_the_author_meta( 'description' ) ); ?></p>
		</div>
	<?php endif; ?>

	<?php do_action( 'before_sidebar' ); ?>
	<?php if ( ! dynamic_sidebar( 'blog-sidebar' ) ) : ?>

		<aside id="search" class="widget widget_search">
			<?php get_search_form(); ?>
		</aside>

		<aside id="archives" class="widget">
			<h3 class="widget-title"><?php _e( 'Archives', 'sc' ); ?></h3>
			<ul>
				<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
			</ul>
		</aside>

		<aside id="meta" class="widget">
			<h3 class="widget-title"><?php _e( 'Meta', 'sc' ); ?></h3>
			<ul>
				<?php wp_register(); ?>
				<li><?php wp_loginout(); ?></li>
				<?php wp_meta(); ?>
			</ul>
		</aside>

	<?php endif; // end sidebar widget area ?>
</div><!-- #secondary large-3-->

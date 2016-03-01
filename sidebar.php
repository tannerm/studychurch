<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package sc
 */
?>
<div id="secondary" class="widget-area medium-4 small-12 columns" role="complementary">
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

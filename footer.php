<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package sc
 */
?>

		<footer id="colophon" class="site-footer" role="contentinfo">
			<div class="row">
				<a href="<?php home_url(); ?>" class="left"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo_full_light.png" width="150" height="35" /></a>
				<a href="#" class="manual-optin-trigger button no-margin small right" data-optin-slug="yljvdgwjzdhlugyc">Join the waiting list.</a>
			</div>
			<div class="site-info">
				<?php do_action( 'sc_credits' ); ?>
			</div>
			<!-- .site-info -->
		</footer>
		<!-- #colophon -->

		<?php if ( is_front_page() && ! is_user_logged_in() ) : ?>
			<?php get_template_part( 'partials/modal', 'register' ); ?>
			<?php get_template_part( 'partials/modal', 'login' ); ?>
		<?php endif; ?>

		<?php wp_footer(); ?>

	</body>
</html>
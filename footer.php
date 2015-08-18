<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package sc
 */
?>

		</div>
		<!-- #page container -->

		<footer id="colophon" class="site-footer" role="contentinfo">
			<div class="row text-center">
				<div class="medium-6 columns">
					<a href="#" class="manual-optin-trigger button small" data-optin-slug="yljvdgwjzdhlugyc">Join the waiting list.</a>
				</div>
				<div class="medium-6 columns">
					<a href="<?php home_url(); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo_full_light.png" width="150" height="35" /></a>
				</div>
			</div>
			<div class="site-info">
				<?php do_action( 'sc_credits' ); ?>
			</div>
			<!-- .site-info -->
		</footer>
		<!-- #colophon -->

		<?php if ( ! is_user_logged_in() ) : ?>
			<?php get_template_part( 'partials/modal', 'register' ); ?>
			<?php get_template_part( 'partials/modal', 'login' ); ?>
		<?php endif; ?>

		<?php if ( bp_is_group() && sc_user_can_manage_group() ) : ?>
			<?php get_template_part( 'partials/modal', 'create-assignment' ); ?>
		<?php endif; ?>

		<?php wp_footer(); ?>

	</body>
</html>
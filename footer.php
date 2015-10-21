<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package sc
 */

if ( ! $icon = get_theme_mod( 'sc_icon' ) ) {
	$icon = get_stylesheet_directory_uri() . '/assets/images/logo_icon.png';
}
?>

		</div>
		<!-- #page container -->

		<footer id="colophon" class="site-footer" role="contentinfo">
			<div class="row">
				<div class="medium-6 columns">
					<?php
					if ( has_nav_menu( 'footer' ) ) {
						wp_nav_menu( array(
							'theme_location' => 'footer',
							'container'      => false,
							'items_wrap'     => '<ul id="%1$s" class="%2$s footer-menu">%3$s</ul>',
							'walker'         => new sc_walker()
						) );
					}

					?>
					<p>© Copyright StudyChurch 2015, All Rights Reserved.<br /><a href="https://studychur.ch/">StudyChurch</a> is a product of <a href="http://tannermoushey.com">iWitness Design</a>.</p>
				</div>
				<div class="medium-6 columns">
					<a href="<?php home_url(); ?>" class="right"><img src="<?php echo esc_url( $icon ); ?>" width="61" height="61" /></a>
					<div class="social">
						<?php dynamic_sidebar( 'landing-social' ); ?>
					</div>
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
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
			<div class="row">
				<div class="medium-6 columns">
					<?php
					wp_nav_menu( array(
						'theme_location' => 'footer',
						'container'      => false,
						'items_wrap'     => '<ul id="%1$s" class="%2$s footer-menu">%3$s</ul>',
						'walker'         => new sc_walker()
					) );
					?>
					<p>© Copyright StudyChurch 2015, All Rights Reserved.<br />StudyChurch is a product of <a href="http://tannermoushey.com">iWitness Design</a>.</p>
				</div>
				<div class="medium-6 columns">
					<a href="<?php home_url(); ?>" class="right"><img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo_icon.png" width="61" height="61" /></a>
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

		<?php if ( is_front_page() ) : ?>
			<div id="watch-video" class="reveal-modal" data-reveal>
				<div class="flex-video widescreen youtube">
					<iframe width="853" height="480" src="https://www.youtube.com/embed/Bv59cTf3e3Q" frameborder="0" allowfullscreen></iframe>
				</div>
				<a class="close-reveal-modal">&#215;</a>
			</div>
		<?php endif; ?>

		<?php if ( ! is_user_logged_in() ) : ?>
			<?php get_template_part( 'partials/modal', 'register' ); ?>
			<?php get_template_part( 'partials/modal', 'login' ); ?>
		<?php endif; ?>

		<?php if ( bp_is_group() && sc_user_can_manage_group() ) : ?>
			<?php get_template_part( 'partials/modal', 'create-assignment' ); ?>
		<?php endif; ?>

		<?php if ( is_user_logged_in() ) : ?>
			<div class="bug-report-cont">
				<div class="handle clearfix">
					<a href="#" class="no-margin button small bug-report-button"><?php _e( 'Need Help?', 'sc' ); ?></a>
				</div>
				<div class="form hide">
					<?php gravity_form( 4, false, false, false, false, true ); ?>
				</div>
			</div>
		<?php endif; ?>

		<?php wp_footer(); ?>

	</body>
</html>
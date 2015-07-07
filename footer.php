<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package sc
 */
?>

			<div class="large-12 columns">
				<footer id="colophon" class="site-footer" role="contentinfo">
					<br /><br /><br /><br /><br /><br /><br />
					<div class="site-info">
						<?php do_action( 'sc_credits' ); ?>
					</div>
					<!-- .site-info -->
				</footer>
				<!-- #colophon -->
			</div>

		</div><!-- #page -->

	<?php wp_footer(); ?>

	</body>
</html>
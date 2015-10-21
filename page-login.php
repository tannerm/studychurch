<?php

get_header(); ?>

	<div id="restricted-message" class="row">

		<div class="medium-6 small-12 column">
			<div class="container">
				<h1><?php _e( 'Oooops! ', 'sc' ); ?></h1>
				<p><?php _e( 'Looks like you need to log in or register before you can visit this page.', 'sc' ); ?></p>
			</div>
		</div>

		<div class="medium-6 columns" role="main">
			<div id="login-body">
				<?php get_template_part( 'partials/login' ); ?>
				<p><a href="#" class="switch"><?php _e( "Need an account? You can register for free", 'sc' ); ?> &rarr;</a></p>
			</div>

			<div id="start-now-body" class="hide">
				<?php get_template_part( 'partials/register' ); ?>
				<p><a href="#" class="switch"><?php _e( 'Already have an account? Login' ); ?> &rarr;</a></p>
			</div>
		</div>

	</div>
<?php get_footer(); ?>
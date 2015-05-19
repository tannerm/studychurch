<?php

get_header(); ?>

	<div class="row">

		<div class="small-12 column">
			<div class="container">
				<h1><?php _e( 'Oooops! ', 'sc' ); ?></h1>
				<p><?php _e( 'Looks like you need to log in or register before you can visit this page.', 'sc' ); ?></p>
			</div>
		</div>

		<div class="medium-6 columns" role="main">
			<h3><?php _e( 'Login', 'sc' ); ?></h3>
			<div id="sc-login-form">
				<?php wp_login_form(); ?>
			</div>
		</div>

		<div class="medium-6 columns">
			<h3><?php _e( 'Register', 'sc' ); ?></h3>
		</div>
		<!-- role="main" -->
	</div>
<?php get_footer(); ?>
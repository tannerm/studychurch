<div id="login" class="reveal-modal small" data-reveal>

	<h2 class="panel-title"><?php _e( 'Login', 'sc' ); ?></h2>

	<form action="" method="post" data-action="sc_login">

		<p class="text-center spinner hide"><i class="fa fa-circle-o-notch fa-spin"></i></p>

		<p>
			<label for="sc-login"><?php _e( 'Email', 'sc' ); ?></label>
			<input type="text" name="sc-login" id="sc-login" placeholder="john.doe@gmail.com" required />
		</p>

		<p>
			<label for="sc-password"><?php _e( 'Password', 'sc' ); ?></label>
			<input type="password" name="sc-password" id="sc-password" placeholder="**********" required />
		</p>


		<div class="text-center">
			<?php wp_nonce_field( 'sc-login', 'sc_login_key' ); ?>
			<input type="submit" value="Login" class="button secondary expand" />
		</div>

	</form>

	<p class="text-center no-margin"><a href="#" data-reveal-id="start-now"><?php _e( "Need an account? You can register for free &rarr;", 'sc' ); ?></a></p>
	<a class="close-reveal-modal">&#215;</a>

</div>
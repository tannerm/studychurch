<form action="" method="post" class="ajax-form" >

	<p class="text-center spinner hide"><i class="fa fa-circle-o-notch fa-spin"></i></p>


	<div class="row">
		<div class="small-12 columns">
			<label for="sc-login"><?php _e( 'Login or Email', 'sc' ); ?></label>
			<input type="text" name="user_login" id="sc-login" placeholder="john.doe@gmail.com" required />
		</div>
	</div>


	<div class="row">
		<div class="small-12 columns">
			<label for="sc-password"><?php _e( 'Password', 'sc' ); ?></label>
			<input type="password" name="user_password" id="sc-password" placeholder="**********" required />
		</div>
	</div>


	<div class="row">
		<div class="small-12 columns text-center">
			<input type="hidden" name="action" value="sc_login" />
			<?php wp_nonce_field( 'sc-login', 'sc_login_key' ); ?>
			<input type="submit" value="Login" class="button secondary expand" />
		</div>
	</div>

</form>
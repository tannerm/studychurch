<div id="start-now" class="reveal-modal small" data-reveal>

	<h2 class="panel-title"><?php _e( 'Create Your Account', 'sc' ); ?></h2>

	<form action="" method="post" data-action="sc_register">

		<p class="text-center spinner hide"><i class="fa fa-circle-o-notch fa-spin"></i></p>

		<div class="row">
			<div class="medium-6 columns">
				<label for="first-name"><?php _e( 'First Name', 'sc' ); ?></label>
				<input type="text" name="rcp_user_first" id="first-name" placeholder="John" required />
			</div>

			<div class="medium-6 columns">
				<label for="last-name"><?php _e( 'Last Name', 'sc' ); ?></label>
				<input type="text" name="rcp_user_last" id="last-name" placeholder="Doe" required />
			</div>
		</div>

		<div class="row">
			<div class="small-12 columns">
				<label for="email"><?php _e( 'Email', 'sc' ); ?></label>
				<input type="email" name="rcp_user_email" id="email" placeholder="john.doe@gmail.com" required />
			</div>
		</div>

		<div class="row">
			<div class="small-12 columns">
				<label for="password"><?php _e( 'Password', 'sc' ); ?></label>
				<input type="password" name="rcp_user_pass" id="password" placeholder="**********" required />
			</div>
		</div>

		<div class="text-center row">
			<div class="small-12 column">
				<?php
				$levels = new RCP_Levels();
				$free = $levels->get_level_by( 'price', 0 );

				if ( isset( $free->id ) ) : ?>
					<input type="hidden" name="rcp_level" value="<?php echo $free->id; ?>" />
				<?php endif; ?>

				<?php wp_nonce_field( 'rcp-register-nonce', 'rcp_register_nonce' ); ?>
				<input type="submit" value="<?php _e( 'Register', 'sc' ); ?>" class="button secondary expand" />
			</div>
		</div>

	</form>
	<p class="text-center no-margin"><a href="#" data-reveal-id="login">Already have an account? Login &rarr;</a></p>

	<a class="close-reveal-modal">&#215;</a>
</div>
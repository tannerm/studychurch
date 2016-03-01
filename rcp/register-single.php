<div class="row">
	<?php global $rcp_options, $rcp_level, $post; ?>

	<?php $level = rcp_get_subscription_details( $rcp_level ); ?>

	<?php if ( ! is_user_logged_in() ) { ?>
		<h3 class="rcp_header">
			<?php echo apply_filters( 'rcp_registration_header_logged_in', __( 'Register New Account', 'rcp' ) ); ?>
		</h3>
	<?php } else { ?>
		<h3 class="rcp_header">
			<?php echo apply_filters( 'rcp_registration_header_logged_out', __( 'Upgrade Your Subscription', 'rcp' ) ); ?>
		</h3>
	<?php }

	// show any error messages after form submission
	rcp_show_error_messages( 'register' ); ?>

	<form id="rcp_registration_form" class="rcp_form" method="POST" action="<?php echo esc_url( rcp_get_current_url() ); ?>">

		<div class="rcp_description"><?php echo wpautop( wptexturize( rcp_get_subscription_description( $rcp_level ) ) ); ?></div>

		<?php if ( ! is_user_logged_in() ) { ?>

			<?php do_action( 'rcp_before_register_form_fields' ); ?>


			<fieldset class="rcp_user_fieldset">
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
						<label for="rcp-login"><?php _e( 'Username', 'sc' ); ?></label>
						<input type="text" name="rcp_user_login" id="rcp-login" placeholder="johndoe" required />
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

				<div class="row">
					<div class="small-12 columns">
						<label for="password-conf"><?php _e( 'Confirm Password', 'sc' ); ?></label>
						<input type="password" name="rcp_user_pass_confirm" id="password-conf" placeholder="**********" required />
					</div>
				</div>

				<?php do_action( 'rcp_after_password_registration_field' ); ?>

			</fieldset>

			<hr />

		<?php } ?>

		<?php if ( rcp_has_discounts() && $level->price > '0' ) : ?>
			<fieldset class="rcp_discounts_fieldset">
				<p id="rcp_discount_code_wrap">
					<label for="rcp_discount_code">
						<?php _e( 'Discount Code', 'rcp' ); ?>
						<span class="rcp_discount_valid" style="display: none;"> - <?php _e( 'Valid', 'rcp' ); ?></span>
						<span class="rcp_discount_invalid" style="display: none;"> - <?php _e( 'Invalid', 'rcp' ); ?></span>
					</label>
					<input type="text" id="rcp_discount_code" name="rcp_discount" class="rcp_discount_code" value="" />
					<button class="rcp_button tiny expand secondary no-margin" id="rcp_apply_discount"><?php _e( 'Apply', 'rcp' ); ?></button>
				</p>
			</fieldset>
			<hr />
		<?php endif; ?>

		<?php do_action( 'rcp_after_register_form_fields' ); ?>

		<?php if ( $level->price > '0' ) : ?>

			<div class="rcp_gateway_fields">
				<?php
				$gateways = rcp_get_enabled_payment_gateways();
				if ( count( $gateways ) > 1 ) : $display = rcp_has_paid_levels() ? '' : ' style="display: none;"'; ?>
					<fieldset class="rcp_gateways_fieldset">
						<p id="rcp_payment_gateways"<?php echo $display; ?>>
							<select name="rcp_gateway" id="rcp_gateway">
								<?php foreach ( $gateways as $key => $gateway ) : $recurring = rcp_gateway_supports( $key, 'recurring' ) ? 'yes' : 'no'; ?>
									<option value="<?php echo esc_attr( $key ); ?>" data-supports-recurring="<?php echo esc_attr( $recurring ); ?>"><?php echo esc_html( $gateway ); ?></option>
								<?php endforeach; ?>
							</select>
							<label for="rcp_gateway"><?php _e( 'Choose Your Payment Method', 'rcp' ); ?></label>
						</p>
					</fieldset>
				<?php else: ?>
					<?php foreach ( $gateways as $key => $gateway ) : $recurring = rcp_gateway_supports( $key, 'recurring' ) ? 'yes' : 'no'; ?>
						<input type="hidden" name="rcp_gateway" value="<?php echo esc_attr( $key ); ?>" data-supports-recurring="<?php echo esc_attr( $recurring ); ?>" />
					<?php endforeach; ?>
				<?php endif; ?>
			</div>

		<?php endif; ?>

		<?php do_action( 'rcp_before_registration_submit_field' ); ?>

		<hr />
		<p id="rcp_submit_wrap">
			<input type="hidden" name="rcp_level" class="rcp_level" rel="<?php echo rcp_get_subscription_price( $rcp_level ); ?>" value="<?php echo absint( $rcp_level ); ?>" />
			<input type="hidden" name="rcp_register_nonce" value="<?php echo wp_create_nonce( 'rcp-register-nonce' ); ?>" />
			<input type="submit" name="rcp_submit_registration" id="rcp_submit" class="button expand" value="<?php echo apply_filters( 'rcp_registration_register_button', __( 'Register', 'rcp' ) ); ?>" />
		</p>
	</form>

</div>
<?php do_action( 'bp_before_member_settings_template' ); ?>

<?php bp_get_template_part( 'members/single/profile/change-avatar' ); ?>

<?php bp_get_template_part( 'members/single/profile/edit' ); ?>

<form action="<?php echo bp_displayed_user_domain() . bp_get_settings_slug() . '/general'; ?>" method="post" class="standard-form panel" id="settings-form">

	<?php if ( !is_super_admin() ) : ?>

		<p>
			<label for="pwd"><?php _e( 'Current Password <span class="small">(required to update email or change current password)</span>', 'buddypress' ); ?></label>
			<input type="password" name="pwd" id="pwd" size="16" value="" class="settings-input small" <?php bp_form_field_attributes( 'password' ); ?>/> &nbsp;<a href="<?php echo wp_lostpassword_url(); ?>" title="<?php esc_attr_e( 'Password Lost and Found', 'buddypress' ); ?>" class="small"><?php _e( 'Lost your password?', 'buddypress' ); ?></a>
		</p>

	<?php endif; ?>

	<p><label for="email"><?php _e( 'Account Email', 'buddypress' ); ?></label>
	<input type="email" name="email" id="email" value="<?php echo bp_get_displayed_user_email(); ?>" class="settings-input" <?php bp_form_field_attributes( 'email' ); ?>/></p>

	<hr />

	<label for="pass1"><?php _e( 'Change Password <span class="small">(leave blank for no change)</span>', 'buddypress' ); ?></label>
	<input type="password" name="pass1" id="pass1" size="16" value="" class="settings-input small password-entry" <?php bp_form_field_attributes( 'password' ); ?>/>
	<p class="small"><?php _e( 'New Password', 'buddypress' ); ?></p>

	<input type="password" name="pass2" id="pass2" size="16" value="" class="settings-input small password-entry-confirm" <?php bp_form_field_attributes( 'password' ); ?>/>
	<p class="small"><?php _e( 'Repeat New Password', 'buddypress' ); ?></p>

	<div id="pass-strength-result"></div>

	<?php do_action( 'bp_core_general_settings_before_submit' ); ?>

	<div class="submit">
		<input type="submit" name="submit" value="<?php esc_attr_e( 'Save Changes', 'buddypress' ); ?>" id="submit" class="auto button secondary expand small" />
	</div>

	<?php do_action( 'bp_core_general_settings_after_submit' ); ?>

	<?php wp_nonce_field( 'bp_settings_general' ); ?>

</form>

<?php do_action( 'bp_after_member_settings_template' ); ?>

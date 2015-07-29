<ul class="right">
	<li class="has-dropdown click">
		<a href="#"><?php bp_loggedin_user_avatar(); ?> <?php _e( 'Hello', 'sc' ); ?> <?php echo bp_core_get_user_displayname( get_current_user_id() ); ?>!</a>
		<ul class="dropdown">
			<li>
				<a href="<?php echo bp_loggedin_user_domain(); ?>"><?php _e( 'Profile', 'sc' ); ?></a>
			</li>
			<li>
				<a href="<?php echo wp_logout_url( get_home_url() ); ?>"><?php _e( 'Logout', 'sc' ); ?></a>
			</li>
		</ul>
		<?php /* bp_nav_menu( array( 'container'  => false,
								                          'items_wrap' => '<ul id="%1$s" class="%2$s dropdown">%3$s</ul>'
								) ); */ ?>
	</li>
</ul>
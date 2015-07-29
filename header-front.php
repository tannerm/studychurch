<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div class="row">
 *
 * @package sc
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<nav id="site-navigation" class="main-navigation top-bar" role="navigation" data-topbar data-options="is_hover: false">

		<div class="top-bar-section">
			<div class="text-right register">
				<?php if ( is_user_logged_in() ) : ?>
					<?php get_template_part( 'partials/header', 'loggedin-nav' ); ?>
				<?php elseif( 1 ) : ?>
					<a href="#" class="login" data-reveal-id="login"><i class="fa fa-user"></i> <?php _e( 'Login', 'sc' ); ?>
					</a>&nbsp;|&nbsp;
					<a id="signup" href="#" class="login" data-reveal-id="start-now"><i class="fa fa-user-plus"></i> <?php _e( 'Register', 'sc' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</nav>

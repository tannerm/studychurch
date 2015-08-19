<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div class="row">
 *
 * @package sc
 */

	$header_logo = ( is_user_logged_in() ) ? 'logo_icon.png' : 'logo_full.png';
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, target-densityDpi=device-dpi">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<?php do_action( 'before' ); ?>
	<header id="masthead" class="site-header" role="banner">
		<?php if ( is_user_logged_in() ) : ?>
			<div class="corner-ribbon top-left sticky red shadow">Beta</div>
		<?php endif; ?>

		<div class="contain-to-grid">
			<nav id="site-navigation" class="main-navigation top-bar" role="navigation" data-topbar data-options="is_hover: false">
				<ul class="title-area">
					<!-- Title Area -->
					<li class="name">
						<h1>
							<?php $link = ( is_user_logged_in() ) ? bp_loggedin_user_domain() : home_url( '/' ); ?>
							<a href="<?php echo esc_url( $link ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">

								<img src="<?php echo get_template_directory_uri(); ?>/assets/images/<?php echo $header_logo; ?>" />
								<span class="screen-reader"><?php bloginfo( 'name' ); ?></span>
							</a>
						</h1>
					</li>
					<!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
					<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
				</ul>

				<section class="top-bar-section">
					<?php if ( is_user_logged_in() ) : ?>
						<?php
						$location = ( is_user_logged_in() ) ? 'members' : 'public';
						wp_nav_menu( array(
							'theme_location' => $location,
							'container'      => false,
							'items_wrap'     => '<ul id="%1$s" class="%2$s main-menu left">%3$s</ul>',
							'walker'         => new sc_walker()
						) );
						?>

						<?php get_template_part( 'partials/header', 'loggedin-nav' ); ?>
					<?php else : ?>
						<?php get_template_part( 'partials/header', 'loggedout-nav' ); ?>
					<?php endif; ?>
				</section>

			</nav>
			<!-- #site-navigation -->
		</div>
	</header>
	<!-- #masthead -->
	<!-- Begin Page -->
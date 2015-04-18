<?php

/*
 * Template Name: Landing Page
 */

get_header( 'front' );

the_post();

?>

	<section id="study-group" class="study-group" style="background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/images/study_group.jpg');">
		<div class="content-container">
			<?php the_content(); ?>
		</div>
	</section>

	<div class="contain-to-grid sticky top-bar-container">
		<nav id="single-page-navigation" class="top-bar" data-topbar role="navigation" data-options="sticky_on: large">
			<ul>
				<li class="icon">
					<a href="#study-group">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo_icon.png" />
						<span class="screen-reader">StudyChurch</span>
					</a>
				</li>
				<li><a href="#<?php echo sanitize_title( get_post_meta( get_the_id(), '_sc_title_1', true ) ); ?>"><?php echo esc_html( get_post_meta( get_the_id(), '_sc_title_1', true ) ); ?></a></li>
				<li><a href="#<?php echo sanitize_title( get_post_meta( get_the_id(), '_sc_title_2', true ) ); ?>"><?php echo esc_html( get_post_meta( get_the_id(), '_sc_title_2', true ) ); ?></a></li>
				<li>&nbsp;</li>
				<li><a href="#<?php echo sanitize_title( get_post_meta( get_the_id(), '_sc_title_3', true ) ); ?>"><?php echo esc_html( get_post_meta( get_the_id(), '_sc_title_3', true ) ); ?></a></li>
				<li><a href="#<?php echo sanitize_title( get_post_meta( get_the_id(), '_sc_title_4', true ) ); ?>"><?php echo esc_html( get_post_meta( get_the_id(), '_sc_title_4', true ) ); ?></a></li>
			</ul>
		</nav>
	</div>

	<section id="<?php echo sanitize_title( get_post_meta( get_the_id(), "_sc_title_1", true ) ); ?>" class="how-it-works">
		<div class="row">
			<?php echo apply_filters( 'the_content', get_post_meta( get_the_id(), "_sc_content_1", true ) ); ?>
		</div>
	</section>

	<section id="<?php echo sanitize_title( get_post_meta( get_the_id(), "_sc_title_2", true ) ); ?>">
		<div class="who-its-for">
			<div class="row">
				<div class="small-centered small-10 columns">
					<?php echo apply_filters( 'the_content', get_post_meta( get_the_id(), "_sc_content_2", true ) ); ?>
				</div>
			</div>
		</div>
	</section>

	<section id="<?php echo sanitize_title( get_post_meta( get_the_id(), "_sc_title_3", true ) ); ?>" class="connect">
		<div class="social">
			<?php dynamic_sidebar( 'landing-social' ); ?>
		</div>

		<div class="row">
			<div class="small-10 small-centered columns">
				<?php echo apply_filters( 'the_content', get_post_meta( get_the_id(), "_sc_content_3", true ) ); ?>
			</div>
		</div>
	</section>

	<section id="<?php echo sanitize_title( get_post_meta( get_the_id(), "_sc_title_4", true ) ); ?>">
		<div class="get-involved">
			<div class="row">
				<div class="small-10 small-centered columns">
					<?php echo apply_filters( 'the_content', get_post_meta( get_the_id(), "_sc_content_4", true ) ); ?>
				</div>
			</div>
		</div>
	</section>

<?php get_footer(); ?>
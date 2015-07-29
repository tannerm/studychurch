<?php

get_header();

the_post();

?>

	<section id="study-group" class="study-group" style="background-image: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/images/study_group.jpg');">
		<div class="content-container">
			<h1>Engage your small&nbsp;group every day of the week.</h1>
			<p><a href="#" class="manual-optin-trigger button secondary no-margin small" data-optin-slug="yljvdgwjzdhlugyc">Join the waiting list.</a></p>
		</div>
	</section>

	<div class="social">
		<?php dynamic_sidebar( 'landing-social' ); ?>
	</div>

	<section id="<?php echo sanitize_title( get_post_meta( get_the_id(), "_sc_title_1", true ) ); ?>" class="how-it-works">
		<div class="row" style="overflow: visible;">
			<div class="small-12 column">
				<h2>StudyChurch is an online platform that makes it easy to manage and collaborate with your small groups.</h2>
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/ipad_2x.png" width="460" height="598" style="margin-right: 2rem;margin-left:-20%;float:left;display:none;"/>
				<ul>
					<li>
						<i class="fa fa-pencil-square-o"></i>
						<div>
							<h3>Write Lessons</h3>
							<p>The lesson builder makes it easy to write your own small group material whether that be companion lessons or a full blown study. Your group will get instant access to your updates so you don't have to worry about processing or distribution.</p>
						</div>
					</li>
					<li>
						<i class="fa fa-share-square-o"></i>
						<div>
							<h3>Share Answers</h3>
							<p>The lesson builder is more than just a way for you to distribute lesson material. Your small group members will be able to interact with the lessons by answering questions and then starting conversations on each others answers. By the time you meet, your group will have already begun discussing and collaborating on the lesson.</p>
						</div>
					</li>
					<li>
						<i class="fa fa-comments-o"></i>
						<div>
							<h3>Start Discussions</h3>
							<p>Collaborating on lesson answers isn't the only way to start a group discussion. Discuss anything from the group's main page and be sure to tag members or the whole group so they are notified of the discussion.</p>
						</div>
					</li>
					<li>
						<i class="fa fa-calendar"></i>
						<div>
							<h3>Manage Assignments</h3>
							<p>Ever get tired of sending multiple emails in a week to remind the small group of the assignment? StudyChurch handles assignment notifications so you just have to enter the assignment once and be done.</p>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</section>

	<section id="<?php echo sanitize_title( get_post_meta( get_the_id(), "_sc_title_2", true ) ); ?>">
		<div class="who-its-for">
			<div class="row">
				<div class="small-12 text-center columns">
					<h2>Watch StudyChurch in Action</h2>
					<iframe width="640" height="366" src="https://www.youtube.com/embed/cbIN35qI6mM" frameborder="0" allowfullscreen></iframe>
					<?php // echo apply_filters( 'the_content', get_post_meta( get_the_id(), "_sc_content_2", true ) ); ?>
				</div>
			</div>
		</div>
	</section>

<?php get_footer(); ?>
<?php

get_header();

the_post(); ?>

	<section id="study-group" class="study-group" style="background: url('<?php echo get_stylesheet_directory_uri(); ?>/assets/images/bible.jpg') center center;">
		<div class="content-container">
			<h1>Create Your Bible Study Online</h1>
			<p>StudyChurch makes it easy to manage and collaborate with your small groups.</p>
			<p><a href="#write-lessons" onclick="jQuery('html, body').animate({scrollTop: jQuery('#write-lessons').offset().top}, 500); return false;">See How <i class="fa fa-angle-right"></i></a> <a href="#" data-reveal-id="watch-video">Watch the Video <i class="fa fa-youtube-play"></i></a></p><br /><br />
			<p><a href="#" data-reveal-id="start-now" class="gbutton secondary no-margin small round ucase wide">Sign up for free</a></p>
		</div>
	</section>

	<section id="write-lessons" class="write-lessons">
		<img class="show-for-medium-up" src="<?php echo get_template_directory_uri(); ?>/assets/images/write-lessons.jpg" />
		<div class="row">
			<i data-sr="enter top reset" class="fa fa-pencil"></i>
			<div class="small-2 medium-6 columns">
			</div>
			<div class="small-10 medium-6 columns">
				<div data-sr="wait .5s, enter  right slowly and move 30px reset" class="content">
					<h3>Write Lessons</h3>
					<p>The lesson builder makes it easy to write your own small group material whether that be companion lessons or a full blown study. Your group will get instant access to your updates so you don't have to worry about processing or distribution.</p>
				</div>
			</div>
		</div>
	</section>

	<section class="share-answers">
		<img class="show-for-medium-up" src="<?php echo get_template_directory_uri(); ?>/assets/images/share-answers.jpg" />
		<div class="row">
			<i data-sr="enter top reset" class="fa fa-share giant-circle"></i>
			<div class="small-10 medium-6 columns">
				<div data-sr="wait .5s, enter left and move 30px reset" class="content">
					<h3>Share Answers</h3>
					<p>Your small group members will be able to interact with the lessons by answering questions and then starting conversations on each others answers. By the time you meet, your group will have already begun discussing and collaborating on the lesson.</p>
				</div>
			</div>
			<div class="small-2 medium-6 columns"></div>
		</div>
	</section>

	<section class="round-table">
		<div class="row">
			<div class="small-12 column">

				<div data-sr="enter right reset" class="start-discussions content clearfix">
					<i class="fa fa-comments"></i>
					<div class="point-text">
						<h3>Start Discussions</h3>
						<p>Collaborating on lesson answers isn't the only way to start a group discussion. Discuss anything from the group's main page and be sure to tag members or the whole group so they are notified of the discussion.</p>
					</div>
				</div>

				<div class="manage-assignments clearfix">
					<img class="show-for-medium-up" src="<?php echo get_template_directory_uri(); ?>/assets/images/round-table.jpg" />

					<div class="content">

						<div data-sr="enter right reset">
							<i class="fa fa-file-o"></i>
							<div class="point-text">
								<h3>Manage Assignments</h3>
								<p>Ever get tired of sending multiple emails in a week to remind the small group of the assignment? StudyChurch handles assignment notifications so you just have to enter the assignment once and be done.</p>
							</div>
						</div>

						<div class="cta">
							<h1 data-sr="wait .5s, enter reset">Bring Your Bible&nbsp;Study to&nbsp;life</h1>
							<p data-sr="wait 1s, enter reset"><a href="#" data-reveal-id="start-now" class="button secondary round ucase wide" data-optin-slug="yljvdgwjzdhlugyc">Sign up for free</a></p>
						</div>

					</div>

				</div>

			</div>
		</div>
	</section>

<?php get_footer(); ?>
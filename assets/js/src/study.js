jQuery(document).ready(function($){
	'use strict';

	var answers = function( $container ) {
		var SELF = this;

		SELF.$container = $container;
		SELF.data  = {};

		SELF.init = function() {
			SELF.$form      = $container.find('.comment-form');
			SELF.$answer    = SELF.$form.find('textarea');
			SELF.$save      = SELF.$form.find('.sudo-save');

			SELF.$container.on('click', '.edit-answer', SELF.setupCommentForm);
			SELF.$save.on( 'click', SELF.triggerSubmission );
			SELF.$answer.on('keyup keydown', SELF.autoGrow );
			SELF.$form.on('submit', SELF.handleSubmission);
		};

		SELF.setupCommentForm = function(e) {
			SELF.$container.find('.study-answers').remove();
			SELF.$form.show().find('textarea').focus().select();
			return false;
		};

		SELF.autoGrow = function(e) {
			var textarea = e.target;
			textarea.style.height = textarea.scrollHeight + "px";
		};

		SELF.triggerSubmission = function(e) {
			e.preventDefault;

			SELF.$form.submit();
			SELF.$save.text('Saving');
			return false;
		};

		SELF.handleSubmission = function(e){
			e.preventDefault();

			SELF.data['answer']     = SELF.$form.find('textarea[name=comment]').val();
			SELF.data['post_id']    = SELF.$form.find('input[name=comment_post_ID]').val();
			SELF.data['comment_id'] = SELF.$form.find('input[name=comment_ID]').val();
			SELF.data['group_id']   = SELF.$form.find('input[name=group_ID]').val();

			wp.ajax.send( 'sc_save_answer', {
				success: SELF.success,
				error: SELF.error,
				data:    SELF.data
			} );

		};

		SELF.success = function(data) {
			SELF.$save.text('Save');
			SELF.$form.find('input[name=comment_ID]').val(data.comment_ID);
			SELF.$form.hide();
			SELF.$container.append(data.answers);
		};

		SELF.error = function(message) {
			console.log('error');
			console.log(message);
		};

		SELF.init();

	};

	$(document).ready(function(){
		$('.sc_study .study-answers-container').each(function(){
			new answers( $(this) );
		});

		$('.study-answers.activity').on('click', '.toggle-comments', function(e) {
			var $this = $(e.target);
			$this.parents('.groups').toggleClass('comments-hide');
			return false;
		});

		$('.activity-list-others').on('click', '.toggle-answers', function(e) {
			var $this = $(e.target);
			$this.parents('.activity-list-others').toggleClass('answers-hide');
			return false;
		});
	});

});
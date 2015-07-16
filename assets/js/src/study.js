jQuery(document).ready(function($){
	'use strict';

	var answers = function( $form ) {
		var SELF = this;

		SELF.$form = $form;
		SELF.data  = {};

		SELF.init = function() {
			SELF.$answer = SELF.$form.find('textarea');
			SELF.$save = SELF.$form.find('.sudo-save');

			SELF.$save.on( 'click', SELF.triggerSubmission );
			SELF.$answer.on('keyup', SELF.autoSave );
			SELF.$form.on('submit', SELF.handleSubmission);
		};

		SELF.autoSave = function() {
			console.log( 'keyup' );
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
			SELF.data['group_id'] = SELF.$form.find('input[name=group_ID]').val();

			wp.ajax.send( 'sc_save_answer', {
				success: SELF.success,
				error: SELF.error,
				data:    SELF.data
			} );

		};

		SELF.success = function(data) {
			SELF.$save.text('Save');
			SELF.$form.find('input[name=comment_ID]').val(data.comment_ID);
		};

		SELF.error = function(message) {
			console.log('error');
			console.log(message);
		};

		SELF.init();

	};

	$(document).ready(function(){
		$('.sc_study .comment-form').each(function(){
			new answers( $(this) );
		});
	});

});
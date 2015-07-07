/*! StudyChurch - v0.1.0 - 2015-07-07
 * http://wordpress.org/themes
 * Copyright (c) 2015; * Licensed GPLv2+ */
(function($) {
	'use strict';

	var scGroupCreate = function() {
		var SELF = this;

		SELF.data    = {'security': scGroupCreateData.security};

		SELF.init = function() {
			SELF.$form = $( document.getElementById( 'group-create') );

			if ( ! SELF.$form.length ) {
				return;
			}

			SELF.$form.on('submit', SELF.handleSubmission);
		};

		SELF.handleSubmission = function(e) {
			e.preventDefault();

			SELF.data['group-name'] = SELF.$form.find('[name=group-name]').val();
			SELF.data['group-desc'] = SELF.$form.find('[name=group-desc]').val();
			SELF.data['study-name'] = SELF.$form.find('[name=study-name]').val();

			SELF.$form.find('.error-message').remove();

			wp.ajax.send( 'sc_group_create', {
				success: SELF.response,
				data:    SELF.data
			} );

		};

		SELF.response = function(url) {
			SELF.$form.find('.status-message').remove();
			SELF.$form.prepend('<p class="success-message">' + scGroupCreateData.success + '</p>');
			window.location = url;
		};

		SELF.init();
	};

	$(document).ready( function(){
		new scGroupCreate();
	});

})(jQuery);
jQuery(document).ready(function($){
	'use strict';

	var ajaxLogin = function() {
		var SELF = this;

		SELF.data    = {'security': scAjaxLogin.security};

		SELF.init = function(id) {
			SELF.$loginContainer = $(id);

			if ( ! SELF.$loginContainer.length ) {
				return;
			}

			SELF.$form = SELF.$loginContainer.find('form');

			SELF.$form.on('submit', SELF.handleSubmission);
		};

		SELF.handleSubmission = function(e){
			e.preventDefault();

			SELF.data['log'] = SELF.$form.find('#user_login').val();
			SELF.data['pwd'] = SELF.$form.find('#user_pass').val();

			SELF.$form.find('.error-message').remove();
			SELF.$form.prepend('<p class="status-message">Logging in...</p>');

			wp.ajax.send( 'sc_login', {
				success: SELF.success,
				error: SELF.error,
				data:    SELF.data
			} );

		};

		SELF.success = function() {
			SELF.$form.find('.status-message').remove();
			SELF.$form.prepend('<p class="success-message">Success! Reloading the page...</p>');
			window.location.reload();
		};

		SELF.error = function(message) {
			SELF.$form.prepend('<p class="error-message">' + status.message + '</p>');
		};

		SELF.init('#sc-login-form');

	};

	new ajaxLogin();
});
jQuery(document).ready(function($){
	'use strict';

	var answers = function( $form ) {
		var SELF = this;

		SELF.$form = $form;
		SELF.data  = {};

		SELF.init = function() {
			SELF.$answer = SELF.$form.find('textarea');

			SELF.$answer.on('keyup', SELF.autoSave );
			SELF.$form.on('submit', SELF.handleSubmission);
		};

		SELF.autoSave = function() {
			console.log( 'keyup' );
		};

		SELF.handleSubmission = function(e){
			e.preventDefault();

			SELF.data['answer']     = SELF.$form.find('textarea[name=comment]').val();
			SELF.data['post_id']    = SELF.$form.find('input[name=comment_post_ID]').val();
			SELF.data['comment_id'] = SELF.$form.find('input[name=comment_ID]').val();

			wp.ajax.send( 'sc_save_answer', {
				success: SELF.success,
				error: SELF.error,
				data:    SELF.data
			} );

		};

		SELF.success = function(data) {
			console.log('save');
			console.log(data);
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
(function ($, window, undefined) {
	'use strict';

	$(document).foundation();

	$(document).ready(function () {
		 $(document.getElementById('single-page-navigation')).singlePageNav();

		//$(document.getElementById('single-page-navigation')).onePageNav({
		//	currentClass: 'current',
		//	changeHash  : true,
		//	scrollSpeed : 750
		//});

		if ($('.study-group .content-container').outerHeight() < window.innerHeight) {
			$('.study-group').height(window.innerHeight - $('.top-bar-container').outerHeight() + 'px');
		}

		$('.study-group #input_1_1').on('focus', function() {
			$(document).foundation();
			$('#field_1_2').show();
		})
	})


})(jQuery, this);
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
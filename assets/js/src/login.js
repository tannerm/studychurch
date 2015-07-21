/**
 * Handle asynchronous user login
 * http://learningworksforkids.com/
 *
 * Copyright (c) 2014 Tanner Moushey, iWitness Design
 * Licensed under the GPLv2+ license.
 */

( function( $, window ) {
	'use strict';

	var ajaxLogin = function () {
		var SELF = this;

		SELF.data = {};

		SELF.init = function (id) {
			SELF.$loginContainer = $(id);

			if (!SELF.$loginContainer.length) {
				return;
			}

			SELF.$form = SELF.$loginContainer.find('form');

			SELF.$form.on('submit', SELF.handleSubmission);
		};

		SELF.handleSubmission = function (e) {
			var action = SELF.$form.attr('data-action');

			if ( ! action ) {
				return;
			}

			e.preventDefault();

			SELF.data['log']        = SELF.$form.find('input[name="sc-login"]').val();
			SELF.data['pwd']        = SELF.$form.find('input[name="sc-password"]').val();
			SELF.data['security']   = SELF.$form.find('input[name="sc_login_key"]').val();
			SELF.data['rememberme'] = true;

			SELF.$form.find('.alert-box').remove();
			SELF.$form.find('.spinner').show();

			wp.ajax.send( action, {
				data : SELF.data,
				success : SELF.response,
				error : SELF.error
			});
		};

		SELF.response = function (data) {
			SELF.$form.find('.spinner').hide();
			SELF.$form.prepend('<p class="alert-box success success-message">Success! Taking you to your profile.</p>');
			window.location = '/profile/';
		};

		SELF.error = function( message ) {
			if ( ! message ) {
				message = "Please make sure that you have filled in both your email and password."
			}
			SELF.$form.find('.spinner').hide();
			SELF.$form.prepend( '<div class="alert-box alert" data-alert>' + message + '</div>');
		}

	};

	var scAjaxLogin = new ajaxLogin();
	scAjaxLogin.init('#login');

} )( jQuery, this );
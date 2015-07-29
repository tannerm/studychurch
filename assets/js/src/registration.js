/**
 * Handle asynchronous user registration
 * http://learningworksforkids.com/
 *
 * Copyright (c) 2014 Tanner Moushey, iWitness Design
 * Licensed under the GPLv2+ license.
 */

( function( $, window, undefined ) {
	'use strict';

	var ajaxRegister = function () {
		var SELF = this;

		SELF.data = {};

		SELF.init = function (id) {
			SELF.$registerContainer = $(id);

			if (!SELF.$registerContainer.length) {
				return;
			}

			SELF.$form = SELF.$registerContainer.find('form');

			SELF.$form.on('submit', SELF.handleSubmission);
		};

		SELF.handleSubmission = function (e) {
			var action = SELF.$form.attr('data-action');

			if ( ! action ) {
				return;
			}

			e.preventDefault();

			SELF.data['rcp_level']             = SELF.$form.find('input[name="rcp_level"]').val();
			SELF.data['rcp_user_first']        = SELF.$form.find('input[name="rcp_user_first"]').val();
			SELF.data['rcp_user_last']         = SELF.$form.find('input[name="rcp_user_last"]').val();
			SELF.data['rcp_user_login']        = SELF.$form.find('input[name="rcp_user_email"]').val();
			SELF.data['rcp_user_email']        = SELF.$form.find('input[name="rcp_user_email"]').val();
			SELF.data['rcp_user_pass']         = SELF.$form.find('input[name="rcp_user_pass"]').val();
			SELF.data['rcp_user_pass_confirm'] = SELF.$form.find('input[name="rcp_user_pass"]').val();
			SELF.data['rcp_register_nonce']    = SELF.$form.find('input[name="rcp_register_nonce"]').val();

			SELF.$form.find('.alert-box').remove();
			SELF.$form.find('.spinner').show();

			wp.ajax.send( action, {
				data : SELF.data,
				success : SELF.response,
				error : SELF.error
			});
		};

		SELF.response = function (status) {
			SELF.$form.find('.spinner').hide();
			SELF.$form.prepend('<p class="alert-box success success-message">Success! Taking you to your profile.</p>');
			window.location = '/profile/';
		};

		SELF.error = function ( message ) {
			SELF.$form.find('.spinner').hide();
			SELF.$form.prepend( '<div class="alert-box alert" data-alert>' + message + '</div>');
			console.log( message );
		};

	};

	//var lwkAjaxRegister = new ajaxRegister();
	//lwkAjaxRegister.init('#start-now');

} )( jQuery, this );
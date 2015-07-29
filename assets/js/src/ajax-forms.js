(function($) {
	'use strict';

	var scAjaxForm = function($form) {
		var SELF = this;

		SELF.init = function() {
			SELF.$form = $form;

			if ( ! SELF.$form.length ) {
				return;
			}

			SELF.$form.on('submit', SELF.handleSubmission);
		};

		SELF.handleSubmission = function(e) {
			e.preventDefault();

			SELF.data = {
				action: 'sc_ajax_form',
				formdata: SELF.$form.serialize()
			};

			wp.ajax.send( 'sc_ajax_form', {
				success: SELF.response,
				error  : SELF.error,
				data   : SELF.data
			} );

		};

		SELF.response = function(data) {
			SELF.$form.find('.status-message').remove();
			SELF.$form.prepend('<p class="success-message">' + data.message + '</p>');

			if (data.url) {
				window.location = data.url;
			}

		};

		SELF.error = function ( data ) {
			SELF.$form.find('.spinner').hide();
			SELF.$form.find('.alert-box').remove();
			SELF.$form.prepend( '<div class="alert-box alert" data-alert>' + data.message + '</div>');
			console.log( data );
		};

		SELF.init();
	};

	$(document).ready( function(){
		$('.ajax-form').each(function(){
			new scAjaxForm($(this));
		});
	});

})(jQuery);
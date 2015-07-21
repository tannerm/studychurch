(function($) {
	'use strict';

	var scAjaxForm = function($container) {
		var SELF = this;

		SELF.init = function() {
			SELF.$container = $container;
			SELF.$form      = SELF.$container.find('form');

			if ( ! SELF.$form.length ) {
				return;
			}

			SELF.$form.on('submit', SELF.handleSubmission);
		};

		SELF.handleSubmission = function(e) {
			e.preventDefault();

			SELF.data = {
				action: SELF.$container.data('action'),
				formdata: SELF.$form.serialize()
			};

			wp.ajax.send( SELF.$container.data('action'), {
				success: SELF.response,
				data:    SELF.data
			} );

		};

		SELF.response = function(data) {
			SELF.$form.find('.status-message').remove();
			SELF.$form.prepend('<p class="success-message">' + data.message + '</p>');
			window.location = data.url;
		};

		SELF.init();
	};

	$(document).ready( function(){
		$('.ajax-form').each(function(){
			new scAjaxForm($(this));
		});
	});

})(jQuery);
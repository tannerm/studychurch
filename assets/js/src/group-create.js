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
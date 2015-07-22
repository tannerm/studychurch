var StudyApp = StudyApp || {};

(function ($) {
	'use strict';

	var MainView = Backbone.View.extend({

		el: StudyApp.$container,

		events: {
			"click #new-chapter": "createNewChapter"
		},

		initialize: function () {
		}

	});

	StudyApp.Views.Main = new MainView;

})(jQuery);
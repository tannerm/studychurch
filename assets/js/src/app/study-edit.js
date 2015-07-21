var StudyApp = StudyApp || {};

(function ($) {
	'use strict';

	var AppView = Backbone.View.extend({

		el: StudyApp.$container,

		events: {
			"click #new-chapter": "createNewChapter"
			//"click #chapter-list li a" : "editChapter"
		},

		initialize: function () {

			this.input = this.$("#new-chapter");

			this.listenTo(StudyApp.Chapters, 'add', this.addChapter);
			this.listenToOnce(StudyApp.Chapters, 'add', this.initializeChapterEdit);
			this.listenTo(StudyApp.ChapterSingle, 'add', this.setupChapter);

			StudyApp.Chapters.fetch();

		},

		addChapter: function (chapter) {
			var sidebarView = new StudyApp.ChapterViewSidebar({model: chapter});

			this.$("#chapter-list").append(sidebarView.render().el);
		},

		initializeChapterEdit: function(chapter) {
			StudyApp.CurrentChapter = new StudyApp.ChapterView({model: chapter});

			StudyApp.$content.html(StudyApp.CurrentChapter.render().el);
			StudyApp.$content.fadeIn();
		},

		setupChapter: function (chapter) {
			StudyApp.CurrentChapter.model.set(chapter);
		},

		createNewChapter: function (e) {
			this.listenTo(StudyApp.Chapters, 'add', this.setupChapter);

			StudyApp.Chapters.create();

			return false;
		},

		editChapter: function(e) {
			var $link     = $(e.target);
			var chapterID = $link.data('chapter');

			this.$('#chapter-list a').removeClass('current');
			$link.addClass('current');

			StudyApp.ChapterSingle.url = StudyApp.Chapters.url() + '/' + chapterID;
			StudyApp.ChapterSingle.fetch();

			return false;
		}

	});

	StudyApp.AppView = new AppView;

})(jQuery);
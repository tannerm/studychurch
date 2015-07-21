var StudyApp = StudyApp || {};

(function ($) {
	'use strict';

	StudyApp.Chapter = Backbone.Model.extend({

		urlRoot: function () {
			return this.collection.url()
		},

		defaults: function () {
			return {
				ID            : null,
				title         : 'New Chapter',
				status        : 'publish',
				type          : 'sc_study',
				author        : new wp.api.models.User(),
				content       : '',
				parent        : StudyApp.study_id,
				link          : '',
				date          : new Date(),
				modified      : new Date(),
				date_gmt      : new Date(),
				modified_gmt  : new Date(),
				date_tz       : 'Etc/UTC',
				modified_tz   : 'Etc/UTC',
				format        : 'standard',
				slug          : '',
				guid          : '',
				excerpt       : '&nbsp;',
				menu_order    : StudyApp.Chapters.nextOrder(),
				comment_status: 'closed',
				ping_status   : 'open',
				sticky        : false,
				password      : '',
				meta          : {
					links: {}
				},
				featured_image: null,
				terms         : [],
				order         : StudyApp.Chapters.nextOrder()
			}
		},

		sync  : function (method, model, options) {
			options = options || {};

			if (typeof WP_API_Settings.nonce !== 'undefined') {
				var beforeSend = options.beforeSend;

				options.beforeSend = function (xhr) {
					xhr.setRequestHeader('X-WP-Nonce', WP_API_Settings.nonce);

					if (beforeSend) {
						return beforeSend.apply(this, arguments);
					}
				};
			}

			return Backbone.sync(method, model, options);
		},

		toggle: function () {
			this.save({done: !this.get("done")});
		}

	});

	var ChapterList = Backbone.Collection.extend({

		order: 0,

		model: StudyApp.Chapter,

		url: function () {
			return WP_API_Settings.root + '/study/' + StudyApp.study_id + '/chapters'
		},

		parse: function (response) {
			//this.length = response.length;
			return response;
		},

		nextOrder: function () {
			if (!this.length) {
				this.order++;
			} else {
				this.order = this.last().get('order') + 1;
			}

			return this.order;
		},

		comparator: 'order'

	});

	StudyApp.Chapters = new ChapterList;
	StudyApp.ChapterSingle = new ChapterList;


	StudyApp.ChapterViewSidebar = Backbone.View.extend({

		tagName: "li",

		className: function () {
			return "page_item page-item_" + this.model.attributes.id
		},

		template: wp.template('chapter-sidebar-template'),

		events: {
			"click a" : "setupSingle"
		},

		initialize: function () {
			this.listenTo(this.model, 'sync', this.render);
		},

		render: function () {
			this.$el.html(this.template(this.model.toJSON()));
			return this;
		},

		setupSingle: function() {
			StudyApp.CurrentChapter.remove();
			StudyApp.CurrentChapter = new StudyApp.ChapterView({model: this.model});

			StudyApp.$content.html(StudyApp.CurrentChapter.render().el);
			StudyApp.$content.fadeIn();
			return false;
		}

	});

	StudyApp.ChapterView = Backbone.View.extend({

		tagName: "div",

		className: "chapter",

		template: wp.template('chapter-template'),

		events: {
			"blur .chapter-title"      : 'saveTitle',
			"click .chapter-title-edit": 'editTitle'
		},

		editTitle: function (e) {
			this.$el.find('.chapter-title')
				.addClass('editing')
				.find('input').focus();
			return false;
		},

		saveTitle: function (e) {
			this.$el.find('.chapter-title').removeClass('editing');
			var value = $(e.target).val();

			if (!value) {
				return;
			}

			this.model.url = WP_API_Settings.root + '/wp/v2/study/' + this.model.attributes.id;
			this.model.save({title: value});
		},

		initialize: function () {
			this.listenTo(this.model, 'sync', this.render);
		},

		render: function () {
			this.$el.html(this.template(this.model.toJSON()));
			return this;
		}

	});

})
(jQuery);
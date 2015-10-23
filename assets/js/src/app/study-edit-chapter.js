var StudyApp = StudyApp || {};

(function ($) {
	'use strict';

	StudyApp.Collections.Chapter = {};
	StudyApp.Views.Chapter = {};

	StudyApp.Models.Chapter = Backbone.Model.extend({

		urlRoot: function () {
			if ( this.collection ) {
				return this.collection.url()
			} else {
				return WP_API_Settings.root + 'study/' + StudyApp.study_id + '/chapters/'
			}
		},

		url : function() {
			return (this.get('id')) ? this.urlRoot() + this.get('id') : this.urlRoot();
		},

		defaults: function () {
			return {
				ID            : null,
				id            : null,
				title         : {
					rendered: ''
				},
				status        : 'publish',
				type          : 'sc_study',
				author        : StudyApp.user_id,
				parent        : StudyApp.study_id,
				menu_order    : StudyApp.Collections.Chapter.Sidebar.nextOrder(),
				comment_status: 'closed',
				ping_status   : 'closed',
				order         : StudyApp.Collections.Chapter.Sidebar.nextOrder(),
				elements      : {},
				sections      : {}
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
		}

	});

	var Collection = Backbone.Collection.extend({

		order: 0,

		model: StudyApp.Models.Chapter,

		url: function () {
			return WP_API_Settings.root + 'study/' + StudyApp.study_id + '/chapters/'
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

	StudyApp.Collections.Chapter.Sidebar = new Collection;
	StudyApp.Collections.Chapter.Single = new Collection;

	StudyApp.Views.Chapter.Sidebar = Backbone.View.extend({

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
			StudyApp.CurrentChapter = new StudyApp.Views.Chapter.Single({model: this.model});

			StudyApp.$content.html(StudyApp.CurrentChapter.render().el);
			StudyApp.Views.Chapter.Main.trigger('renderChapter', StudyApp.CurrentChapter.model);
			return false;
		}

	});

	StudyApp.Views.Chapter.Single = Backbone.View.extend({

		tagName: "div",

		className: "chapter",

		template: wp.template('chapter-template'),

		events: {
			"blur .chapter-title"      : 'saveTitle',
			"click .chapter-delete"    : 'deleteChapter'
		},

		initialize : function() {
			this.listenTo(this.model, 'destroy', this.remove);
		},

		saveTitle: function (e) {
			this.$el.find('.chapter-title').removeClass('editing');
			var value = $(e.target).val();

			if (!value) {
				return;
			}

			this.model.save({title: value});

		},

		deleteChapter : function() {
			if (!window.confirm('Are you sure you want to delete this chapter and all its items?')) {
				return;
			}

			this.model.$el.remove();
			this.model.destroy();
			return false;
		},

		render: function () {
			StudyApp.chapter_id = this.model.get('id');
			this.$el.html(this.template(this.model.toJSON()));
			return this;
		}
	});

	var MainView = Backbone.View.extend({

		el: StudyApp.$container,

		events: {
			"click #new-chapter": "createNewChapter"
		},

		initialize: function () {

			if (! StudyApp.$container.length) {
				return;
			}

			// listen to chapters and add
			this.listenTo(StudyApp.Collections.Chapter.Sidebar, 'add', this.addSidebarChapter);

			// The first chapter is being edited by default
			this.listenToOnce(StudyApp.Collections.Chapter.Sidebar, 'add', this.initializeChapterEdit);

			StudyApp.Collections.Chapter.Sidebar.fetch();

		},

		addSidebarChapter: function (chapter) {
			var sidebarView = new StudyApp.Views.Chapter.Sidebar({model: chapter});
			chapter.$el = sidebarView.$el;
			this.$("#chapter-list").append(sidebarView.render().el);
			this.$('#chapter-list').sortable({
					update : this.sortChapters
			});
		},

		sortChapters: function() {
			StudyApp.Collections.Chapter.Sidebar.each(function(item){
				if (item.get('menu_order') != item.$el.index()) {
					item.save({menu_order: item.$el.index()});
				}
			});
		},

		initializeChapterEdit: function(chapter) {
			StudyApp.CurrentChapter = new StudyApp.Views.Chapter.Single({model: chapter});
			StudyApp.$content.html(StudyApp.CurrentChapter.render().el);
			StudyApp.Views.Chapter.Main.trigger('renderChapter', StudyApp.CurrentChapter.model);
		},

		createNewChapter: function (e) {
			this.listenToOnce(StudyApp.Collections.Chapter.Sidebar, 'add', this.initializeChapterEdit);
			StudyApp.Collections.Chapter.Sidebar.add(new StudyApp.Models.Chapter);

			return false;
		}

	});

	StudyApp.Views.Chapter.Main = new MainView;

})
(jQuery);
var StudyApp = StudyApp || {};

(function ($) {
	'use strict';

	StudyApp.Collections.Item = {};
	StudyApp.Views.Item = {};

	StudyApp.Models.Item = Backbone.Model.extend({

		urlRoot: function () {
			return this.collection.url()
		},

		url : function() {
			return (this.attributes.id) ? this.urlRoot() + this.get('id') : this.url = this.urlRoot();
		},

		defaults: function () {
			return {
				ID            : null,
				id            : null,
				title         : {
					rendered: ''
				},
				content       : {
					rendered: ''
				},
				status        : 'publish',
				type          : 'sc_study',
				author        : StudyApp.user_id,
				parent        : StudyApp.chapter_id,
				menu_order    : StudyApp.Collections.Chapter.Sidebar.nextOrder(),
				comment_status: 'open',
				ping_status   : 'closed',
				data_type     : 'question_short',
				order         : StudyApp.Collections.Chapter.Sidebar.nextOrder()
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

		model: StudyApp.Models.Item,

		url: function () {
			return WP_API_Settings.root + '/study/' + StudyApp.study_id + '/chapters/' + StudyApp.chapter_id + '/items/';
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

	StudyApp.Collections.Item = new Collection;

	StudyApp.Views.Item.List = Backbone.View.extend({

		tagName: "div",

		template: wp.template('item-template'),

		events: {
			"click .item-compress"       : "compress",
			"click .item-expand"         : "expand",
			"click .item-content-edit"   : "editContent",
			"click .item-content-delete" : "deleteItem",
			"change .item-data-type"     : "setDataType"
		},

		initialize : function() {
			this.listenTo(this.model, 'destroy', this.handleDestroy);
		},

		render: function () {
			this.$el.html(this.template(this.model.toJSON()));
			return this;
		},

		compress : function(e) {
			this.$el.find('.panel').addClass('compressed');
			return false;
		},

		expand : function() {
			this.$el.find('.panel').removeClass('compressed');
			return false;
		},

		editContent : function(e) {
			var $content = this.$el.find('.item-content');
			var $buttons = this.$el.find('.panel-buttons');

			$buttons.hide();

			$content.editable({
				autosave : true,
				autosaveInterval : 2500,
				inlineMode : false,
				minHeight: 100,
				maxHeight: 400,
				buttons: ['bold', 'italic', 'sep', 'indent', 'outdent',
					'insertOrderedList', 'insertUnorderedList', 'sep',
					'createLink', 'insertImage', 'fullscreen', 'close'],
				customButtons : {
					// Clear HTML button with text icon.
					close: {
						title: 'Close Editor',
						icon: {
							type: 'font',
							value: 'fa fa-save'
						},
						callback: function () {
							this.save();
							this.destroy();
							$buttons.show();
						}
					}
				}
			});

			$content.on('editable.beforeSave', this, this.autosave);

			return false;
		},

		setDataType : function(e) {
			this.setsave({data_type : $(e.target).val()});
			return false;
		},

		autosave : function(e, editor) {
			var view = e.data;
			var content = view.model.get('content');

			content.raw = editor.getHTML();

			view.setsave({content : content});
			return false;
		},

		setsave : function(data, fetchChapter) {
			this.model.save(data);

			// refetch the current chapter?
			if (false !== fetchChapter) {
				StudyApp.CurrentChapter.model.fetch();
			}
		},

		deleteItem : function() {
			if (!window.confirm('Are you sure you want to delete this item?')) {
				return;
			}

			this.model.destroy();
			return false;
		},

		handleDestroy : function() {
			this.remove();
			StudyApp.CurrentChapter.model.fetch();
		}

	});

	var MainView = Backbone.View.extend({

		el: StudyApp.$container,

		events: {
			"click #new-item": "createNewItem"
		},

		initialize: function () {
			// listen to chapters and add
			this.listenTo(StudyApp.Collections.Item, 'add', this.addItem);

			// listen for setup of chapter and setup items
			this.listenTo(StudyApp.Views.Chapter.Main, 'renderChapter', this.setupItems);
		},

		setupItems: function(chapter) {
			StudyApp.Collections.Item.reset();

			var elements = chapter.get('elements');

			if ($.isEmptyObject(elements)) {
				return;
			}

			StudyApp.Collections.Item.add(chapter.get('elements'));
			StudyApp.Collections.Item.url = function() { return chapter.urlRoot() + chapter.id + '/items/'; }
		},

		addItem: function (item) {
			var view = new StudyApp.Views.Item.List({model: item});
			this.$("#chapter-items").append(view.render().el);

			if (item.get('content') && !item.get('content').rendered) {
				view.editContent();
			}
		},

		createNewItem: function (e) {
			StudyApp.Collections.Item.add(new StudyApp.Models.Item);
			return false;
		}

	});

	StudyApp.Views.Item.Main = new MainView;


})(jQuery);
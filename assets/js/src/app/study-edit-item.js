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
			return (this.attributes.id) ? this.urlRoot() + this.get('id') : this.urlRoot();
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
				parent        : StudyApp.CurrentChapter.model.get('id'),
				menu_order    : StudyApp.Collections.Item.nextOrder(),
				comment_status: 'open',
				ping_status   : 'closed',
				data_type     : 'question_short',
				is_private    : false
			}
		},

		sync  : function (method, model, options) {
			options = options || {};



			if (typeof wpApiSettings.nonce !== 'undefined') {
				var beforeSend = options.beforeSend;

				options.beforeSend = function (xhr) {
					xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);

					if (beforeSend) {
						return beforeSend.apply(this, arguments);
					}
				};
			}

			return Backbone.sync(method, model, options);
		}

	});

	var Collection = Backbone.Collection.extend({

		menu_order: 0,

		model: StudyApp.Models.Item,

		url: function () {
			return wpApiSettings.root + 'study/' + StudyApp.study_id + '/chapters/' + StudyApp.CurrentChapter.model.get('id') + '/items/';
		},

		nextOrder: function () {
			if (!this.length) {
				this.menu_order++;
			} else {
				this.menu_order = this.last().get('menu_order') + 1;
			}

			return this.menu_order;
		},

		comparator: 'menu_order'

	});

	StudyApp.Collections.Item = new Collection;

	StudyApp.Views.Item.List = Backbone.View.extend({

		tagName: "li",

		className: function () {
			return "panel item-" + this.model.get('id') + " " + this.model.get('data_type')
		},

		template: wp.template('item-template'),

		events: {
			"click .item-compress"       : "compress",
			"click .item-expand"         : "expand",
			"click .item-content-edit"   : "editContent",
			"click .item-content-delete" : "deleteItem",
			"click .item-content-save"   : "saveItem",
			"click .item-privacy"        : "setPrivacy",
			"change .item-data-type"     : "setDataType"
		},

		initialize : function() {
			this.listenTo(this.model, 'destroy', this.handleDestroy);
			this.listenTo(this.model, 'save', this.handleDestroy);
		},

		render: function () {
			this.$el.html(this.template(this.model.toJSON()));
			return this;
		},

		compress : function(e) {
			this.$el.addClass('compressed');
			return false;
		},

		expand : function() {
			this.$el.removeClass('compressed');
			return false;
		},

		setPrivacy : function(e) {

			var $this = $(e.target);

			if (!$this.hasClass('item-privacy')) {
				$this = $this.parent();
			}

			if ('question_short' != this.model.get('data_type') && 'question_long' != this.model.get('data_type')) {
				return false;
			}

			if ( $this.hasClass('current')) {
				return false;
			}

			this.$el.find('.item-privacy').removeClass('current');

			$this.addClass('current');
			var value = ($this.hasClass('item-private'));
			this.model.set({is_private : value});

			// only save if we have an id
			if (this.model.get('id')){
				this.setsave();
			}

			return false;
		},

		setDataType : function(e) {
			var dataType = $(e.target).val();
			this.$el.removeClass(this.model.get('data_type')).addClass(dataType);

			this.model.set({data_type : dataType});

			// only save if we have an id
			if (this.model.get('id')){
				this.setsave();
			}

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
				buttons: ['bold', 'italic', 'underline', 'strikeThrough', 'fontSize', 'fontFamily', 'color', 'sep',
					'formatBlock', 'blockStyle', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'sep',
					'createLink', 'insertLink', 'insertImage', 'insertVideo', 'html', 'fullscreen', 'sep', 'close'
				],
				customButtons : {
					// Clear HTML button with text icon.
					close: {
						title: 'Close Editor',
						icon: {
							type: 'font',
							value: 'fa fa-close'
						},
						callback: function () {
							this.save();
							this.destroy();
							$buttons.show();
						}
					}
				},
				imageUploadToS3: scFroalaS3,
				imageMaxSize: 1024 * 1024 * 1
			});

			$content.on('editable.beforeSave', this, this.autosave);

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
			var $content = this.$el.find('.item-content');

			// update the content if the editor is enabled
			if ($content.data('fa.editable')) {
				var content = this.model.get('content');
				content.raw = $content.editable('getHTML');
				this.model.set({content : content});
			}

			if (! this.model.get('parent')){
				this.model.set('parent', StudyApp.CurrentChapter.model.get('id'));
			}

			this.saving();
			this.model.save(data, {success: this.saveSuccess, error: this.saveFail});

			// refetch the current chapter?
			if (false !== fetchChapter) {
				StudyApp.CurrentChapter.model.fetch();
			}
		},

		saving : function() {
			var $saveIcon = this.$el.find( '.item-content-save' );
			$saveIcon.addClass('saving');
		},

		saveSuccess : function(model, response, options) {
			var $saveIcon = model.$el.find( '.item-content-save' );
			$saveIcon.removeClass('saving');
		},

		saveFail : function(model, response, options) {
			var $saveIcon = model.$el.find( '.item-content-save' );
			$saveIcon.removeClass('saving');
			alert('Saving failed... please refresh the page and try again.');
			console.log(response);
		},

		saveItem : function(e) {
			e.preventDefault();
			if (this.$el.find( '.item-content-save').hasClass('saving')) {
				return false;
			}

			this.setsave();
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
			StudyApp.Collections.Item.url = function() { return chapter.urlRoot() + chapter.id + '/items/'; };

			this.$el.find('#chapter-items').sortable({
				handle : '.item-reorder',
				update : this.sortItems
			});

		},

		sortItems: function() {
			StudyApp.Collections.Item.each(function(item){
				if (item.get('menu_order') != item.$el.index()) {
					item.save({menu_order: item.$el.index()});
				}
			});

			StudyApp.CurrentChapter.model.fetch();
		},

		addItem: function (item) {
			var view = new StudyApp.Views.Item.List({model: item});
			item.$el = view.$el;

			this.$("#chapter-items").append(view.render().el);

			if (item.get('content') && !item.get('content').rendered) {
				view.editContent();
			} else {
				view.compress();
			}
		},

		createNewItem: function (e) {
			StudyApp.Collections.Item.add(new StudyApp.Models.Item);
			return false;
		}

	});

	StudyApp.Views.Item.Main = new MainView;


})(jQuery);
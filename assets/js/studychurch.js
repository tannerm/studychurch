/*! StudyChurch - v0.1.0 - 2015-07-22
 * http://wordpress.org/themes
 * Copyright (c) 2015; * Licensed GPLv2+ */
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
( function( $, window ) {
	'use strict';

	var ajaxLogin = function () {
		var SELF = this;

		SELF.data = {};

		SELF.init = function (id) {
			SELF.$loginContainer = $(id);

			if (!SELF.$loginContainer.length) {
				return;
			}

			SELF.$form = SELF.$loginContainer.find('form');

			SELF.$form.on('submit', SELF.handleSubmission);
		};

		SELF.handleSubmission = function (e) {
			var action = SELF.$form.attr('data-action');

			if ( ! action ) {
				return;
			}

			e.preventDefault();

			SELF.data['log']        = SELF.$form.find('input[name="sc-login"]').val();
			SELF.data['pwd']        = SELF.$form.find('input[name="sc-password"]').val();
			SELF.data['security']   = SELF.$form.find('input[name="sc_login_key"]').val();
			SELF.data['rememberme'] = true;

			SELF.$form.find('.alert-box').remove();
			SELF.$form.find('.spinner').show();

			wp.ajax.send( action, {
				data : SELF.data,
				success : SELF.response,
				error : SELF.error
			});
		};

		SELF.response = function (data) {
			SELF.$form.find('.spinner').hide();
			SELF.$form.prepend('<p class="alert-box success success-message">Success! Taking you to your profile.</p>');
			window.location = '/profile/';
		};

		SELF.error = function( message ) {
			if ( ! message ) {
				message = "Please make sure that you have filled in both your email and password."
			}
			SELF.$form.find('.spinner').hide();
			SELF.$form.prepend( '<div class="alert-box alert" data-alert>' + message + '</div>');
		}

	};

	var scAjaxLogin = new ajaxLogin();
	scAjaxLogin.init('#login');

} )( jQuery, this );
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

	var lwkAjaxRegister = new ajaxRegister();
	lwkAjaxRegister.init('#start-now');

} )( jQuery, this );
jQuery(document).ready(function($){
	'use strict';

	var answers = function( $form ) {
		var SELF = this;

		SELF.$form = $form;
		SELF.data  = {};

		SELF.init = function() {
			SELF.$answer = SELF.$form.find('textarea');
			SELF.$save = SELF.$form.find('.sudo-save');

			SELF.$save.on( 'click', SELF.triggerSubmission );
			SELF.$answer.on('keyup', SELF.autoSave );
			SELF.$form.on('submit', SELF.handleSubmission);
		};

		SELF.autoSave = function() {
			console.log( 'keyup' );
		};

		SELF.triggerSubmission = function(e) {
			e.preventDefault;

			SELF.$form.submit();
			SELF.$save.text('Saving');
			return false;
		};

		SELF.handleSubmission = function(e){
			e.preventDefault();

			SELF.data['answer']     = SELF.$form.find('textarea[name=comment]').val();
			SELF.data['post_id']    = SELF.$form.find('input[name=comment_post_ID]').val();
			SELF.data['comment_id'] = SELF.$form.find('input[name=comment_ID]').val();
			SELF.data['group_id'] = SELF.$form.find('input[name=group_ID]').val();

			wp.ajax.send( 'sc_save_answer', {
				success: SELF.success,
				error: SELF.error,
				data:    SELF.data
			} );

		};

		SELF.success = function(data) {
			SELF.$save.text('Save');
			SELF.$form.find('input[name=comment_ID]').val(data.comment_ID);
		};

		SELF.error = function(message) {
			console.log('error');
			console.log(message);
		};

		SELF.init();

	};

	$(document).ready(function(){
		$('.sc_study .comment-form').each(function(){
			new answers( $(this) );
		});
	});

});
(function ($, window, undefined) {
	'use strict';

	$(document).foundation();

	$(document).ready(function () {
		 $(document.getElementById('single-page-navigation')).singlePageNav();

		//$(document.getElementById('single-page-navigation')).onePageNav({
		//	currentClass: 'current',
		//	changeHash  : true,
		//	scrollSpeed : 750
		//});

		if ($('.study-group .content-container').outerHeight() < window.innerHeight) {
			$('.study-group').height(window.innerHeight - $('.top-bar-container').outerHeight() + 'px');
		}

		$('.study-group #input_1_1').on('focus', function() {
			$(document).foundation();
			$('#field_1_2').show();
		})
	})


})(jQuery, this);
var StudyApp = StudyApp || {};

StudyApp.Models      = {};
StudyApp.Views       = {};
StudyApp.Collections = {};

StudyApp.$container = jQuery('#studyapp');
StudyApp.$content   = jQuery('#studyapp-content');
StudyApp.study_id   = StudyApp.$container.data('study');
StudyApp.user_id    = StudyApp.$container.data('user');
StudyApp.chapter_id = null;
var StudyApp = StudyApp || {};

(function ($) {
	'use strict';

	StudyApp.Collections.Chapter = {};
	StudyApp.Views.Chapter = {};

	StudyApp.Models.Chapter = Backbone.Model.extend({

		urlRoot: function () {
			return this.collection.url()
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
			"blur .chapter-title"      : 'saveTitle'
		},

		initialize: function () {
			this.listenTo(this.model, 'sync', this.render);
		},

		saveTitle: function (e) {
			this.$el.find('.chapter-title').removeClass('editing');
			var value = $(e.target).val();

			if (!value) {
				return;
			}

			if ( this.model.attributes.id ) {
				this.model.url = WP_API_Settings.root + '/wp/v2/study/' + this.model.attributes.id;
			} else {
				this.model.url = WP_API_Settings.root + '/wp/v2/study/';
			}

			this.model.save({title: value});

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

			// listen to chapters and add
			this.listenTo(StudyApp.Collections.Chapter.Sidebar, 'add', this.addSidebarChapter);

			// The first chapter is being edited by default
			this.listenToOnce(StudyApp.Collections.Chapter.Sidebar, 'add', this.initializeChapterEdit);

			StudyApp.Collections.Chapter.Sidebar.fetch();

		},

		addSidebarChapter: function (chapter) {
			var sidebarView = new StudyApp.Views.Chapter.Sidebar({model: chapter});
			this.$("#chapter-list").append(sidebarView.render().el);
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
var StudyApp = StudyApp || {};

(function ($) {
	'use strict';

	StudyApp.Collections.Item = {};
	StudyApp.Views.Item = {};

	StudyApp.Models.Item = Backbone.Model.extend({

		urlRoot: function () {
			return this.collection.url()
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
				parent        : StudyApp.study_id,
				menu_order    : StudyApp.Collections.Chapter.Sidebar.nextOrder(),
				comment_status: 'closed',
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
			return WP_API_Settings.root + '/study/' + StudyApp.study_id + '/chapters/' + StudyApp.chapter_id;
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
			"click .item-content-edit" : "editContent",
		},

		render: function () {
			this.$el.html(this.template(this.model.toJSON()));
			return this;
		},

		editContent : function(e) {
			var $content = this.$el.find('.item-content');
			var $buttons = this.$el.find('.panel-buttons');

			$buttons.hide();

			$content.editable({
				autosave : true,
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
							value: 'fa fa-compress'
						},
						callback: function () {
							this.destroy();
							$buttons.show();
						}
					}
				}
			});

			$content.on('editable.beforeSave', this.autosave);

			return false;
		},

		autosave : function(e, editor) {
			return false;
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
			StudyApp.Collections.Item.add(chapter.get('elements'));
			StudyApp.Collections.Item.url = function() { return chapter.urlRoot() + '/' + chapter.id + '/items'; }
		},

		addItem: function (item) {
			var view = new StudyApp.Views.Item.List({model: item});
			this.$("#chapter-items").append(view.render().el);
		},

		createNewItem: function (e) {
			StudyApp.Collections.Item.add(new StudyApp.Models.Item);
			return false;
		}

	});

	StudyApp.Views.Item.Main = new MainView;


})(jQuery);
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
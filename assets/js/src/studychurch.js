/**
 * StudyChurch
 * http://wordpress.org/themes
 *
 * Copyright (c) 2014 Tanner Moushey
 * Licensed under the GPLv2+ license.
 */

(function ($, window, undefined) {
	'use strict';

	window.sr = new scrollReveal();
	$(document).foundation();

	$(document).ready(function () {
		 $(document.getElementById('single-page-navigation')).singlePageNav();

		$('.login a').attr('data-reveal-id', 'login');
		$('.sign-up a').attr('data-reveal-id', 'start-now').addClass('button tiny round secondary');

		$('.fdatepicker').fdatepicker({
			format: 'mm/dd/yyyy',
			disableDblClickSelection: true
		});

		$('.froala-min').editable({
			inlineMode : false,
			minHeight: 100,
			maxHeight: 400,
			buttons: ['bold', 'italic', 'underline', 'createLink' ]
		});

		$('.froala-default').editable({
			inlineMode : false,
			minHeight: 100,
			maxHeight: 400,
			buttons: ['bold', 'italic', 'underline', 'strikeThrough', 'sep',
				'formatBlock', 'blockStyle', 'align', 'insertOrderedList', 'insertUnorderedList', 'outdent', 'indent', 'sep',
				'createLink', 'html', 'fullscreen'
			]
		});

		$('.froala-inline').editable({
			minHeight: 100,
			buttons: ['bold', 'italic', 'underline', 'strikeThrough', 'sep',
				'formatBlock', 'blockStyle', 'align', 'insertOrderedList', 'insertUnorderedList', 'outdent', 'indent', 'sep',
				'createLink'
			]
		});


		$('.footer-subscribe #input_1_1').on('focus', function() {
			$(document).foundation();
			$('#field_1_2').show();
		});

		$('.group-invite-link').on('click', function() {
			$(this).select();
		});

		$('.bug-report-button').on('click', function() {
			$(this).parents('.bug-report-cont').find('.form').slideToggle();
			return false;
		});

		var $restrictedContainer = $(document.getElementById('restricted-message'));
		if ($restrictedContainer.length) {
			var $loginContainer = $restrictedContainer.find('#login-body');
			var $registerContainer = $restrictedContainer.find('#start-now-body');

			$loginContainer.find('.switch').on('click', function(e){
				$loginContainer.fadeOut(function(){
					$registerContainer.fadeIn();
				});
				return false;
			});

			$registerContainer.find('.switch').on('click', function(e){
				$registerContainer.fadeOut(function(){
					$loginContainer.fadeIn();
				});
				return false;
			});
		}

	})


})(jQuery, this);
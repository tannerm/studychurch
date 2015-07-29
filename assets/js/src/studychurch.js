/**
 * StudyChurch
 * http://wordpress.org/themes
 *
 * Copyright (c) 2014 Tanner Moushey
 * Licensed under the GPLv2+ license.
 */

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

		//if ($('.study-group .content-container').outerHeight() < window.innerHeight) {
		//	$('.study-group').height(window.innerHeight - $('.top-bar-container').outerHeight() + 'px');
		//}

		$('.footer-subscribe #input_1_1').on('focus', function() {
			$(document).foundation();
			$('#field_1_2').show();
		});

		var $restrictedContainer = $(document.getElementById('restricted-message'));
		if ($restrictedContainer.length) {
			var $loginContainer = $restrictedContainer.find('#login');
			var $registerContainer = $restrictedContainer.find('#start-now');

			$loginContainer.find('.switch').on('click', function(e){
				$loginContainer.fadeOut(function(){
					$registerContainer.fadeIn();
				});
			});

			$registerContainer.find('.switch').on('click', function(e){
				$registerContainer.fadeOut(function(){
					$loginContainer.fadeIn();
				});
			});
		}

	})


})(jQuery, this);
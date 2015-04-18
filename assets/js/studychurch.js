/*! StudyChurch - v0.1.0 - 2015-04-17
 * http://wordpress.org/themes
 * Copyright (c) 2015; * Licensed GPLv2+ */
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
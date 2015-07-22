var StudyApp = StudyApp || {};

StudyApp.Models      = {};
StudyApp.Views       = {};
StudyApp.Collections = {};

StudyApp.$container = jQuery('#studyapp');
StudyApp.$content   = jQuery('#studyapp-content');
StudyApp.study_id   = StudyApp.$container.data('study');
StudyApp.user_id    = StudyApp.$container.data('user');
StudyApp.chapter_id = null;
var StudyApp = StudyApp || {};

StudyApp.$container = jQuery('#studyapp');
StudyApp.$content   = jQuery('#studyapp-content');
StudyApp.study_id   = StudyApp.$container.data('study');
StudyApp.user_id    = StudyApp.$container.data('user');
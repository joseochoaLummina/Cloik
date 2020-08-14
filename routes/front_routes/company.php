<?php

Route::get('company-home', 'Company\CompanyController@index')->name('company.home');
Route::get('company-home-profile', 'Company\CompanyController@fullProfileCompany')->name('company.home.profile');
Route::get('companies', 'Company\CompaniesController@company_listing')->name('company.listing');
Route::get('company-profile', 'Company\CompanyController@companyProfile')->name('company.profile');
Route::put('update-company-profile', 'Company\CompanyController@updateCompanyProfile')->name('update.company.profile');
Route::get('posted-jobs', 'Company\CompanyController@postedJobs')->name('posted.jobs');
Route::get('company/{slug}', 'Company\CompanyController@companyDetail')->name('company.detail');
Route::post('contact-company-message-send', 'Company\CompanyController@sendContactForm')->name('contact.company.message.send');
Route::post('contact-applicant-message-send', 'Company\CompanyController@sendApplicantContactForm')->name('contact.applicant.message.send');
Route::get('list-applied-users/{job_id}', 'Company\CompanyController@listAppliedUsers')->name('list.applied.users');
Route::get('list-favourite-applied-users/{job_id}', 'Company\CompanyController@listFavouriteAppliedUsers')->name('list.favourite.applied.users');
Route::get('add-to-favourite-applicant/{application_id}/{user_id}/{type}/{company_id}', 'Company\CompanyController@addToFavouriteApplicant')->name('add.to.favourite.applicant');
Route::get('remove-from-favourite-applicant/{application_id}/{user_id}/{type}/{company_id}', 'Company\CompanyController@removeFromFavouriteApplicant')->name('remove.from.favourite.applicant');
Route::get('applicant-profile/{application_id}', 'Company\CompanyController@applicantProfile')->name('applicant.profile');
Route::post('applicant-profile-comment/{application_id}/{from}', 'Company\CompanyController@newComment')->name('new.comment');
Route::post('show-comment-company', 'Company\CompanyController@showCommentCompany')->name('show.comment.company');
Route::post('delete-comment-company', 'Company\CompanyController@deleteCommentCompany')->name('delete.comment.company');
Route::get('user-profile/{id}', 'Company\CompanyController@userProfile')->name('user.profile');
Route::get('company-followers', 'Company\CompanyController@companyFollowers')->name('company.followers');
Route::get('company-messages', 'Company\CompanyController@companyMessages')->name('company.messages');
Route::get('company-message-detail/{id}', 'Company\CompanyController@companyMessageDetail')->name('company.message.detail');
Route::get('schedule-meeting/{user_id}/{job_apply_id}/{job_id}', 'Company\CompanyController@getScheduleMeeting')->name('get.schedule.meeting');
Route::post('save-meeting', 'Company\CompanyController@saveMeeting')->name('save.meeting');
Route::put('update-meeting', 'Company\CompanyController@updateMeeting')->name('update.meeting');
Route::delete('delete-meeting', 'Company\CompanyController@deleteMeeting')->name('delete.meeting');
Route::get('show-applicants-video/{video_apply}/{user_id}','Company\CompanyController@showApplicantsVideo')->name('show.applicants.video');
Route::get('company-blacklist','Company\CompanyController@showBlacklist')->name('company.blacklist');
Route::get('company-favourites','Company\CompanyController@showFavourites')->name('company.favourites');
Route::get('company-delete-from-favourites/{user_id}','Company\CompanyController@deleteFromFavourites')->name('company.delete.from.favourites');

// Route::get('add-black-list/{application_id}/{user_id}/{type}/{company_id}', 'Company\CompanyController@addToBlackList')->name('add.black.list');
Route::get('add-black-list', 'Company\CompanyController@addToBlackList')->name('add.black.list');
Route::get('remove-black-list/{application_id}/{user_id}/{type}/{company_id}', 'Company\CompanyController@deleteToBlackList')->name('remove.black.list');
Route::get('remove-from-blacklist/{user_id}/{company_id}', 'Company\CompanyController@deleteFromBlackList')->name('remove.from.blacklist');
Route::post('delete-profile-company', 'Company\CompanyController@deleteProfileCompany')->name('delete.profile.company');


//Reclutadores
Route::get('company-recruiters', 'Company\CompanyController@showRecruiters')->name('company.recruiters');
Route::get('send-invitation-recruiters', 'GeneralController@sendInvitationRecruiter')->name('send.invitation.recruiter');
Route::post('verify-exist-email', 'GeneralController@verifyExistEmail')->name('verify.exist.email');

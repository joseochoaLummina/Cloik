<?php

// Route::prefix('recruiter')->name('recruiter')->group(function () {
//     Route::get('/', 'Recruiter\Auth\LoginController@showLoginForm');
//     Route::get('/login', 'Recruiter\Auth\LoginController@showLoginForm')->name('login');
//     Route::post('/login', 'Recruiter\Auth\LoginController@login');
// });

Route::post('recruiter-login', 'Recruiter\Auth\LoginController@login')->name('recruiter.login');
Route::post('recruiter-logout', 'Recruiter\Auth\LoginController@logout')->name('recruiter.logout');
Route::get('recruiter-home', 'Recruiter\RecruiterController@viewRecruiterHome')->name('recruiter.home');
Route::get('recruiter-profile', 'Recruiter\RecruiterController@recruiterProfile')->name('recruiter.profile');
Route::put('update-recruiter-profile', 'Recruiter\RecruiterController@updateRecruiterProfile')->name('update.recruiter.profile');
Route::get('recruiter-company-profile', 'Recruiter\RecruiterController@companyProfile')->name('recruiter.company.profile');
Route::put('recruiter.update-company-profile', 'Recruiter\RecruiterController@updateCompanyProfile')->name('recruiter.update.company.profile');
Route::post('new-recruiter', 'GeneralController@newRecruiter')->name('recruiter.new');
Route::get('report-recruiter-invitation-error/{id}', 'GeneralController@recruiterInvitationError');
Route::get('invitations-recruiters/{id}', 'GeneralController@showRecruiterForm')->name('invitarions.recruiters');
Route::get('delete-recruiter/{id}', 'GeneralController@DeleteRecruiter')->name('delete.recruiter');
Route::get('recruiter-posted-jobs', 'Recruiter\RecruiterController@postedJobs')->name('recruiter.posted.jobs');
Route::get('recruiter-list-applied-users/{job_id}', 'Recruiter\RecruiterController@listAppliedUsers')->name('recruiter.list.applied.users');
Route::get('recruiter.applicant-profile/{application_id}', 'Recruiter\RecruiterController@applicantProfile')->name('recruiter.applicant.profile');
Route::get('recruiter-list-favourite-applied-users/{job_id}', 'Recruiter\RecruiterController@listFavouriteAppliedUsers')->name('recruiter.list.favourite.applied.users');
Route::get('recruiter-show-blacklist', 'Recruiter\RecruiterController@showBlacklist')->name('recruiter.show.blacklist');
Route::get('recruiter-company-followers', 'Recruiter\RecruiterController@companyFollowers')->name('recruiter.company.followers');
Route::get('recruiter-user-profile/{id}', 'Recruiter\RecruiterController@userProfile')->name('recruiter.user.profile');
Route::get('recruiter-add-black-list', 'Recruiter\RecruiterController@addToBlackList')->name('recruiter.add.black.list');
Route::get('recruiter-remove-black-list/{application_id}/{user_id}/{type}/{company_id}', 'Recruiter\RecruiterController@deleteToBlackList')->name('recruiter.remove.black.list');
Route::get('recruiter.remove-from-blacklist/{user_id}/{company_id}', 'Recruiter\RecruiterController@deleteFromBlackList')->name('recruiter.remove.from.blacklist');
Route::get('recruiter-add-to-favourite-applicant/{application_id}/{user_id}/{type}/{company_id}', 'Recruiter\RecruiterController@addToFavouriteApplicant')->name('recruiter.add.to.favourite.applicant');
Route::get('recruiter-remove-from-favourite-applicant/{application_id}/{user_id}/{type}/{company_id}', 'Recruiter\RecruiterController@removeFromFavouriteApplicant')->name('recruiter.remove.from.favourite.applicant');
Route::post('recruiter.contact-applicant-message-send', 'Recruiter\RecruiterController@sendApplicantContactForm')->name('recruiter.contact.applicant.message.send');
Route::get('recruiter-company-meetings', 'Recruiter\RecruiterController@getCompanyMeetings')->name('recruiter.company.meetings');
Route::get('recruiter-schedule-meeting/{user_id}/{job_apply_id}/{job_id}', 'Recruiter\RecruiterController@getScheduleMeeting')->name('recruiter.get.schedule.meeting');
Route::post('recruiter-save-meeting', 'Recruiter\RecruiterController@saveMeeting')->name('recruiter.save.meeting');
Route::put('recruiter-update-meeting', 'Recruiter\RecruiterController@updateMeeting')->name('recruiter.update.meeting');
Route::delete('recruiter-delete-meeting', 'Recruiter\RecruiterController@deleteMeeting')->name('recruiter.delete.meeting');
Route::post('recruiter-new-comment/{application_id}/{from}', 'Recruiter\RecruiterController@newComment')->name('recruiter.new.comment');
Route::post('recruiter-show-comment-company', 'Recruiter\RecruiterController@showCommentCompany')->name('recruiter.show.comment.company');
Route::post('recruiter-delete-comment-company', 'Recruiter\RecruiterController@deleteCommentCompany')->name('recruiter.delete.comment.company');
Route::get('recruiter-show-modal-jobs/{user_id}','Recruiter\RecruiterController@showModalJobs')->name('recruiter.show.modal.jobs');
Route::post('recruiter-recommend-candidate','Recruiter\RecruiterController@recommendCandidate')->name('recruiter.recommend.candidate');
Route::post('recruiter-change-meeting','Recruiter\RecruiterController@changeMeeting')->name('recruiter.change.meeting');
Route::get('recruiter-company-favourites','Recruiter\RecruiterController@showFavourites')->name('recruiter.company.favourites');
Route::get('recruiter-delete-from-favourites/{user_id}','Recruiter\RecruiterController@deleteFromFavourites')->name('recruiter.delete.from.favourites');

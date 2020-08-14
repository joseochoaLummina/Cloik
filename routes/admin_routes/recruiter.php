<?php

/* * ******  Recruiter Start ********** */
Route::get('list-recruiters', array_merge(['uses' => 'Admin\RecruiterController@indexRecruiters'], $all_users))->name('list.recruiters');
Route::get('new-recruiters', array_merge(['uses' => 'Admin\RecruiterController@newRecruiter'], $all_users))->name('new.recruiters');
Route::get('fetch-recruiters', array_merge(['uses' => 'Admin\RecruiterController@fetchRecruitersData'], $all_users))->name('fetch.data.recruiters');
Route::delete('delete-recruiter-admin', array_merge(['uses' => 'Admin\RecruiterController@deleteRecruiter'], $all_users))->name('delete.recruiter.admin');
Route::put('make-recruiter-master', array_merge(['uses' => 'Admin\RecruiterController@makeMaster'], $all_users))->name('make.recruiter.master');
Route::put('make-recruiter-jr', array_merge(['uses' => 'Admin\RecruiterController@makeJunior'], $all_users))->name('make.recruiter.jr');
Route::post('fetch-data-recruiters-companies', array_merge(['uses' => 'Admin\RecruiterController@fetchDataRecruitersCompanies'], $all_users))->name('fetch.data.recruiters.companies');
Route::post('admin-verify-exist-email', 'Admin\RecruiterController@verifyExistEmail')->name('admin.verify.exist.email');
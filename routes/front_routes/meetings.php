<?php

Route::get('user-meetings', 'MeetingController@getUserMeetings')->name('my.user.meetings');
Route::get('meetings-call', 'MeetingController@getMeetingCall')->name('meetings.call');
Route::post('generate-room', 'MeetingController@generateRoom')->name('generate.room');
Route::get('company-meetings', 'MeetingController@getCompanyMeetings')->name('company.meetings');
Route::get('video-call-room', 'MeetingController@getVideoCallRoom')->name('video.call.room');
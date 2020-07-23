<?php

Route::get('center-message', 'CenterMessagesController@getCenterMessages')->name('my.center.message');
Route::get('company-center-message', 'CenterMessagesController@getCompanyCenterMessages')->name('company.center.message');
Route::get('change-toread-message', 'CenterMessagesController@ChangeMessagesToRead')->name('change.to.read');
Route::get('change-message-type', 'CenterMessagesController@changeMessagesType')->name('change.messages.type');
Route::get('accept-meeting', 'CenterMessagesController@acceptMeeting')->name('accept.meeting');
Route::get('deny-meeting', 'CenterMessagesController@denyMeeting')->name('deny.meeting');
Route::get('delete-message', 'CenterMessagesController@deleteMessage')->name('delete.message');
Route::get('reply-message', 'CenterMessagesController@replyMessage')->name('reply.message');
Route::get('send-message-to-candidate', 'CenterMessagesController@sendMessageToCandidate')->name('send.message.to.candidate');


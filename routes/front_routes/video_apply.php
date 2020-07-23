<?php

Route::get('video-apply', 'videoApplyController@getVideoApplication')->name('my.videos.apply');
Route::get('postvideo', 'videoApplyController@newVideoApplication')->name('post.video.apply');
Route::get('delvideo', 'videoApplyController@deleteVideoApplication')->name('delete.video.apply');
Route::get('countvideo', 'videoApplyController@getCountVideo')->name('count.video.apply');
Route::get('delcompanyvideo', 'videoApplyController@deleteVideoApplication')->name('delete.company.video');
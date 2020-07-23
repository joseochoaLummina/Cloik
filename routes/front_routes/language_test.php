<?php

Route::get('language-test', 'LanguageTestController@getLanguageTest')->name('my.language.test');
Route::post('language-test', 'LanguageTestController@qualifyLanguageTest')->name('my.language.test');
Route::get('language-paragraph-test', 'LanguageTestController@getParagraph')->name('test.paragraph');
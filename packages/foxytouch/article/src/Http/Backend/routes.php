<?php

Route::group(['prefix' => 'auth', 'middleware' => 'auth', 'namespace' => 'Foxytouch\Article\Http\Backend\Controllers'], function() {

    Route::get('uncategorized', 'CategoryController@uncategorized')->name('auth.category.uncategorized');
    Route::resource('category', 'CategoryController');
});


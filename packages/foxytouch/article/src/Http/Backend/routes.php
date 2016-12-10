<?php

use Foxytouch\Article\Http\Backend\Controllers\ArticleController;
use Foxytouch\Article\Http\Backend\Controllers\CategoryController;

Route::group(['prefix' => 'auth'], function() {

    Route::resource('article', ArticleController::class);
    
    Route::get('uncategorized', 'CategoryController@uncategorized')->name('auth.category.uncategorized');
    Route::resource('category', CategoryController::class);
});

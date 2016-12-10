<?php

use Foxytouch\Page\Http\Backend\Controllers\PageController;

Route::group(['prefix' => 'auth'], function() {

    Route::resource('page', PageController::class);
});

<?php

use Foxytouch\User\Http\Backend\Controllers\RoleController;
use Foxytouch\User\Http\Backend\Controllers\PermissionController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| USER MODULE BACKEND ROUTES
|--------------------------------------------------------------------------
|
*/
Route::group(['prefix' => 'auth'], function() {
    Route::resource('role', RoleController::class);
    Route::resource('permission', PermissionController::class);
});

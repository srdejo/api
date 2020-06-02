<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('pre_registro', 'AuthController@registrar');

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('registrar', 'AuthController@registrar');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
        Route::get('users', 'AuthController@users'); // Debo ser admin... PENDIENTE
    });
});
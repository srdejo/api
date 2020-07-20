<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false],['login' => false]);

Route::get('/', 'HomeController@index')->name('inicio');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/login', 'Auth\LoginController@showAdminLoginForm')->name('login');
Route::get('/register', 'Auth\RegisterController@showAdminRegisterForm');

Route::post('/login', 'Auth\LoginController@adminLogin');
Route::post('/register', 'Auth\RegisterController@createAdmin');
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes(['register' => false], ['login' => false]);

Route::get('/', 'HomeController@index')->name('inicio');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/login', 'Auth\LoginController@showAdminLoginForm')->name('login');
Route::get('/register', 'Auth\RegisterController@showAdminRegisterForm')->name('register');

Route::post('/login', 'Auth\LoginController@adminLogin');
Route::post('/register', 'Auth\RegisterController@createAdmin');

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/negocio', 'NegocioController@index')->name('negocio');
    Route::get('/producto', 'ProductoController@index')->name('producto');
    Route::post('/producto', 'ProductoController@store')->name('guardar_producto');
    Route::get('/productos', 'ProductoController@json')->name('json_producto');
});
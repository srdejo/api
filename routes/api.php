<?php

use Illuminate\Support\Facades\Route;

Route::post('login', 'CodigoController@EnviarCodigo');
Route::post('pre_registro', 'AuthController@registrar');
Route::post('validar_codigo', 'CodigoController@ValidarCodigo');
Route::put('direccion', 'DireccionController@ActualizarDireccion')->middleware('auth:api');
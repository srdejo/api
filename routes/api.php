<?php

use Illuminate\Support\Facades\Route;

Route::post('login', 'CodigoController@EnviarCodigo');
Route::post('pre_registro', 'AuthController@registrar');
Route::post('validar_codigo', 'CodigoController@ValidarCodigo');
Route::put('direccion', 'DireccionController@ActualizarDireccion')->middleware('auth:api');
Route::get('categorias', 'CategoriaController@getAll')->middleware('auth:api');
Route::get('productos', 'ProductoController@getAll')->middleware('auth:api');
Route::get('productos_por_categoria', 'ProductoController@getByCategory')->middleware('auth:api');
Route::get('productos_en_oferta', 'ProductoController@getOferta')->middleware('auth:api');
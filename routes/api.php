<?php

use Illuminate\Support\Facades\Route;

Route::post('pre_registro', 'AuthController@registrar');
Route::post('validar_codigo', 'CodigoController@ValidarCodigo');
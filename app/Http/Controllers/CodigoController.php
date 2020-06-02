<?php

namespace App\Http\Controllers;

use App\Codigo;
use Illuminate\Http\Request;

class CodigoController extends Controller
{
    public function GenerarCodigo($celular)
    {
        try {
            $codigo = New Codigo();
            $codigo->codigo = rand(1000,9999);
            $codigo->celular = $celular;
            $codigo->save();

            return $codigo->id;
        } catch (\Throwable $th) {
            return 0;
        }
    }
}

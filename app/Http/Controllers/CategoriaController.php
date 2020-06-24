<?php

namespace App\Http\Controllers;

use App\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function getAll()
    {
        $categorias = Categoria::get();
        return response()->json([
            'data' => $categorias
        ], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Categoria;
use App\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function getAll()
    {
        return response()->json([
            'data' => Producto::get()
        ], 200);
    }

    public function getByCategory(Request $request)
    {
        $categoria = Categoria::find($request->categoria);

        if (isset($categoria)) {

            return response()->json([
                'data' => Producto::where('categoria_id', $categoria->id)->get()
            ], 200);
        } else {

            return response()->json([
                'message' => 'Categoria no valida',
                'data' => null
            ], 404);
        }
    }

    public function getOferta()
    {
        $hoy = date('Y-m-d');

        return response()->json([
            'data' => Producto::where('fecha_inicio_oferta', '<=', $hoy)->where('fecha_fin_oferta', '>=', $hoy)->whereNotNull('precio_oferta')->get()
        ], 200);
    }
}

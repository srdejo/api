<?php

namespace App\Http\Controllers;

use App\Categoria;
use App\Http\Resources\ProductoCollection;
use App\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        $request->validate([
            'categoria'     => 'required|int',
        ]);

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

    // Funciones admin

    public function index()
    {
        return view('productos.list');
    }

    public function json(Request $request)
    {
        $perPage = $request->perPage;
        $currentPage = $request->currentPage;
        $sortBy = $request->sortBy;
        $sortDesc = $request->sortDesc;
        $filter = $request->filter;

        if ($request->sortDesc == 'true') {
            $OrderDesc = 'desc';
        } else {
            $OrderDesc = 'asc';
        }
        $user = Auth::user();
        $negocio_id_user = $user->negocio_id;
        $productos = Producto::where('negocio_id', $negocio_id_user)->orderBy($sortBy, $OrderDesc)->paginate($perPage);
        return new ProductoCollection($productos);
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'nombre' => 'required',
            'imagen' => 'required',
            'precio' => 'required',
        ]);

        try {
            $user = Auth::user();
    
            $ubicacion = $request->imagen->store('public');
    
            $producto = New Producto();
            $producto->nombre = $request->nombre;
            $producto->imagen = $ubicacion;
            $producto->precio = $request->precio;
            $producto->precio_oferta = $request->precio_oferta;
            $producto->fecha_inicio_oferta = $request->fecha_inicio_oferta;
            $producto->fecha_fin_oferta = $request->fecha_fin_oferta;
            $producto->categoria_id = '2';
            $producto->negocio_id = $user->negocio_id;

            $producto->save();
            return response()->json(['success' => 'Almacenado']);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json(['error' => '']);
        }
    }
}

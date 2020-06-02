<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DireccionController extends Controller
{
    public function ActualizarDireccion(Request $request)
    {

        $request->validate([
            'nomenclatura'     => 'required|string',
            'numero'    => 'required|string',
            'placa' => 'required|string',
        ]);
        try {
            DB::beginTransaction();
            $direccion = Auth::user()->direccion;
            $direccion->nomenclatura = $request->nomenclatura;
            $direccion->numero = $request->numero;
            $direccion->placa = $request->placa;
            $direccion->barrio = $request->barrio;
            $direccion->save();
            DB::commit();
            return response()->json([
                'message' => 'Direccion actualizada',
                'direccion' => $direccion,
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            return response()->json([
                'message' => 'Error al actualizar direccion!'
            ], 500);
        }
    }
}

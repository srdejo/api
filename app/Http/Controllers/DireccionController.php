<?php

namespace App\Http\Controllers;

use App\Direccion;
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
            'geolocation' => 'required|boolean',
        ]);
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $direccion = Direccion::firstOrNew(['user_id' => $user->id]);
            $direccion->nomenclatura = $request->nomenclatura;
            $direccion->numero = $request->numero;
            $direccion->placa = $request->placa;
            $direccion->barrio = $request->barrio;
            $direccion->geolocation = $request->geolocation;
            $direccion->coordenadas = $request->coordenadas;
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

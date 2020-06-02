<?php

namespace App\Http\Controllers;

use App\Codigo;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CodigoController extends Controller
{
    public function GenerarCodigo($celular)
    {
        try {
            $codigo = new Codigo();
            $codigo->codigo = rand(1000, 9999);
            $codigo->celular = $celular;
            $codigo->save();

            return $codigo->id;
        } catch (\Throwable $th) {
            return 0;
        }
    }

    public function ValidarCodigo(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = User::firstOrNew(['celular' => $request->celular]);

            $codigo = Codigo::where('celular', $request->celular)->where('codigo', $request->codigo)->whereNull('fecha_hora_utilizacion')->first();
            if (isset($codigo) && isset($user)) {
                $fecha_hora_actual = Carbon::now();
                $codigo->fecha_hora_utilizacion = $fecha_hora_actual->toDateTimeString();
                $codigo->save();

                $user->codigo_id = $codigo->id;
                $user->save();

                DB::commit();
                return response()->json([
                    'message' => 'Código validado correctamente'
                ], 500);
            } else {
                DB::rollback();
                return response()->json([
                    'message' => 'El código no corresponde!'
                ], 500);
            }
        } catch (\Throwable $th) {
            Log::error($th);
            DB::rollback();
            return response()->json([
                'message' => 'Error al validar!'
            ], 500);
        }
    }
}

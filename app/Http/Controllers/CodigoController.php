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

            $res = $this->sendWhatsappNotification($codigo->codigo, $celular);
            Log::info('respuesta: '.json_encode($res));

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

                $respuesta = (new AuthController)->login($request);

                return $respuesta;
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

    public function EnviarCodigo(Request $request)
    {
        $request->validate([
            'celular'       => 'required|int',
        ]);

        $user_search = User::where('celular', $request->celular)->whereNotNull('codigo_id')->first();
        if (!isset($user_search)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }else{
            $codigo = $this->GenerarCodigo($request->celular);
            
            if ($codigo != 0) {
                DB::commit();
                return response()->json([
                    'message' => 'Codigo generado exitosamente!'
                ], 201);
            }else{
                DB::rollback();
                return response()->json([
                    'message' => 'Error al generar el código!'
                ], 500);
            }
        }
    }
    
    private function sendWhatsappNotification(string $otp, string $recipient)
    {
        $twilio_whatsapp_number = config('services.twilio.whatsapp_from');
        $account_sid = config('services.twilio.sid');
        $auth_token = config('services.twilio.token');

        $client = new \Twilio\Rest\Client($account_sid, $auth_token);
        $message = "Su Domi código es $otp";
        return $client->messages->create("whatsapp:+57$recipient", array('from' => "whatsapp:$twilio_whatsapp_number", 'body' => $message));
    }
}

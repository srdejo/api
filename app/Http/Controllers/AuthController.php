<?php

namespace App\Http\Controllers;

use App\Direccion;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    
    public function registrar(Request $request)
    {   
      
        $request->validate([
            'celular'     => 'required|int',
            'nombre'    => 'required|string',
            'acepta_sms' => 'required|boolean',
        ]);
        try {
            DB::beginTransaction();
            $user = User::firstOrNew(['celular' => $request->celular]);

            $user->celular = $request->celular;
            $user->nombre = $request->nombre;
            $user->acepta_sms = $request->acepta_sms;
            $user->userid_os = $request->userid_os;


            if (!$request->acepta_sms) {
                return response()->json([
                    'message' => 'Debe aceptar el envio de sms!'
                ], 201);
            }
            $user->save();

            $codigo = (new CodigoController)->GenerarCodigo($request->celular);
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
        } catch (\Throwable $th) {
            Log::error($th);
            DB::rollback();
            return response()->json([
                'message' => 'Error'
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'celular'       => 'required|int',
        ]);

        $user_search = User::where('celular', $request->celular)->whereNotNull('codigo_id')->first();
        if (!isset($user_search)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        Log::info('userid_os=>'.$request->userid_os);
        $user_search->userid_os = $request->userid_os;
        $user_search->save();
        Log::info($request->all());
        
        Auth::login($user_search);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addYear(1);
        }
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
            'direccion'    => $user->direccion,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' =>
        'Successfully logged out']);
    }

    public function user()
    {
        $user = Auth::user();
        $direccion = $user->direccion;
        return response()->json([
            'user' => $user,
        ]);
    }

    public function users()
    {
        return response()->json(User::paginate(15));
    }


    public function validarNumero(Request $request){
        $request->validate([
            'celular' => 'required|int',
        ]);

        $user = User::where('celular', $request->celular)->first();
        if (isset($user)) {
            return response()->json([
                'registrado' => true
            ], 200);
        }else {
            return response()->json([
                'registrado' => false
            ], 200);  
        }

    }
}

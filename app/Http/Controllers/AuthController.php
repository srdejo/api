<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function registrar(Request $request)
    {
        $request->validate([
            'celular'     => 'required|int|unique:users',
            'nombre'    => 'required|string',
            'direccion' => 'required|string',
        ]);
        $user = new User([
            'celular'     => $request->celular,
            'nombre'    => $request->nombre,
            'direccion' => $request->direccion,
            'kdx' => $request->kdx,
        ]);
        $user->save();
        return response()->json([
            'message' => 'Cliente registrado correctamente!'
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'celular'       => 'required|int',
        ]);

        $user_search = User::where('celular', $request->celular)->first();
        if(!isset($user_search)){
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        Auth::login($user_search);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();
        return response()->json([
            "celular"      => $user->celular,
            "nombre"       => $user->nombre,
            "direccion"    => $user->direccion,
            'kdx'          => $user->kdx,
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' =>
        'Successfully logged out']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}

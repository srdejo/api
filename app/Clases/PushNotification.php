<?php

namespace App\Clases;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PushNotification
{

  static function enviar($userid_os, $mensaje, $titulo)
  {
    $app_id = env('ONESIGNAL_APP_ID', '123456');
    $auth = env('auth_onesignal', '123456');

    //$response = Http::withBasicAuth('taylor@laravel.com', 'secret')->post('https://onesignal.com/api/v1/notifications', [
    $response = Http::withHeaders([
      'Content-Type' => 'application/json',
      'Authorization' => 'Basic YmM1MWEyZDAtYWMyYS00NTFiLWIwMjktMzNhMjhmMzlhZGIy',
    ])->post('https://onesignal.com/api/v1/notifications', [
      'app_id' => 'b659bb9e-acfa-4002-a195-34220d9c4eff',
      'include_player_ids' => array($userid_os),
      'contents' => array('en' =>  $mensaje ),
      'headings' => array('en' =>  $titulo ),
    ]);

    Log::info($response);
    /**
     * {
  "app_id": "b659bb9e-acfa-4002-a195-34220d9c4eff",
  "included_player_ids": ["c84be581-72a9-4e3e-9612-90686c07d68a"],
  "contents": {"en": "Hemos recibido tu pedido, ya se encuentra en preparación"},
  "headings": {"en": "Hola😃"}
}
     */
    return $response;
  }
}

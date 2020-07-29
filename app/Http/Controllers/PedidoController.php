<?php

namespace App\Http\Controllers;

use App\DetallePedido;
use App\Http\Requests\StorePedidoPost;
use App\Pedido;
use App\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PedidoController extends Controller
{
    public function store(StorePedidoPost $request)
    {
        DB::beginTransaction();
        $user = auth('api')->user();
        try {
            $pedido_ordenado = array_values(Arr::sort($request->pedido, function ($value) {
                return $value['negocio_id'];
            }));

            $negocio_id_temp = 0;
            $pedidos = array();
            foreach ($pedido_ordenado as $pedido) {
                if ($pedido['negocio_id'] != $negocio_id_temp) {
                    $pedido_nuevo = Pedido::create(['negocio_id' => $pedido['negocio_id'], 'user_id' => $user->id]);
                    $negocio_id_temp = $pedido['negocio_id'];
                }
                $detalle = $pedido_nuevo->detalles()->create(['producto_id' => $pedido['producto_id'], 'cantidad' => $pedido['cantidad']]);
                //$pedidos_registrados = $array = Arr::add($pedido_nuevo);
            }

            DB::commit();
            return response()->json([
                'message' => 'Pedido almacenado',
            ], 200);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'error' => $th->getMessage(),

            ], 500);
        }
    }
}

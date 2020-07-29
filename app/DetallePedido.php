<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    
    protected $fillable = [
        'producto_id', 'cantidad'
    ];
    
    public function pedido()
    {
        return $this->belongsTo('App\Pedido');
    }
}

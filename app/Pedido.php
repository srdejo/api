<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'user_id', 'negocio_id'
    ];
    
    public function detalles()
    {
        return $this->hasMany('App\DetallePedido');
    }
    
    public function negocio()
    {
        return $this->belongsTo('App\Negocio');
    }
}

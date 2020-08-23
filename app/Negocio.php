<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Negocio extends Model
{
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function productos()
    {
        return $this->hasMany('App\Producto');
    }
}

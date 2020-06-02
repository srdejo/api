<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    protected $table = 'direcciones';

    protected $hidden = [
        'created_at', 'updated_at'
    ];
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Producto extends Model
{
    protected $hidden = [
        'created_at', 'updated_at','imagen'
    ];
    
    protected $appends = array('url_imagen');

    public function getUrlImagenAttribute()
    {
        return Storage::url($this->imagen)->full();
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NegocioController extends Controller
{
    public function index()
    {
        return view('negocios.list');
    }
}

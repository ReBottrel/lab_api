<?php

namespace App\Http\Controllers\Veterinario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VetController extends Controller
{
    public function index()
    {
        return view('veterinario.index');
    }

}

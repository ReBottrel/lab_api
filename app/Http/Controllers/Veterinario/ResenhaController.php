<?php

namespace App\Http\Controllers\Veterinario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResenhaController extends Controller
{
    public function step1()
    {
        return view('veterinario.resenha.step-1');
    }
}

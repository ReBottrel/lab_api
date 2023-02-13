<?php

namespace App\Http\Controllers\Veterinario;

use App\Http\Controllers\Controller;
use App\Models\Marking;
use Illuminate\Http\Request;

class ResenhaController extends Controller
{
    public function step1()
    {
        $marcas = Marking::where('categorie', 1)->get();
        return view('veterinario.resenha.step-1', get_defined_vars());
    }
    public function step2()
    {
        $marcas = Marking::where('categorie', 2)->get();
        return view('veterinario.resenha.step-2', get_defined_vars());
    }
}

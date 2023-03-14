<?php

namespace App\Http\Controllers\Veterinario;

use App\Models\Owner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VetController extends Controller
{
    public function index()
    {
        return view('veterinario.index');
    }

    public function select()
    {
        $owners = Owner::where('vet_id', auth()->user()->id)->get();
        return view('veterinario.select', get_defined_vars());
    }

    public function owners()
    {
        $owners = Owner::where('vet_id', auth()->user()->id)->get();
        return view('veterinario.owner', get_defined_vars());
    }
    public function owners2()
    {
        $owners = Owner::where('vet_id', auth()->user()->id)->get();
        return view('veterinario.owner2', get_defined_vars());
    }
}

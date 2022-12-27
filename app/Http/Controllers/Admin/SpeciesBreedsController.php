<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Breed;
use App\Models\Specie;
use Illuminate\Http\Request;

class SpeciesBreedsController extends Controller
{
    public function index()
    {
        $species = Specie::get();
        return view('admin.species_and_breeds.species', get_defined_vars());
    }



    public function store(Request $request)
    {
        $especie = Specie::create([
            'name' => strtoupper($request->name),
            'description' => $request->description,
        ]);
        return redirect()->route('species')->with('success', 'Espécie criada com éxito');
    }

    public function breeds()
    {
        $species = Specie::get();
        $breeds = Breed::with('specie')->get();
        return view('admin.species_and_breeds.breeds', get_defined_vars());
    }

    public function storeBreed(Request $request)
    {
        $raça = Breed::create([
            'name' => strtoupper($request->name),
            'specie_id' => $request->specie_id,
            'description' => $request->description,
        ]);
        return redirect()->route('breeds')->with('success', 'Raça criada com éxito');
    }

    public function getBreed($id)
    {
        $breeds = Breed::where('specie_id', $id)->get();
        return response()->json($breeds);
    }
}

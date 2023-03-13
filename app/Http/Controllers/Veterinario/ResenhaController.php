<?php

namespace App\Http\Controllers\Veterinario;

use App\Models\Animal;
use App\Models\Marking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ResenhaController extends Controller
{
    public function animalCreate($id)
    {
        $order = $id;
        return view('veterinario.resenha.animal-create', get_defined_vars());
    }

    public function animalStore(Request $request)
    {
        $animal = Animal::create([
            'user_id' => auth()->user()->id,
            'order_id' => $request->order,
            'register_number_brand' => $request->register_number_brand,
            'animal_name' => $request->animal_name,
            'especies' => $request->especies,
            'breed' => $request->breed,
            'sex' => $request->sex,
            'age' => $request->age,
            'birth_date' => $request->birth_date,
            'fur' => $request->fur,
            'description' => $request->description,
            'status' => 1,
            'chip_number' => $request->chip_number,
            'registro_pai' => $request->registro_pai,
            'pai' => $request->pai,
            'registro_mae' => $request->registro_mae,
            'mae'   => $request->mae,
        ]);

        return redirect()->route('resenha.step1', $animal->id);
    }
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

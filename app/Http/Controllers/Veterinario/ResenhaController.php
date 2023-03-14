<?php

namespace App\Http\Controllers\Veterinario;

use App\Models\Animal;
use App\Models\Marking;
use Illuminate\Http\Request;
use App\Models\ResenhaAnimal;
use App\Http\Controllers\Controller;
use App\Models\OrderRequest;

class ResenhaController extends Controller
{
    public function animalCreate($id)
    {
        $order = $id;
        return view('veterinario.resenha.animal-create', get_defined_vars());
    }

    public function animalSelect($id)
    {
        $order = OrderRequest::find($id);
        $animals = Animal::where('user_id', $order->user_id)->get();
        return view('veterinario.animal-select', get_defined_vars());
    }
    public function animalUpdate(Request $request)
    {
        $animal = Animal::find($request->animal_id);
        $animal->update([
            'order_id' => $request->order_id,
        ]);
        return redirect()->route('resenha.step1', $animal->id);
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
    public function step1($id)
    {
        $animal = $id;
        $marcas = Marking::where('categorie', 1)->get();
        return view('veterinario.resenha.step-1', get_defined_vars());
    }
    public function step2($id)
    {
        $animal = $id;
        $marcas = Marking::where('categorie', 2)->get();
        return view('veterinario.resenha.step-2', get_defined_vars());
    }
    public function step3($id)
    {
        $animal = $id;
        $marcas = Marking::where('categorie', 3)->get();
        return view('veterinario.resenha.step-3', get_defined_vars());
    }
    public function step4($id)
    {
        $animal = $id;
        $marcas = Marking::where('categorie', 4)->get();
        return view('veterinario.resenha.step-4', get_defined_vars());
    }
    public function step5($id)
    {
        $animal = $id;
        $marcas = Marking::where('categorie', 5)->get();
        return view('veterinario.resenha.step-5', get_defined_vars());
    }
    public function step6($id)
    {
        $animal = $id;
        $marcas = Marking::where('categorie', 6)->get();
        return view('veterinario.resenha.step-6', get_defined_vars());
    }
    public function step7($id)
    {
        $animal = $id;
        $marcas = Marking::where('categorie', 7)->get();
        return view('veterinario.resenha.step-7', get_defined_vars());
    }
    public function store(Request $request)
    {
        $image = $request->input('data');

        // $image = base64_decode($image);
        $data = ResenhaAnimal::create([
            'animal_id' => $request->animal_id,
            'user_id' => auth()->user()->id,
            'resenha' => $request->side,
            'localization' => $image,
        ]);

        return response()->json(['message' => 'Imagem salva com sucesso!']);
    }
}

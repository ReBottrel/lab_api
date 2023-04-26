<?php

namespace App\Http\Controllers\Veterinario;

use App\Models\Fur;
use App\Models\Breed;
use App\Models\Owner;
use App\Models\Animal;
use App\Models\Specie;
use App\Models\Marking;
use App\Models\Veterinario;
use App\Models\OrderRequest;
use App\Models\PedidoAnimal;
use Illuminate\Http\Request;
use App\Models\ResenhaAnimal;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;

class ResenhaController extends Controller
{
    public function animalCreate($id)
    {
        $order = $id;
        $especies = Specie::all();
        $breeds = Breed::all();
        $furs = Fur::all();
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
        $order = OrderRequest::find($request->order_id);
        $pedido = PedidoAnimal::create([
            'id_pedido' => $request->order_id,
            'id_animal' => $request->animal_id,
            'owner_id' => $order->owner_id,
            'user_id' => auth()->user()->id,
            'status' => 1,
        ]);
        return redirect()->route('resenha.step1', $pedido->id);
    }

    public function animalStore(Request $request)
    {
        $order = OrderRequest::find($request->order);
        $animal = Animal::create([
            'user_id' => $order->user_id,
            'vet_id' => auth()->user()->id,
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

        $pedido = PedidoAnimal::create([
            'id_pedido' => $request->order,
            'id_animal' => $animal->id,
            'owner_id' => $order->owner_id,
            'user_id' => auth()->user()->id,
            'status' => 1,
        ]);

        return redirect()->route('resenha.step1', $pedido->id);
    }
    public function step1($id)
    {
        $pedido = PedidoAnimal::find($id);
        $animal = $pedido->id_animal;
        $marcas = Marking::where('categorie', 1)->get();
        return view('veterinario.resenha.step-1', get_defined_vars());
    }
    public function step2($id)
    {
        $pedido = PedidoAnimal::find($id);
        $animal = $pedido->id_animal;
        $marcas = Marking::where('categorie', 2)->get();
        return view('veterinario.resenha.step-2', get_defined_vars());
    }
    public function step3($id)
    {
        $pedido = PedidoAnimal::find($id);
        $animal = $pedido->id_animal;
        $marcas = Marking::where('categorie', 3)->get();
        return view('veterinario.resenha.step-3', get_defined_vars());
    }
    public function step4($id)
    {
        $pedido = PedidoAnimal::find($id);
        $animal = $pedido->id_animal;
        $marcas = Marking::where('categorie', 4)->get();
        return view('veterinario.resenha.step-4', get_defined_vars());
    }
    public function step5($id)
    {
        $pedido = PedidoAnimal::find($id);
        $animal = $pedido->id_animal;
        $marcas = Marking::where('categorie', 5)->get();
        return view('veterinario.resenha.step-5', get_defined_vars());
    }
    public function step6($id)
    {
        $pedido = PedidoAnimal::find($id);
        $animal = $pedido->id_animal;
        $marcas = Marking::where('categorie', 6)->get();
        return view('veterinario.resenha.step-6', get_defined_vars());
    }
    public function step7($id)
    {
        $pedido = PedidoAnimal::find($id);
        $animal = $pedido->id_animal;
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
            'pedido' => $request->pedido_id,
        ]);

        return response()->json(['message' => 'Imagem salva com sucesso!']);
    }
    public function viewResenha($id)
    {
        $resenhas = ResenhaAnimal::where('pedido', $id)->get();
        $pedido = PedidoAnimal::find($id);
        $animal = Animal::find($pedido->id_animal);
        $order = OrderRequest::find($pedido->id_pedido);
        $owner = Owner::find($pedido->owner_id);
        $veterinario = Veterinario::find($pedido->user_id);
        return view('veterinario.resenha.view-resenha', get_defined_vars());
    }


}

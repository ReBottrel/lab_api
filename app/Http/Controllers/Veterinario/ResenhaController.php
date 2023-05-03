<?php

namespace App\Http\Controllers\Veterinario;

use App\Models\Fur;
use App\Models\User;
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
use App\Models\Exam;
use App\Models\ExamToAnimal;
use Illuminate\Support\Facades\Mail;


class ResenhaController extends Controller
{
    public function animalCreate($id)
    {
        $pedido = $id;
        $especies = Specie::all();
        $breeds = Breed::all();
        $furs = Fur::all();
        return view('veterinario.resenha.animal-create', get_defined_vars());
    }

    public function animalSelect($id)
    {
        $pedido = PedidoAnimal::find($id);
        $owner = Owner::find($pedido->owner_id);
        $user = User::where('id', $owner->user_id)->first();
        $animals = Animal::where('user_id', $user->id)->get();
        return view('veterinario.animal-select', get_defined_vars());
    }



    public function animalUpdate(Request $request)
    {
        $animal = Animal::find($request->animal_id);
        $pedido = PedidoAnimal::find($request->pedido);
        $pedido->update([
            'id_animal' => $request->animal_id,
        ]);
        return redirect()->route('animal.update.view', $pedido->id);
    }

    public function animalUpdateView($id)
    {
        $pedido = $id;
        $animal = Animal::find($id);

        return view('veterinario.resenha.update-animal-select', get_defined_vars());
    }

    public function UpdateData(Request $request, $id)
    {
        $animal = Animal::find($id);
        $animal->update([
            'collect_date' => $request->collect_date,
            'birth_date' => $request->birth_date,
        ]);
        return redirect()->route('resenha.step1', $request->pedido);
    }

    public function animalStore(Request $request)
    {
        $order = OrderRequest::find($request->order);
        $pedido = PedidoAnimal::find($request->pedido_id);
        $animal = Animal::create([
            'vet_id' => auth()->user()->id,
            'order_id' => $request->order,
            'register_number_brand' => $request->register_number_brand,
            'animal_name' => $request->animal_name,
            'especies' => $request->especies,
            'breed' => $request->breed,
            'sex' => $request->sex,
            'age' => $request->age,
            'state' => $request->state,
            'city' => $request->city,
            'animal_location' => $request->animal_location,
            'birth_date' => $request->birth_date,
            'fur' => $request->fur,
            'description' => $request->description,
            'status' => 1,
            'chip_number' => $request->chip_number,
            'oesa_cad' => $request->oesa_cad,
            'numero_aie' => $request->numero_aie,
            'numero_mormo' => $request->numero_mormo,
            'collect_date' => $request->collect_date,
            'portaria_habilitacao' => $request->portaria_habilitacao,
            'utility' => $request->utility,
            'classification' => $request->classification,
            'number_existing_equines' => $request->number_existing_equines,

        ]);

        $pedido = $pedido->update([
            'id_animal' => $animal->id,
        ]);

        return redirect()->route('resenha.step1', $request->pedido_id);
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

        // Envio do e-mail


        return response()->json(['message' => 'Imagem salva com sucesso!']);
    }

    public function finishResenha($id)
    {
        $pedido = PedidoAnimal::find($id);

        return view('veterinario.resenha.finish', get_defined_vars());
    }

    public function viewResenha($id)
    {
        $resenhas = ResenhaAnimal::where('pedido', $id)->get();
        $pedido = PedidoAnimal::find($id);
        $animal = Animal::find($pedido->id_animal);
        $order = OrderRequest::find($pedido->id_pedido);
        $owner = Owner::find($pedido->owner_id);
        $veterinario = Veterinario::find($pedido->user_id);
        $exam = ExamToAnimal::where('animal_id', $pedido->id_animal)->first();
        $exame = Exam::where('id', $exam->exam_id)->first();
        return view('veterinario.resenha.view-resenha', get_defined_vars());
    }
}

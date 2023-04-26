<?php

namespace App\Http\Controllers\Veterinario;

use App\Models\User;
use App\Models\Owner;
use App\Models\Animal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Breed;
use App\Models\Fur;
use App\Models\Specie;

class VetAnimalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $animais = Animal::where('vet_id', auth()->user()->id)->get();
        return view('veterinario.animais.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $owners = Owner::where('vet_id', auth()->user()->id)->get();
        $especies = Specie::all();
        $breeds = Breed::all();
        $furs = Fur::all();
        return view('veterinario.animais.create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $owner = Owner::find($request->owner_id);
        $user = User::where('id', $owner->user_id)->first();

        $animal = Animal::create([
            'vet_id' => auth()->user()->id,
            'user_id' => $user->id,
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
            'registro_pai' => $request->registro_pai,
            'pai' => $request->pai,
            'registro_mae' => $request->registro_mae,
            'mae'   => $request->mae,
            'oesa_cad' => $request->oesa_cad,
            'numero_aie' => $request->numero_aie,
            'numero_mormo' => $request->numero_mormo,
            'collect_date' => $request->collect_date,
        ]);

        return redirect()->route('vet.animal.index')->with('success', 'Animal cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $animal = Animal::find($id);
        $owners = Owner::where('vet_id', auth()->user()->id)->get();
        return view('veterinario.animais.edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $animal = Animal::find($id);
        $owner = Owner::find($request->owner_id);
        $user = User::where('id', $owner->user_id)->first();

        $animal->update([
            'vet_id' => auth()->user()->id,
            'user_id' => $user->id,
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
        return redirect()->route('vet.animal.index')->with('success', 'Animal alterado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

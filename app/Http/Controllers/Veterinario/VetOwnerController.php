<?php

namespace App\Http\Controllers\Veterinario;

use App\Models\User;
use App\Models\Owner;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class VetOwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $owners = Owner::where('vet_id', auth()->user()->id)->get();
        return view('veterinario.owner.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('veterinario.owner.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $documents = str_replace(['.', '-', '/'], ['', '', ''],  $request->document);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($documents),
            'permission' => 1,
        ]);

        $info = UserInfo::create([
            'user_id' => $user->id,
            'document' => $request->document,
            'phone' => $request->cell,
            'zip_code' => $request->zip_code,
            'address' => $request->address,
            'number' => $request->number,
            'complement' => $request->complement,
            'district' => $request->district,
            'city' => $request->city,
            'state' => $request->state,

        ]);
        $dados = Owner::create([
            'user_id' => $user->id,
            'document' => $request->document,
            'owner_name' => $request->name,
            'email' => $request->email,
            'fone' => $request->fone,
            'cell' => $request->cell,
            'zip_code' => $request->zip_code,
            'address' => $request->address,
            'number'  => $request->number,
            'complement' => $request->complement,
            'district' => $request->district,
            'city'  => $request->city,
            'state' => $request->state,
            'propriety' => $request->propriety,
            'vet_id' => $request->user_id,
        ]);

        return redirect()->route('vet.owner.index')->with('success', 'Proprietário cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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

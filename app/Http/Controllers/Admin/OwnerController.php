<?php

namespace App\Http\Controllers\Admin;

use App\Models\Exam;
use App\Models\User;
use App\Models\Owner;
use App\Models\Animal;
use App\Models\OrderRequest;
use Illuminate\Http\Request;
use App\Models\OrderRequestPayment;
use App\Http\Controllers\Controller;
use App\Models\UserInfo;
use Illuminate\Support\Facades\Hash;

class OwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $owners = Owner::paginate(10);
        return view('admin.owners.index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.owners.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $order = OrderRequest::find($request->order_id);
        $documents = str_replace(['.', '-', '/'], ['', '', ''],  $request->document);

        $user = User::create([
            'name' => $request->owner_name,
            'email' => $request->email,
            'password' => Hash::make($documents),
            'permission' => 1,
        ]);

        $info = UserInfo::create([
            'user_id' => $user->id,
            'document' => $request->document,
            'phone' => $request->fone,
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
            'owner_name' => $request->owner_name,
            'email' => $request->email,
            'fone' => $request->fone,
            'zip_code' => $request->zip_code,
            'address' => $request->address,
            'number'  => $request->number,
            'complement' => $request->complement,
            'district' => $request->district,
            'city'  => $request->city,
            'state' => $request->state,
            'propriety' => $request->propriety,
        ]);


        return response()->json($dados);
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
        $owner = Owner::find($id);
        return view('admin.owners.edit', get_defined_vars());
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
        $owner = Owner::find($id);
        $owner->update($request->all());
        return redirect()->route('owners')->with('success', 'Editado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
    public function destroyAll()
    {
        $owners = Owner::where('id', '!=', 1)->get();
        foreach ($owners as $owner) {
            $owner->delete();
        }
        return redirect()->back();
    }
    public function getAnimals($old_id)
    {
        $animals = Animal::where('owner_id', $old_id)->get();
         return view('admin.owners.animals', get_defined_vars());
    }
}

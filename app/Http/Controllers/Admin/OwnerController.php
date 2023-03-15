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
        $owners = Owner::orderBy('owner_name', 'asc')->paginate(10);
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
            'owner_name' => $request->owner_name,
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
        ]);

        if (auth()->user()->permission == 10) {
            return response()->json($dados);
        } else {
            return redirect()->route('order.create.painel')->with('success', 'Proprietário cadastrado com sucesso!');
        }
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
    public function destroy(Request $request)
    {
        $owner = Owner::find($request->id);
        $owner->delete();
        return response()->json($owner);
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

    public function ownerAcess(Request $request)
    {

        $owner = Owner::find($request->id);
        $documents = str_replace(['.', '-', '/'], ['', '', ''],  $owner->document);


        $user = User::create([
            'name' => $owner->owner_name,
            'email' => strtolower($owner->email),
            'password' => Hash::make($documents),
            'permission' => 1,
        ]);

        $info = UserInfo::create([
            'user_id' => $user->id,
            'document' => $owner->document,
            'phone' => $owner->cell,
            'zip_code' => $owner->zip_code,
            'address' => $owner->address,
            'number' => $owner->number,
            'complement' => $owner->complement,
            'district' => $owner->district,
            'city' => $owner->city,
            'state' => $owner->state,

        ]);

        $owner->update([
            'user_id' => $user->id,
        ]);

        return response()->json($user);
    }

    public function getOwner($id)
    {
        $owner = Owner::find($id);
        return view('admin.owners.show', get_defined_vars());
    }

    public function getUser($id)
    {
        $user = User::with('info')->find($id);
        return view('admin.owners.user', get_defined_vars());
    }
    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        $user->info()->update([
            'document' => $request->document,
            'phone' => $request->phone,
            'zip_code' => $request->zip_code,
            'address' => $request->address,
            'number' => $request->number,
            'complement' => $request->complement,
            'district' => $request->district,
            'city' => $request->city,
            'state' => $request->state,
        ]);

        return redirect()->back()->with('success', 'Editado com sucesso!');
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $owners = Owner::where('owner_name', 'LIKE', '%' . $request->search . "%")->get();;
            $viewRender = view('admin.owners.search', get_defined_vars())->render();
            return response()->json([get_defined_vars()]);
        }
    }
}

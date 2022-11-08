<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Owner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class UserDadosController extends Controller
{
    public function index()
    {
        $user = User::with('info')->find(auth()->user()->id);
        return view('user.dados', get_defined_vars());
    }
    public function address()
    {
        $user = User::with('info')->find(auth()->user()->id);
        return view('user.endereco', get_defined_vars());
    }
    public function updateAddress(Request $request)
    {
        $user = User::with('info')->find(auth()->user()->id);
        $owner = Owner::where('user_id', auth()->user()->id)->first();
        $user->info()->update([
            'zip_code' => $request->zip_code,
            'address' => $request->address,
            'number' => $request->number,
            'complement' => $request->complement,
            'district' => $request->district,
            'city' => $request->city,
            'state' => $request->state,
        ]);
        $owner->update([
            'zip_code' => $request->zip_code,
            'address' => $request->address,
            'number' => $request->number,
            'complement' => $request->complement,
            'district' => $request->district,
            'city' => $request->city,
            'state' => $request->state,
        ]);

        return redirect()->route('user.dados')->with('success', 'Endereço alterado com sucesso!');
    }

    public function editDados()
    {
        $user = User::with('info')->find(auth()->user()->id);
        return view('user.info-edit', get_defined_vars());
    }

    public function updateUser(Request $request)
    {
        $user = User::with('info')->find(auth()->user()->id);
        $user->update([
            'name' => $request->name,
            'password' => Hash::make($request->password)
        ]);
        $user->info()->update([
            'phone' => $request->phone,

        ]);
        return redirect()->route('user.dados')->with('success', 'Dados alterados com sucesso!');
    }


    public function getCep(Request $request)
    {
        $https = Http::get("https://viacep.com.br/ws/{$request->cep}/json/");

        $dados = json_decode($https->body());

        return response()->json($dados);
    }
}

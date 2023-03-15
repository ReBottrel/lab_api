<?php

namespace App\Http\Controllers\Veterinario;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Veterinario;
use Illuminate\Support\Facades\Hash;

class VetConfigController extends Controller
{
    public function index()
    {

        return view('veterinario.configs.index');
    }

    public function updateUser(Request $request)
    {
        $user = Veterinario::find($request->id);

        $data = [
            'name' => $request->name,
        ];

        $password = $request->input('password');
        if (!empty($password)) {
            $data['password'] = Hash::make($password);
        }

        $user->update($data);

        return redirect()->route('vet.configs')->with('success', 'Dados atualizados com sucesso!');
    }
}

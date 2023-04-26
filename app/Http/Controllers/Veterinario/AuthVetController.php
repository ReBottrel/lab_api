<?php

namespace App\Http\Controllers\Veterinario;

use App\Models\Veterinario;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthVetController extends Controller
{

    public function showLoginForm()
    {
        return view('veterinario.auth.login');
    }

    public function login(Request $request)
    {
        // Validate the form data
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);

        // Attempt to log the user in
        if (Auth::guard('veterinario')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            // if successful, then redirect to their intended location
            return response()->json(['success' => 'Login successful'], 200);
        }

        // if unsuccessful, then redirect back to the login with the form data
        return response()->json(['error' => 'Login unsuccessful'], 401);
    }
    public function showRegisterForm()
    {
        return view('veterinario.auth.cadastro');
    }
    public function registerStore(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:veterinarios',
            'password' => 'required|string|min:6|confirmed',
            'cpf' => 'required|max:255',
            'portaria' => 'required|max:255',
            'crmv' => 'required|max:255',
            'phone' => 'required|max:255',
        ]);


        $user = Veterinario::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'cpf' => $request->cpf,
            'portaria' => $request->portaria,
            'crmv' => $request->crmv,
            'phone' => $request->phone,
            'address' => $request->address,
            'number' => $request->number,
            'district' => $request->district,
            'city' => $request->city,
            'state' => $request->state,
            'cep' => $request->cep,
        ]);

        auth()->login($user);
        return response()->json(['success' => 'Cadastro realizado com sucesso'], 200);
    }

    public function sair(Request $request)
    {
        Auth::guard('veterinario')->logout();
        return redirect()->route('vet.login');
    }
}

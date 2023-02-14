<?php

namespace App\Http\Controllers\Veterinario;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthVetController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:veterinario');
    }

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

    public function logout()
    {
        Auth::guard('veterinario')->logout();
        return redirect()->route('vet.login');
    }

}

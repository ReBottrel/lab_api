<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // valida os dados do formulário
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // tenta realizar o login
        if (Auth::attempt($request->only('email', 'password'))) {
            // login bem-sucedido
            return response()->json([
                'success' => true,
                'message' => 'Login realizado com sucesso',
            ]);
        } else {
            // login falhou
            return response()->json([
                'success' => false,
                'message' => 'Falha ao realizar login. Verifique seu e-mail e senha.',
            ]);
        }
    }
}

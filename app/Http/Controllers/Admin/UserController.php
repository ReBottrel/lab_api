<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('info')->paginate();
        return view('admin.usuarios.index', get_defined_vars());
    }

    //criar função para buscar usuário
    public function search(Request $request)
    {
        $users = User::where('name', 'like', "%{$request->search}%")
            ->orWhere('email', 'like', "%{$request->search}%")
            ->get();
        
            $view = view('admin.usuarios.search', compact('users'))->render();

        return response()->json(['html' => $view]);
    }
    //criar função para deletar usuário
    public function destroy(Request $request)
    {
        $user = User::find($request->id);
        $user->delete();
        return response()->json(['success' => 'Usuário deletado com sucesso']);
    }

}

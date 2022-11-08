<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserDadosController extends Controller
{
    public function index()
    {
        $user = User::with('info')->find(auth()->user()->id);
        return view('user.dados', get_defined_vars());
    }
}

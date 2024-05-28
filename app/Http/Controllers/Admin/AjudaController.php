<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AjudaController extends Controller
{
    public function erroPagamento()
    {
        return view('admin.ajuda.erro-pagamento');
    }
}

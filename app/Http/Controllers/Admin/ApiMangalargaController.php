<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class ApiMangalargaController extends Controller
{
    public function getApi()
    {
        $response = Http::get('http://laboratorios.abccmm.org.br/api/coletas/18');
        $data = $response->body();
        $data = json_decode($data);
        return response()->json($data);
        
    }
}

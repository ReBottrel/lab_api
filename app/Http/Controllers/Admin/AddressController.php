<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class AddressController extends Controller
{
    public function getCep(Request $request)
    {
        $https = Http::get("https://viacep.com.br/ws/{$request->cep}/json/");

        $dados = json_decode($https->body());

        return response()->json($dados);
    }

    public function estados()
    {
        $https = Http::withoutVerifying()->withOptions([
            ["verify" => false],
        ])->get("https://brasilapi.com.br/api/ibge/uf/v1");

        $dados = json_decode($https->body());

        return response()->json($dados);
    }
    public function cidades(Request $request)
    {
        $https = Http::withoutVerifying()->withOptions([
            ["verify" => false],

        ])->get("https://brasilapi.com.br/api/ibge/municipios/v1/{$request->estado}");

        $dados = json_decode($https->body());

        return response()->json($dados);
    }
}

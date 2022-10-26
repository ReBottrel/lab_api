<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TesteController extends Controller
{
    public function testeZapApi()
    {
        $response = Http::post('https://api.z-api.io/instances/3B30881EC3E99084D3D3B6927F6ADC67/token/66E633717A0DCDD3D4A1BC19/send-text', [
            "phone" => "5541985214474",
            "message" => "Teste de integração do zap-API feito!"
        ]);
    }
}

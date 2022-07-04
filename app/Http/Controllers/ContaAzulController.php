<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ContaAzulController extends Controller
{
    public function callbackContaAzul(Request $request)
    {
        $data = [
            "grant_type" => "authorization_code",
            "redirect_uri" => env('CA_REDIRECT_URI'),
            "code" => $request->code
        ];
        $response = Http::withHeaders(['Authorization' => 'Basic '.base64_encode(env('CA_CLIENT_ID').':'.env('CA_CLIENT_SECRET'))])->post('https://api.contaazul.com/oauth2/token', $data)->object();
        $response = collect($response)->put('expires_at', date('Y-m-d H:i:s', strtotime('+ '.$response->expires_in.' seconds')));
        if(isset($request->code)) Storage::disk('local')->put('conta_azul_T.json', $response->toJson());
    }

    public function getUrlCode(Request $request)
    {
        $redirect_uri = env('CA_REDIRECT_URI');
        $client_id = env('CA_CLIENT_ID');
        $scope = 'Sales,Customer,Product,Service,Contract';
        $state = \Str::random(20);
        $return_url = "https://api.contaazul.com/auth/authorize?redirect_uri=$redirect_uri&client_id=$client_id&scope=$scope&state=$state";
        return response()->json($return_url);
    }

    ############ENVIO DE DADOS############

    public function sendSales(Request $request)
    {
        $ca_token = json_decode(Storage::disk('local')->get('conta_azul_T.json'));
        // $ca_token = collect($ca_token)->toArray();

        return response()->json(date('Y-m-d H:i:s', strtotime('+ '.$ca_token->expires_in.' seconds')));
    }
}

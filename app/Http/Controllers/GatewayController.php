<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GatewayController extends Controller
{
    public function generateToken()
    {
        $data = [
            "email" => env('IOPLAY_EMAIL'),
            "secret" => env('IOPLAY_SECRET'),
            "io_seller_id" => env('IOPLAY_IO_SELLER_ID')
        ];
        $response = Http::post(env('IOPLAY_URL').'auth/login', $data)->object();
        \Log::info(collect($response));
    }
}

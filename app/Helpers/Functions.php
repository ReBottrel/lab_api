<?php

use Carbon\Carbon;
use App\Models\User;

if (!function_exists('user_token')) {
    function user_token()
    {
        $access_token = \Request::header()['access-token'][0];
        $access_token = explode(':', base64_decode($access_token));
        $user = User::with('info')->where('email', ($access_token[0] ?? null))->where('access_token', ($access_token[1] ?? null))->where('token_expires_in', '>=', date('Y-m-d H:i:s'))->first();
        return $user;
    }
}

function parseDate($date)
{
    if (!$date) {
        return null; // Retorna nulo se a data for vazia
    }

    // Tenta no formato 'Y-m-d'
    try {
        return Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');
    } catch (\Exception $e) {
        // Se falhar, tenta no formato 'd/m/Y'
        try {
            return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null; // Retorna nulo se todos os formatos falharem
        }
    }
}

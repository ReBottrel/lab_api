<?php

use App\Models\User;

if(!function_exists('user_token')){
    function user_token(){
        $access_token = \Request::header()['access-token'][0];
        $access_token = explode(':', base64_decode($access_token));
        $user = User::where('email', ($access_token[0]??null))->where('access_token', ($access_token[1]??null))->where('token_expires_in', '>=', date('Y-m-d H:i:s'))->first();
        return $user;
    }
}
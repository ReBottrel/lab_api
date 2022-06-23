<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $update_token['access_token'] = (string) \Str::uuid();
        $update_token['token_expires_in'] = date('Y-m-d H:i:s', strtotime('+24 Hours'));

        $remember = $request->remember ? true : false;

        $authValid = Auth::guard('web')->validate(['email' => $request->email, 'password' => $request->password]);

        if($authValid){
            User::where('email', $request->email)->update($update_token);

            return response()->json(['access_token' => base64_encode($request->email.':'.$update_token['access_token']), 'token_expires_in' => $update_token['token_expires_in']]);
        }else{
            return response()->json('Email ou Senha incorretos!', 422);
        }
    }

    public function register(Request $request)
    {
        $create_user['name'] = $request->name;
        $create_user['email'] = $request->email;
        $create_user['password'] = Hash::make($request->password);
        $create_user['permission'] = $request->permission ?? 1;
        $create_user['access_token'] = (string) \Str::uuid();
        $create_user['token_expires_in'] = date('Y-m-d H:i:s', strtotime('+24 Hours'));

        User::create($create_user);

        return response()->json(['access_token' => base64_encode($create_user['email'].':'.$create_user['access_token']), 'token_expires_in' => $create_user['token_expires_in']]);
    }
}

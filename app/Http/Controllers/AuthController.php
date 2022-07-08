<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

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

    public function getUser()
    {
        return response()->json(user_token());
    }

    // Auto Login
    public function autoLogin(Request $request)
    {
        // Verificando se existe o arquivo do token
        if(Storage::disk('local')->exists('user_tokens/'.$request->token.'.txt')){
            // Descriptogrando o token dentro do arquivo
            $encrypt_token = Crypt::decryptString(Storage::disk('local')->get('user_tokens/'.$request->token.'.txt'));
            $encrypt_token = json_decode($encrypt_token);

            // Verifiando se o token informado é igual ao que t adentro do arquivo
            if($request->token == $encrypt_token->file){
                // Atualizando token de login
                $update_token['access_token'] = (string) \Str::uuid();
                $update_token['token_expires_in'] = date('Y-m-d H:i:s', strtotime('+24 Hours'));

                // Descriptogrando o token unico para pegar o email
                $token_unique = Crypt::decryptString($encrypt_token->token_unique);
                $token_unique = explode(':', base64_decode($token_unique))[0];
                
                // Gerando atualização de login
                User::where('email', $request->email)->update($update_token);

                Storage::disk('local')->delete('user_tokens/'.$request->token.'.txt');
                return response()->json([
                    'access_token' => base64_encode($request->email.':'.$update_token['access_token']),
                    'token_expires_in' => $update_token['token_expires_in'],
                    'order_request_id'=> $encrypt_token->order_request_id ?? null,
                    'or_payment_id'=> $encrypt_token->or_payment_id ?? null,
                ]);
            }
        }

        return response()->json('Token inexistente ou inativo', 412);
    }
}

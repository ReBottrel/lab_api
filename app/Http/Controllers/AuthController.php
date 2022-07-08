<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use App\Mail\UserNotifyStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public $users_permission_name = [
        'cliente' => 0,
        'veterinario' => 5,
        'admin' => 10
    ];

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

    public function getUsers(Request $request,$user)
    {
        $user_token = user_token();
        if($user_token->permission == 10){
            $users = User::where('permission', $this->users_permission_name[$user])->paginate($request->per_page ?? 20);
            return response()->json([$user => $users]);
        }
        return response()->json('Voce não possui permississão suficiente!', 402);
    }

    // Atualização de usuario
    public function updateUser(Request $request)
    {
        $user = user_token();

        $data_user = collect($request->all())->forget(['info_add','permission', 'status', 'access-token', 'email']);
        User::find($user->id)->update($data_user->toArray());

        if($request->info_add){
            $data_info_add = collect($request->info_add)->forget('user_id');
            if(empty($user->info)) UserInfo::create($data_info_add->put('user_id', $user->id)->toArray());
            if(!empty($user->info)) UserInfo::where('user_id',$user->id)->update($data_info_add->toArray());
        }

        return response()->json(User::with('info')->find($user->id));
    }

    // Atualização de todos Usuarios pelo admin
    public function updateUsers(Request $request)
    {
        $user = user_token();
        if($user->permission == 10){
            $data_user = collect($request->all())->forget(['info_add', 'access-token', 'email']);
            User::find($request->id)->update($data_user->toArray());

            if($request->info_add){
                $data_info_add = collect($request->info_add)->forget('user_id');
                if(empty(User::find($request->id)->info)) UserInfo::create($data_info_add->put('user_id', $request->id)->toArray());
                if(!empty(User::find($request->id)->info)) UserInfo::where('user_id',$request->id)->update($data_info_add->toArray());
            }

            if(User::find($request->id)->permission == 5){
                if(($request->status ?? 0) == 1) Mail::to(User::find($request->id)->email)->send(new UserNotifyStatus());
            }

            return response()->json(User::with('info')->find($request->id));
        }

        return response()->json('Voce não possui permississão suficiente!', 402);
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

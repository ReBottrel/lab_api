<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;

class AuthToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $access_token = $request->header()['access-token'][0];
        $access_token = explode(':', base64_decode($access_token));
        $user = User::where('email', ($access_token[0]??null))->where('access_token', ($access_token[1]??null))->where('token_expires_in', '>=', date('Y-m-d H:i:s'))->first();

        if($user) return $next($request);
        return response()->json('Token Invalido!',422);
    }
}

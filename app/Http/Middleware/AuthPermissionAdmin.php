<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthPermissionAdmin
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
        $user = user_token();
        if($user !== 10) return response()->json('voce não possui permissão!');
        return $next($request);
    }
}

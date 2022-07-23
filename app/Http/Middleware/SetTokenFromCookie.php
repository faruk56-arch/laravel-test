<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;

class SetTokenFromCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            if ($request->hasCookie('access_token')) {
                JWTAuth::setToken($request->cookie('access_token'))->authenticate();
            }
        } catch (\Exception $e) {
        } finally {
            return $next($request);
        }
    }
}

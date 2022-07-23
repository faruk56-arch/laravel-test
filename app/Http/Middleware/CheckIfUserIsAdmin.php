<?php

namespace App\Http\Middleware;

use App\Exceptions\UnauthorizedResourceException;
use Closure;

class CheckIfUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     * @throws \App\Exceptions\UnauthorizedResourceException
     */
    public function handle($request, Closure $next)
    {
        if (! isAdmin()) {
            throw new UnauthorizedResourceException;
        }

        return $next($request);
    }
}

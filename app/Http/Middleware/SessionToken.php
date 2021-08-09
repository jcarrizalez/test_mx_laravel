<?php

namespace App\Http\Middleware;

use Closure;

class SessionToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$request->header('SessionToken')) {
            throw new \App\Exceptions\InvalidSessionToken;
        }

        return $next($request);
    }
}

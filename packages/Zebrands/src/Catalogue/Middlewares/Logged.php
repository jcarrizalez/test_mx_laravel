<?php
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Middlewares;

use Closure;

class Logged
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
                
        app('Context')->requireLogin();

        return $next($request);
    }
}

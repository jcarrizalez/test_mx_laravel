<?php
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Middlewares;

use Zebrands\Catalogue\Domain\Shrared\Exception;
use Closure;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        

        $authorization = ($request->headers->get('authorization')?:$request->cookie('Authorization'));

        if(!$authorization) {
            // Unauthorized response if token not there
            throw new Exception(4030);
        }       
        app('Context')->addJwt($authorization);
        
        return $next($request);
    }
}
<?php

namespace App\Http\Middleware;

use App\Http\Controllers\General\CryptIO;
use App\Http\Controllers\General\Jwt;
use App\Models\User;
use Closure;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */

    public function handle($request, Closure $next)
    {
        if (!Jwt::isJwtValid($request->bearerToken()))
            return CryptIO::unauthorized();
        else {
            return $next($request);
        }
    }
}
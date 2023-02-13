<?php

namespace App\Http\Middleware;

use App\Http\Controllers\General\CryptIO;
use App\Http\Controllers\General\Jwt;
use App\Models\User;
use App\Models\UserDevice;
use Browser;
use Closure;

class JwtRefreshTokenMiddleware
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
        if (!Jwt::isJwtValid($request['refresh_token']))
            return CryptIO::unauthorized();
        else {
            return $next($request);
        }
    }
}
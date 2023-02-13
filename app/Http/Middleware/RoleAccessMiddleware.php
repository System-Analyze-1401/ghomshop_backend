<?php

namespace App\Http\Middleware;

use App\Http\Controllers\General\CryptIO;
use App\Http\Controllers\General\Jwt;
use App\Models\Role;
use App\Models\RoleAccess;
use App\Models\User;
use Closure;

class RoleAccessMiddleware
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
        $user = User::descriptionUser(Jwt::JwtTokenPayload($request->bearerToken())->user_id);

        if ($user['role'] == User::ROLE_ADMIN)
            return $next($request);
        else
            return CryptIO::accessDenied();

    }
}
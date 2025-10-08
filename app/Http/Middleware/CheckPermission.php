<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            return $next($request);
        }

        // Untuk adminsecond: cek apakah permission tersedia
        if ($user->role === 'adminsecond') {
            $permissions = $user->permissions ?? [];
            if (in_array($permission, $permissions)) {
                return $next($request);
            }
        }

        abort(403, 'Anda tidak punya akses ke menu ini.');
    }
}


<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $user = auth()->user();
        if (!$user) {
            abort(403, 'Anda belum login.');
        }

        if ($user->role === 'admin') {
            return $next($request);
        }

        if (is_null($user->role)) {
            abort(403, 'Akun Anda belum memiliki izin untuk mengakses sistem ini.');
        }

        if ($user->role === 'stokis' && is_null($user->warehouse_id)) {
            abort(403, 'Akun stokis Anda belum memiliki warehouse yang terdaftar.');
        }

        // Pecah role parameter menjadi array
        $allowedRoles = [];
        foreach ($roles as $role) {
            $allowedRoles = array_merge($allowedRoles, explode('|', $role));
        }

        if (!in_array($user->role, $allowedRoles)) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}

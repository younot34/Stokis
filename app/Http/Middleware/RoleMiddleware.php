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

        // Pastikan sudah login
        if (!$user) {
            abort(403, 'Anda belum login.');
        }

        // Jika user tidak punya role sama sekali
        if (is_null($user->role)) {
            abort(403, 'Akun Anda belum memiliki izin untuk mengakses sistem ini.');
        }

        // Jika user role-nya stokis tapi belum punya warehouse
        if ($user->role === 'stokis' && is_null($user->warehouse_id)) {
            abort(403, 'Akun stokis Anda belum memiliki warehouse yang terdaftar.');
        }

        // Jika role user tidak sesuai dengan parameter yang diterima
        if (!in_array($user->role, $roles)) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}

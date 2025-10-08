<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AutoResourcePermission
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!$user) abort(403, 'Unauthorized');

        // Admin akses penuh
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Untuk adminsecond
        if ($user->role === 'adminsecond') {

            // Pastikan relasi di-load
            $permissions = $user->permissions()->pluck('permission')->toArray();

            $routeName = $request->route()->getName();
            if (!$routeName) abort(403, 'Route tidak bernama, middleware tidak bisa memproses.');

            // Ambil nama modul dari route name
            $name = Str::after($routeName, 'admin.');
            $name = Str::before($name, '.') ?: $name;

            $action = $request->route()->getActionMethod();

            // Tentukan permission yang dibutuhkan
            $perm = match ($action) {
                'index', 'show' => "$name.view",
                'create', 'store' => "$name.create",
                'edit', 'update' => "$name.edit",
                'destroy' => "$name.delete",
                default => "$name.view",
            };

            if (in_array($perm, $permissions)) {
                return $next($request);
            }

            abort(403, "Anda tidak punya izin untuk $action di modul $name");
        }

        abort(403, 'Akses ditolak.');
    }
}
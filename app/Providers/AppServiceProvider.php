<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // 🔒 Permission check helper
        Blade::if('canView', function ($module) {
            $user = auth()->user();
            if (!$user) return false;
            if ($user->role === 'admin') return true;
            return in_array("$module.view", $user->permissions->pluck('permission')->toArray() ?? []);
        });

        Blade::if('canCreate', function ($module) {
            $user = auth()->user();
            if (!$user) return false;
            if ($user->role === 'admin') return true;
            return in_array("$module.create", $user->permissions->pluck('permission')->toArray() ?? []);

        });

        Blade::if('canEdit', function ($module) {
            $user = auth()->user();
            if (!$user) return false;
            if ($user->role === 'admin') return true;
            return in_array("$module.edit", $user->permissions->pluck('permission')->toArray() ?? []);

        });

        Blade::if('canDelete', function ($module) {
            $user = auth()->user();
            if (!$user) return false;
            if ($user->role === 'admin') return true;
            return in_array("$module.delete", $user->permissions->pluck('permission')->toArray() ?? []);

        });
    }
}

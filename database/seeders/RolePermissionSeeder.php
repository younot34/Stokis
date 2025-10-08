<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // === 1️⃣ Buat akun admin utama (punya semua akses) ===
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin'
            ]
        );

        // === 3️⃣ Daftar semua permission berdasarkan route kamu ===
        $permissions = [
            'dashboard.view',
            'warehouses.manage',
            'categories.manage',
            'products.manage',
            'purchase_orders.manage',
            'central_stocks.manage',
            'stocks.manage',
            'reports.manage',
            'deposits.manage',
            'users.manage',
            'tracker.manage',
            'kirims.manage',
            'transactions.manage',
        ];

        // === 4️⃣ Admin utama punya semua akses ===
        foreach ($permissions as $perm) {
            Permission::updateOrCreate([
                'user_id' => $admin->id,
                'permission' => $perm,
            ]);
        }

        echo "✅ RolePermissionSeeder berhasil dijalankan.\n";
    }
}

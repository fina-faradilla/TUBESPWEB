<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // ===== Permissions =====
        $adminPermissions = [
            'lihat dashboard',
            'kelola laporan',
            'verifikasi laporan',
            'hapus laporan',
            'kelola kategori',
        ];

        $userPermissions = [
            'buat laporan',
            'lihat laporan sendiri',
        ];

        foreach ([...$adminPermissions, ...$userPermissions] as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // ===== Roles =====
        $adminRole = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);
        $adminRole->syncPermissions($adminPermissions);

        $userRole = Role::firstOrCreate([
            'name' => 'User',
            'guard_name' => 'web',
        ]);
        $userRole->syncPermissions($userPermissions);

        // ===== Assign ke akun contoh =====
        $admin = User::where('email', 'admin@roadfix.com')->first();
        if ($admin) {
            $admin->assignRole($adminRole);
        }

        $user = User::where('email', 'test@example.com')->first();
        if ($user) {
            $user->assignRole($userRole);
        }
    }
}
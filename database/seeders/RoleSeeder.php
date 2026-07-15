<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        $userRole = Role::firstOrCreate([
            'name' => 'User',
            'guard_name' => 'web',
        ]);

        // Admin
        $admin = User::where('email', 'admin@roadfix.com')->first();

        if ($admin) {
            $admin->assignRole($adminRole);
        }

        // User biasa
        $user = User::where('email', 'test@example.com')->first();

        if ($user) {
            $user->assignRole($userRole);
        }
    }
}
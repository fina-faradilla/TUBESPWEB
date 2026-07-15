<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
{
    // Jalankan RoleSeeder dan LaporanSeeder
    $this->call([
        RoleSeeder::class,
        LaporanSeeder::class,
    ]);

    // User biasa
    $user = User::firstOrCreate(
        ['email' => 'test@example.com'],
        [
            'name' => 'Test User',
            'password' => bcrypt('password'),
        ]
    );

    $user->assignRole('User');

    // Admin
    $admin = User::firstOrCreate(
        ['email' => 'admin@roadfix.com'],
        [
            'name' => 'Admin',
            'password' => bcrypt('admin123'),
        ]
    );

    $admin->assignRole('Admin');
}
}
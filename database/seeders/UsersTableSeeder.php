<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User', 'password' => Hash::make('password123')]
        );
        $admin->assignRole('admin');

        // Merchant user
        $merchant = User::firstOrCreate(
            ['email' => 'merchant@example.com'],
            ['name' => 'Merchant User', 'password' => Hash::make('password123')]
        );
        $merchant->assignRole('merchant');

        // Regular user
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            ['name' => 'Regular User', 'password' => Hash::make('password123')]
        );
        $user->assignRole('user');
    }
}

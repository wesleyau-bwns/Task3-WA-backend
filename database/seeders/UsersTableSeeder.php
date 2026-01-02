<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate([
            'name' => 'user',
            'guard_name' => 'user-api'
        ]);

        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            ['name' => 'Regular User', 'password' => Hash::make('password123')]
        );

        $user->assignRole($role);
    }
}

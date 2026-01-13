<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate([
            'name' => 'user',
            'guard_name' => 'user-api'
        ]);

        $user = User::create([
            'name' => 'Basic User',
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        $user->assignRole($role);
    }
}

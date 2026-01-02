<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the roles
        $roles = ['admin', 'merchant', 'user'];

        // Create roles if they don't exist
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}

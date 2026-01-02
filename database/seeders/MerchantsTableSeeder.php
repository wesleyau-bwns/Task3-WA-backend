<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Merchant;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class MerchantsTableSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate([
            'name' => 'merchant',
            'guard_name' => 'merchant-api'
        ]);

        $merchant = Merchant::create([
            'name' => 'Merchant Demo',
            'email' => 'merchant@example.com',
            'password' => Hash::make('password123'),
            'store_name' => 'Demo Store',
            'store_description' => 'This is a standalone merchant store',
            'status' => 'active',
        ]);

        $merchant->assignRole($role);
    }
}

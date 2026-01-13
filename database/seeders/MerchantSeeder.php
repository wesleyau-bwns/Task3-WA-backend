<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Merchant;
use Spatie\Permission\Models\Role;

class MerchantSeeder extends Seeder
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
            'password' => 'password123',
            'store_name' => 'Demo Store',
            'store_description' => 'This is a standalone merchant store',
            'status' => 'active',
        ]);

        $merchant->assignRole($role);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Merchant;
use App\Models\User;

class MerchantsTableSeeder extends Seeder
{
    public function run(): void
    {
        $merchantUser = User::whereHas('roles', fn($q) => $q->where('name', 'merchant'))->first();

        Merchant::firstOrCreate(
            ['user_id' => $merchantUser->id],
            [
                'store_name' => 'Demo Store',
                'store_description' => 'This is a demo store.',
                'status' => 'active'
            ]
        );
    }
}

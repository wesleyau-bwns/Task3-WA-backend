<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Merchant;

class ProductsTableSeeder extends Seeder
{
    public function run(): void
    {
        $merchant = Merchant::first();

        Product::firstOrCreate(
            ['merchant_id' => $merchant->id, 'name' => 'Demo Product'],
            [
                'description' => 'This is a sample product',
                'price' => 19.99,
                'status' => 'active'
            ]
        );
    }
}

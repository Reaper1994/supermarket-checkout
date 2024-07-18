<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'code' => 'FR1',
                'name' => 'Fruit tea',
                'price' => 3.11,
                'inventory_stock' => 100
            ],
            [
                'code' => 'SR1',
                'name' => 'Strawberries',
                'price' => 5.00,
                'inventory_stock' => 200
            ],
            [
                'code' => 'CF1',
                'name' => 'Coffee',
                'price' => 11.23,
                'inventory_stock' => 150
            ]
        ]);
    }
}

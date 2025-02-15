<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'category_id' => 1,
                'name' => 'Laptop Gaming',
                'slug' => Str::slug('Laptop Gaming'),
                'image' => json_encode([]),
                'description' => 'Laptop gaming dengan spesifikasi tinggi.',
                'stock' => 10,
                'base_price' => 15000000.00,
                'het_price' => 16000000.00,
                'external_product' => json_encode([]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 1,
                'name' => 'Mouse Wireless',
                'slug' => Str::slug('Mouse Wireless'),
                'image' => json_encode([]),
                'description' => 'Mouse tanpa kabel dengan sensitivitas tinggi.',
                'stock' => 50,
                'base_price' => 250000.00,
                'het_price' => 300000.00,
                'external_product' => json_encode([]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'name' => 'Keyboard Mechanical',
                'slug' => Str::slug('Keyboard Mechanical'),
                'image' => json_encode([]),
                'description' => 'Keyboard dengan switch mechanical.',
                'stock' => 30,
                'base_price' => 800000.00,
                'het_price' => 850000.00,
                'external_product' => json_encode([]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'name' => 'Monitor 24 Inch',
                'slug' => Str::slug('Monitor 24 Inch'),
                'image' => json_encode([]),
                'description' => 'Monitor dengan resolusi Full HD.',
                'stock' => 20,
                'base_price' => 2000000.00,
                'het_price' => 2200000.00,
                'external_product' => json_encode([]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 3,
                'name' => 'Headset Gaming',
                'slug' => Str::slug('Headset Gaming'),
                'image' => json_encode([]),
                'description' => 'Headset dengan suara surround.',
                'stock' => 40,
                'base_price' => 600000.00,
                'het_price' => 700000.00,
                'external_product' => json_encode([]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('products')->insert($products);
    }
}

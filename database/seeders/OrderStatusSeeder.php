<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'Diproses',
                'slug' => Str::slug('Diproses'),
                'sort_order' => 1,
                'description' => 'Pesanan sedang diproses untuk diambil.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Siap Diambil',
                'slug' => Str::slug('Siap Diambil'),
                'sort_order' => 2,
                'description' => 'Pesanan siap untuk diambil oleh pembeli.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Selesai',
                'slug' => Str::slug('Selesai'),
                'sort_order' => 3,
                'description' => 'Pesanan telah selesai dan sudah diambil oleh pembeli.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dibatalkan',
                'slug' => Str::slug('Dibatalkan'),
                'sort_order' => 4,
                'description' => 'Pesanan dibatalkan oleh pembeli atau penjual.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        DB::table('order_statuses')->insert($statuses);
    }
}

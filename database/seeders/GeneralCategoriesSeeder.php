<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GeneralCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menambahkan data ke dalam tabel general_categories
        $generalCategories = [
            [
                'name' => 'Men',
                'slug' => Str::slug('men'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Women',
                'slug' => Str::slug('women'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tools',
                'slug' => Str::slug('tools'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        // Insert data ke tabel general_categories
        DB::table('general_categories')->insert($generalCategories);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil slug dari general_categories berdasarkan ID
        $generalCategories = DB::table('general_categories')->pluck('slug', 'id'); 

        $categories = [
            ['general_category_id' => 1, 'name' => 'Backpack', 'is_active' => true],
            ['general_category_id' => 1, 'name' => 'Bag', 'is_active' => true],
            ['general_category_id' => 2, 'name' => 'Wallet', 'is_active' => true],
            ['general_category_id' => 2, 'name' => 'Backpack', 'is_active' => true],
            ['general_category_id' => 2, 'name' => 'Bag', 'is_active' => true],
            ['general_category_id' => 2, 'name' => 'Mini Bag', 'is_active' => true],
            ['general_category_id' => 2, 'name' => 'Watch', 'is_active' => true],
            ['general_category_id' => 3, 'name' => 'Powerbank', 'is_active' => true],
            ['general_category_id' => 3, 'name' => 'Tumbler', 'is_active' => true],
            ['general_category_id' => 3, 'name' => 'Packaging', 'is_active' => true],
        ];

        foreach ($categories as $category) {
            // Ambil slug general category berdasarkan ID
            $generalCategorySlug = $generalCategories[$category['general_category_id']] ?? 'unknown';

            // Slug format: [general_category_slug]-[category_name]
            $slug = Str::slug($generalCategorySlug . '-' . $category['name']);

            // Cek apakah slug sudah ada di tabel categories
            $exists = DB::table('categories')->where('slug', $slug)->exists();

            if (!$exists) {
                DB::table('categories')->insert([
                    'general_category_id' => $category['general_category_id'],
                    'name' => $category['name'],
                    'slug' => $slug,
                    'is_active' => $category['is_active'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

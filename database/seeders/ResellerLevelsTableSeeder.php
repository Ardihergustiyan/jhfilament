<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ResellerLevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resellerLevels = [
            ['name' => 'Ruby', 'slug' => Str::slug('ruuby')],
            ['name' => 'Bronze', 'slug' => Str::slug('bronze')],
            ['name' => 'Silver', 'slug' => Str::slug('silver')],
            ['name' => 'Gold', 'slug' => Str::slug('gold')],
        ];

        foreach ($resellerLevels as $level) {
            DB::table('reseller_levels')->insert([
                'name' => $level['name'],
                'slug' => $level['slug'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

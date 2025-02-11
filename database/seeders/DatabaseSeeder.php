<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            PermissionSeeder::class,
            ResellerLevelsTableSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            GeneralCategoriesSeeder::class,
            CategorySeeder::class,
            OrderStatusSeeder::class
        ]);
    }
}

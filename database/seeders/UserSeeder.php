<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Buat user baru
        $userAdmin = User::create([
            'first_name' => 'admin',
            'last_name' => 'toko',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'remember_token' => Str::random(10),
        ]);
        
        // Tetapkan role "Admin" menggunakan Spatie
        $userAdmin->assignRole('Admin');
        
        $userReseller = User::create([
            'first_name' => 'reseller',
            'last_name' => 'toko',
            'email' => 'reseller@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('reseller123'),
            'remember_token' => Str::random(10),
        ]);
        
        // Tetapkan role "Reseller"
        $userReseller->assignRole('Reseller');
        
        $userCustomer = User::create([
            'first_name' => 'customer',
            'last_name' => 'toko',
            'email' => 'customer@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('customer123'),
            'remember_token' => Str::random(10),
        ]);
        
        // Tetapkan role "Customer"
        $userCustomer->assignRole('Customer');

    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@mamitha.com'],
            [
                'name' => 'Admin Mamitha',
                'username' => 'admin',
                'email' => 'admin@mamitha.com',
                'phone' => '081234567890',
                'password' => Hash::make('Admin123'),
                'role' => 'admin',
            ]
        );
        User::updateOrCreate(
            ['email' => 'kitchen@mamitha.com'],
            [
                'name' => 'Kitchen Staff',
                'username' => 'kitchen',
                'email' => 'kitchen@mamitha.com',
                'phone' => '081234567891',
                'password' => Hash::make('Kitchen123'),
                'role' => 'kitchen',
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@mamitha.com'],
            [
                'name' => 'Customer Demo',
                'username' => 'customer',
                'email' => 'customer@mamitha.com',
                'phone' => '081234567892',
                'password' => Hash::make('Customer123'),
                'role' => 'customer',
            ]
        );

        User::updateOrCreate(
            ['email' => 'courier@mamitha.com'],
            [
                'name' => 'Courier Staff',
                'username' => 'courier',
                'email' => 'courier@mamitha.com',
                'phone' => '081234567893',
                'password' => Hash::make('Courier123'),
                'role' => 'courier',
            ]
        );

        // Seed default categories for Mamitha Bakery
        $categories = [
            ['name' => 'Roti', 'slug' => 'roti'],
            ['name' => 'Kue', 'slug' => 'kue'],
            ['name' => 'Pastry', 'slug' => 'pastry'],
            ['name' => 'Minuman', 'slug' => 'minuman'],
            ['name' => 'Hampers & Paket', 'slug' => 'hampers-paket'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                [
                    'name' => $category['name'],
                    'is_active' => true,
                ]
            );
        }
    }
}

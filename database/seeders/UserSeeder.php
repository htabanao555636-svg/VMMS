<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin Account
        User::updateOrCreate(
            ['email' => 'admin@easyfix.com'],
            [
                'name'     => 'System Admin',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
                'is_active'=> true,
            ]
        );

        // Staff Account
        User::updateOrCreate(
            ['email' => 'staff@easyfix.com'],
            [
                'name'     => 'Staff Member',
                'password' => Hash::make('staff123'),
                'role'     => 'staff',
                'is_active'=> true,
            ]
        );

        // Sample Customer
        User::updateOrCreate(
            ['email' => 'customer@easyfix.com'],
            [
                'name'     => 'Sample Customer',
                'password' => Hash::make('customer123'),
                'role'     => 'customer',
                'is_active'=> true,
            ]
        );
    }
}

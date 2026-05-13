<?php

namespace Database\Seeders;

use App\Models\WheelerCategory;
use Illuminate\Database\Seeder;

class WheelerCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wheelerCategories = [
            [
                'name' => '2-Wheelers',
                'description' => 'Services for motorcycles and scooters',
                'status' => 'active',
            ],
            [
                'name' => '3-Wheelers',
                'description' => 'Services for auto-rickshaws and three-wheeled vehicles',
                'status' => 'active',
            ],
            [
                'name' => '4-Wheelers',
                'description' => 'Services for cars and compact vehicles',
                'status' => 'active',
            ],
            [
                'name' => 'Heavy Vehicles',
                'description' => 'Services for trucks, buses, and commercial vehicles',
                'status' => 'active',
            ],
        ];

        foreach ($wheelerCategories as $category) {
            WheelerCategory::firstOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}

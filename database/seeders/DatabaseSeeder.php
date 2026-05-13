<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Mechanic;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call UserSeeder to create default accounts (1 admin, 1 staff, 1 customer)
        $this->call([
            UserSeeder::class,
        ]);

        // Create categories (4 total)
        $categories = [
            Category::create(['name' => 'Engine Services', 'status' => 'active']),
            Category::create(['name' => 'Tire Services', 'status' => 'active']),
            Category::create(['name' => 'Brake Systems', 'status' => 'active']),
            Category::create(['name' => 'Electrical Systems', 'status' => 'active']),
        ];

        // Create mechanics (5 total)
        $mechanics = Mechanic::factory(5)->create();

        // Create services (10 total) - assign to existing categories
        $services = [];
        for ($i = 0; $i < 10; $i++) {
            $services[] = Service::factory()->create([
                'category_id' => $categories[$i % count($categories)]->id,
            ]);
        }

        // Get the single customer user
        $customer = User::where('role', 'customer')->first();

        // Create service requests (5 total) - use the single customer
        for ($i = 0; $i < 5; $i++) {
            ServiceRequest::factory()->create([
                'customer_id' => $customer->id,
                'mechanic_id' => $mechanics[$i % $mechanics->count()]->id,
                'service_id' => $services[$i % count($services)]->id,
            ]);
        }
    }
}


<?php

namespace Database\Factories;

use App\Models\Mechanic;
use App\Models\Service;
use App\Models\User;
use App\Models\ServiceRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ServiceRequest>
 */
class ServiceRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => User::factory(),
            'mechanic_id' => Mechanic::factory(),
            'service_id' => Service::factory(),
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed', 'cancelled']),
            'notes' => $this->faker->sentence(),
            'requested_date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'completed_date' => null,
        ];
    }

    /**
     * Indicate that the service request is completed
     */
    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'completed',
                'completed_date' => $this->faker->dateTimeBetween($attributes['requested_date'], 'now'),
            ];
        });
    }

    /**
     * Indicate that the service request is in progress
     */
    public function inProgress()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'in_progress',
            ];
        });
    }
}

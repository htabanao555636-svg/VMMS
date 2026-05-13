<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mechanic>
 */
class MechanicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'specialization' => $this->faker->randomElement([
                'Engine Repair',
                'Transmission',
                'Brake Systems',
                'Electrical Systems',
                'Oil Change',
                'General Maintenance'
            ]),
            'certificate_path' => null,
            'status' => 'active',
            'date_added' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Enums\OfficerRole;
use App\Enums\OfficerStatus;
use App\Models\District;
use App\Models\Officer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Officer>
 */
class OfficerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->optional(0.7)->safeEmail(),
            'role' => fake()->randomElement(OfficerRole::cases()),
            'district_id' => District::factory(),
            'status' => fake()->randomElement(OfficerStatus::cases()),
        ];
    }

    /**
     * Indicate the officer is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => OfficerStatus::Active,
        ]);
    }

    /**
     * Indicate the officer is a coordinator.
     */
    public function coordinator(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => OfficerRole::Coordinator,
        ]);
    }
}

<?php

namespace Database\Factories;

use App\Enums\ConfirmationStatus;
use App\Enums\OfficerRole;
use App\Models\Assignment;
use App\Models\Officer;
use App\Models\PollingStation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Assignment>
 */
class AssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'polling_station_id' => PollingStation::factory(),
            'officer_id' => Officer::factory(),
            'role' => fake()->randomElement(OfficerRole::cases()),
            'confirmation_status' => fake()->randomElement(ConfirmationStatus::cases()),
            'notes' => fake()->optional(0.2)->sentence(),
            'assigned_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'confirmed_at' => fake()->optional(0.5)->dateTimeBetween('-1 week', 'now'),
        ];
    }

    /**
     * Indicate the assignment is confirmed.
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'confirmation_status' => ConfirmationStatus::Confirmed,
            'confirmed_at' => now(),
        ]);
    }

    /**
     * Indicate the assignment is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'confirmation_status' => ConfirmationStatus::Pending,
            'confirmed_at' => null,
        ]);
    }
}

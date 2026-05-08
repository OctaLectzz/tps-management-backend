<?php

namespace Database\Factories;

use App\Enums\PollingStationStatus;
use App\Models\District;
use App\Models\PollingStation;
use App\Models\Village;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PollingStation>
 */
class PollingStationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $district = District::factory();

        return [
            'village_id' => Village::factory(),
            'district_id' => $district,
            'station_number' => fake()->numberBetween(1, 20),
            'venue_name' => fake()->company().' Hall',
            'address' => fake()->address(),
            'latitude' => fake()->latitude(-7.7, -7.5),
            'longitude' => fake()->longitude(110.7, 110.9),
            'status' => fake()->randomElement(PollingStationStatus::cases()),
            'notes' => fake()->optional(0.3)->sentence(),
        ];
    }

    /**
     * Indicate the polling station is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PollingStationStatus::Active,
        ]);
    }

    /**
     * Indicate the polling station is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PollingStationStatus::Inactive,
        ]);
    }

    /**
     * Indicate the polling station is under review.
     */
    public function review(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PollingStationStatus::Review,
        ]);
    }
}

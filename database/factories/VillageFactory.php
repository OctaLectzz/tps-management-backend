<?php

namespace Database\Factories;

use App\Models\District;
use App\Models\Village;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Village>
 */
class VillageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'district_id' => District::factory(),
            'code' => fake()->unique()->numerify('33.11.##.####'),
            'name' => fake()->unique()->streetName(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\PollingStation;
use App\Models\User;
use App\Models\VoteResult;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VoteResult>
 */
class VoteResultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dpt = fake()->numberBetween(200, 500);
        $votersPresent = fake()->numberBetween((int) ($dpt * 0.5), $dpt);
        $totalVotes = fake()->numberBetween((int) ($votersPresent * 0.9), $votersPresent);
        $partyVotes = fake()->numberBetween(0, $totalVotes);

        return [
            'polling_station_id' => PollingStation::factory(),
            'party_votes' => $partyVotes,
            'total_votes' => $totalVotes,
            'dpt' => $dpt,
            'voters_present' => $votersPresent,
            'submitted_by' => User::factory(),
            'submitted_at' => fake()->dateTimeBetween('-1 week', 'now'),
            'verified' => fake()->boolean(30),
        ];
    }

    /**
     * Indicate the vote result is verified.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'verified' => true,
        ]);
    }
}

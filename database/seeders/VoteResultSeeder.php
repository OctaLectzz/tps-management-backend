<?php

namespace Database\Seeders;

use App\Models\PollingStation;
use App\Models\User;
use App\Models\VoteResult;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class VoteResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pollingStations = PollingStation::all();
        $users = User::where('role', 'operator')->get();

        if ($users->isEmpty()) {
            $users = User::all();
        }

        // Add results for ~50% of stations
        $stationsWithResults = $pollingStations->random($pollingStations->count() * 0.5);

        foreach ($stationsWithResults as $station) {
            $dpt = rand(200, 300);
            $votersPresent = rand(150, $dpt);
            $partyVotes = rand(50, $votersPresent);

            VoteResult::create([
                'polling_station_id' => $station->id,
                'party_votes' => $partyVotes,
                'total_votes' => $votersPresent, // Simplified: total valid votes = voters present
                'dpt' => $dpt,
                'voters_present' => $votersPresent,
                'submitted_by' => $users->random()->id,
                'submitted_at' => Carbon::now()->subDays(rand(0, 2)),
                'verified' => fake()->boolean(80),
            ]);
        }
    }
}

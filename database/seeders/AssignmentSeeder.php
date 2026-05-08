<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Officer;
use App\Models\PollingStation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pollingStations = PollingStation::all();
        $officers = Officer::all();

        // Assign officers to polling stations
        // We'll try to assign at least one officer to ~70% of stations
        $stationsToAssign = $pollingStations->random($pollingStations->count() * 0.7);

        foreach ($stationsToAssign as $station) {
            // Assign 1-2 random officers who belong to the same district or are nearby
            // For simplicity, just pick random officers for now
            $assignedOfficers = $officers->random(rand(1, 2));

            foreach ($assignedOfficers as $officer) {
                // Check if already assigned to this station to avoid unique constraint error
                if (! Assignment::where('polling_station_id', $station->id)->where('officer_id', $officer->id)->exists()) {
                    Assignment::create([
                        'polling_station_id' => $station->id,
                        'officer_id' => $officer->id,
                        'role' => $officer->role,
                        'confirmation_status' => fake()->randomElement(['pending', 'confirmed', 'absent']),
                        'assigned_at' => Carbon::now()->subDays(rand(1, 10)),
                        'confirmed_at' => fake()->boolean(70) ? Carbon::now()->subDays(rand(0, 5)) : null,
                    ]);
                }
            }
        }
    }
}

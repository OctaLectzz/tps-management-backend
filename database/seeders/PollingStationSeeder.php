<?php

namespace Database\Seeders;

use App\Models\PollingStation;
use App\Models\Village;
use Illuminate\Database\Seeder;

class PollingStationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all villages in Sukoharjo
        $villages = Village::all();

        foreach ($villages as $village) {
            // Randomly create 1-3 polling stations per village for variety
            $count = rand(1, 3);
            for ($i = 1; $i <= $count; $i++) {
                PollingStation::create([
                    'village_id' => $village->id,
                    'district_id' => $village->district_id,
                    'station_number' => $i,
                    'venue_name' => fake()->randomElement(['SD Negeri', 'Balai Desa', 'Halaman Rumah Warga', 'Gedung Serbaguna', 'Masjid'])." $i ".$village->name,
                    'address' => fake()->address(),
                    'latitude' => fake()->latitude(-7.72, -7.58),
                    'longitude' => fake()->longitude(110.75, 110.92),
                    'status' => 'active',
                ]);
            }
        }
    }
}

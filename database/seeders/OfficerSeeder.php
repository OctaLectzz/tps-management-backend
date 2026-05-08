<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Officer;
use Illuminate\Database\Seeder;

class OfficerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $districts = District::all();
        $roles = ['coordinator', 'kpps', 'witness', 'observer'];

        // Create ~1000 officers
        for ($i = 0; $i < 1000; $i++) {
            Officer::create([
                'name' => fake('id_ID')->name(),
                'phone' => '08'.fake()->numerify('##########'),
                'email' => fake()->unique()->safeEmail(),
                'role' => fake()->randomElement($roles),
                'district_id' => $districts->random()->id,
                'status' => 'active',
            ]);
        }
    }
}

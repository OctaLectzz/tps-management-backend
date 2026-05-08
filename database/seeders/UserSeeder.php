<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create an Admin user
        User::factory()->create([
            'name' => 'Admin System',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'status' => true,
        ]);

        // Create a Operator User
        User::factory()->create([
            'name' => 'Operator System',
            'email' => 'operator@example.com',
            'role' => 'operator',
            'status' => true,
        ]);

        User::factory(10)->create([
            'role' => 'admin',
        ]);

        User::factory(20)->create([
            'role' => 'operator',
        ]);
    }
}

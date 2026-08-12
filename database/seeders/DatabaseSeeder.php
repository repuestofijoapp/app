<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'), // Simple password for testing
            'role' => 'mechanic',
        ]);

        $this->call([
            CategorySeeder::class,
            VehicleSeeder::class,
            OemProductSeeder::class,
            ProviderSeeder::class,
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test user for mechanic
        User::create([
            'name' => 'Usuario Prueba Mecánico',
            'email' => 'mecanico@prueba.com',
            'password' => Hash::make('123456'),
            'google_id' => 'test_mecanico_123',
            'role' => UserRole::Mechanic,
            'email_verified_at' => now(),
            'business_name' => 'Taller Ejemplo',
            'ruc_dni' => '12345678',
        ]);

        // Create test user for provider
        User::create([
            'name' => 'Usuario Prueba Proveedor',
            'email' => 'proveedor@prueba.com',
            'password' => Hash::make('123456'),
            'google_id' => 'test_proveedor_123',
            'role' => UserRole::Provider,
            'email_verified_at' => now(),
        ]);

        // Create test user for admin
        User::create([
            'name' => 'Usuario Prueba Admin',
            'email' => 'admin@prueba.com',
            'password' => Hash::make('123456'),
            'google_id' => 'test_admin_123',
            'role' => UserRole::Admin,
            'email_verified_at' => now(),
        ]);
    }
}
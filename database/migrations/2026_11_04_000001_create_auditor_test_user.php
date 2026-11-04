<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        User::updateOrCreate(
            ['email' => 'auditor@repuestofijo.com'],
            [
                'name' => 'Auditor Culqi (Pruebas)',
                'password' => Hash::make('Culqi2026!'),
                'role' => UserRole::Mechanic,
                'email_verified_at' => now(),
                'phone' => '51983064166',
                'phone_verified_at' => now(),
                'onboarding_completed_at' => now(),
                'is_active' => true,
                'saved_addresses' => [
                    [
                        'type' => 'lima',
                        'label' => 'MIRAFLORES — Av. José Larco 345',
                        'district' => 'MIRAFLORES',
                        'address' => 'Av. José Larco 345, Oficina 401',
                        'referencia' => 'Frente al Parque Kennedy',
                        'lat' => -12.1218,
                        'lng' => -77.0298,
                        'ubigeo' => '150122',
                    ]
                ],
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        User::where('email', 'auditor@repuestofijo.com')->delete();
    }
};


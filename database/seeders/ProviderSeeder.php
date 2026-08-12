<?php

namespace Database\Seeders;

use App\Models\Provider;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providers = [
            [
                'business_name' => 'Repuestos El Chino S.A.C.',
                'email' => 'chino@repuestofijo.com',
                'ruc' => '20123456781',
                'specialty' => 'Toyota, Nissan, Hyundai',
                'whatsapp_number' => '+51987654321',
                'phone' => '01 4567891',
                'city' => 'Lima',
                'country' => 'Perú',
                'address' => 'Av. Iquitos 123, La Victoria'
            ],
            [
                'business_name' => 'Importaciones Benavides',
                'email' => 'benavides@repuestofijo.com',
                'ruc' => '20987654321',
                'specialty' => 'Frenos, Suspensión, Embragues',
                'whatsapp_number' => '+51912345678',
                'phone' => '01 7894561',
                'city' => 'Trujillo',
                'country' => 'Perú',
                'address' => 'Jr. Pizarro 456, Centro'
            ],
            [
                'business_name' => 'Motores & Más',
                'email' => 'motores@repuestofijo.com',
                'ruc' => '20556677881',
                'specialty' => 'Motores, Culatas, Rectificación',
                'whatsapp_number' => '+51955667788',
                'phone' => '01 5564433',
                'city' => 'Arequipa',
                'country' => 'Perú',
                'address' => 'Calle Mercaderes 789'
            ],
        ];

        foreach ($providers as $p) {
            Provider::updateOrCreate(
                ['business_name' => $p['business_name']],
                [
                    'ruc' => $p['ruc'],
                    'specialty' => $p['specialty'],
                    'whatsapp_number' => $p['whatsapp_number'],
                    'phone' => $p['phone'],
                    'contact_email' => $p['email'],
                    'address' => $p['address'],
                    'city' => $p['city'],
                    'country' => $p['country'],
                    'is_active' => true,
                ]
            );
        }
    }
}
